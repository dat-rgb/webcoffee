<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardServiceController();
    }
    public function getNguyenLieuCuaHang($ma_cua_hang)
    {
        return DB::table('cua_hang_nguyen_lieus as chnl')
            ->join('nguyen_lieus as nl', 'chnl.ma_nguyen_lieu', '=', 'nl.ma_nguyen_lieu')
            ->where('chnl.ma_cua_hang', $ma_cua_hang)
            ->where('chnl.trang_thai', 1) // Lấy nguyên liệu đang hoạt động
            ->select(
                'chnl.ma_nguyen_lieu',
                'nl.ten_nguyen_lieu',
                'nl.so_luong',
                'nl.don_vi',
                'nl.gia',
                'chnl.so_luong_ton',
                'chnl.don_vi as don_vi_tinh',
                'chnl.so_luong_ton_min',
                'chnl.so_luong_ton_max',
                'chnl.trang_thai'
            )
            ->get();
    }
    public function getNguyenLieuKiemKho($ma_cua_hang)
    {
        $nglKho = DB::table('cua_hang_nguyen_lieus as chnl')
            ->where('ma_cua_hang', $ma_cua_hang)
            ->where('chnl.so_luong_ton', '>', 0)
            ->join('nguyen_lieus as nl', 'chnl.ma_nguyen_lieu', '=', 'nl.ma_nguyen_lieu')
            ->select(
                'chnl.ma_nguyen_lieu',
                'nl.ten_nguyen_lieu',
                'nl.so_luong as so_luong_goc',
                'nl.don_vi',
                'chnl.so_luong_ton',
                'chnl.don_vi as don_vi_tinh'
            )
            ->get();

        foreach ($nglKho as $nl) {
            // Lấy danh sách lô nhập theo thứ tự thời gian
            $loList = DB::table('phieu_nhap_xuat_nguyen_lieus as pnxl')
                ->where('pnxl.ma_cua_hang', $ma_cua_hang)
                ->where('pnxl.ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->where('pnxl.loai_phieu', 0)
                ->orderBy('pnxl.han_su_dung', 'asc')
                ->get();

           // Định lượng dùng từ hóa đơn bán hàng
            $dinhLuongDaDungHD = DB::table('chi_tiet_hoa_dons as cthd')
                ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                ->join('thanh_phan_san_phams as tp', 'cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                ->where('hd.ma_cua_hang', $ma_cua_hang)
                ->where('tp.ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->sum(DB::raw('tp.dinh_luong * cthd.so_luong'));

            // Định lượng đã xuất qua phiếu xuất (loai_phieu = 1)
            $dinhLuongXuatKho = DB::table('phieu_nhap_xuat_nguyen_lieus')
                ->where('ma_cua_hang', $ma_cua_hang)
                ->where('ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->where('loai_phieu', 1)
                ->sum('dinh_luong');

            // Tổng định lượng đã dùng
            $dinhLuongDaDung = $dinhLuongDaDungHD + $dinhLuongXuatKho;


            // Quy định lượng đã dùng ra từng lô (FIFO)
            $loWithTon = [];
            foreach ($loList as $lo) {
                $left = $lo->dinh_luong;
                if ($dinhLuongDaDung > 0) {
                    $used = min($left, $dinhLuongDaDung);
                    $left -= $used;
                    $dinhLuongDaDung -= $used;
                }

                $soLuongConLai = round($left / max($nl->so_luong_goc, 1), 2);

                $loWithTon[] = [
                    'so_lo' => $lo->so_lo,
                    'han_su_dung' => $lo->han_su_dung,
                    'ton_lo' => $soLuongConLai,
                ];
            }

            $nl->lo_hang = $loWithTon;
        }

        return $nglKho;
    }
    public function index(Request $request)
    {

        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        if (!$nhanVien) {
            toastr()->error('Không tìm thấy thông tin nhân viên.');
            return redirect()->back();
        }
        $ma_cua_hang = $nhanVien->ma_cua_hang;
        $nguyen_lieu_cua_hang = $this->getNguyenLieuCuaHang($ma_cua_hang);
        $nguyen_lieu_kiem_kho = $this->getNguyenLieuKiemKho($ma_cua_hang);

        $hoaDonDaNhan = $this->dashboardService->countDonHangTheoTrangThai($ma_cua_hang,4);
        $hoaDonDaHuy = $this->dashboardService->countDonHangTheoTrangThai($ma_cua_hang,5);
        $hoaDonNgay = $this->dashboardService->countDonHangTheoNgay($ma_cua_hang,null,null, now()->toDateString());
        $tangTruongNgay = $this->dashboardService->tinhTyLeTangTruongDonHang('day', now(), $ma_cua_hang);
        $tongHoaDon = $this->dashboardService->countDonHangTheoNgay($ma_cua_hang,null,null, null);
        $doanhThuNgay = $this->dashboardService->sumDoanhThuTheoThoiGian($ma_cua_hang, 'day');
        $tongDoanhThu = $this->dashboardService->sumDoanhThuTheoThoiGian($ma_cua_hang, 'all');           
        $countNhanVien = $this->dashboardService->countNhanVien($ma_cua_hang);
        $topSPBanChay = $this->dashboardService->topSanPhamBanChay($ma_cua_hang);   
        $doanhTungThangTrongNam = $this->dashboardService->doanhThuTungThangTrongNam($ma_cua_hang);
        
        $labelsChart = [];
        $dataChart = [];

        foreach (range(1, 12) as $month) {
            $tenThang = 'Tháng ' . $month;
            $labelsChart[] = $tenThang;
            $dataChart[] = intval(round($doanhTungThangTrongNam[$tenThang] ?? 0));
        }

        $viewData = [
            'title' => 'Quản lý cửa hàng '.$ma_cua_hang.' | CDMT Coffee & Tea',
            'subtitle' => 'Thống kê cửa hàng '. $ma_cua_hang,
            'hoaDonDaNhan' => $hoaDonDaNhan,
            'hoaDonDaHuy' => $hoaDonDaHuy,
            'hoaDonNgay' => $hoaDonNgay,
            'tangTruongNgay' => $tangTruongNgay,
            'tongHoaDon' => $tongHoaDon,
            'doanhThuNgay' => $doanhThuNgay,
            'tongDoanhThu' => $tongDoanhThu,
            'countNhanVien' => $countNhanVien,
            'topSPBanChay' =>$topSPBanChay,
            'doanhTungThangTrongNam' => $doanhTungThangTrongNam,
            'dataChart' => $dataChart,
            'labelsChart' => $labelsChart,
            'cuaHangs' => $this->dashboardService->getCuaHangs(), 
            'selectedCuaHang' => $ma_cua_hang,
            'nguyen_lieu_cua_hang' => $nguyen_lieu_cua_hang,
            'nguyen_lieu_kiem_kho' => $nguyen_lieu_kiem_kho,
        ];

        return view('staffs.dashboards.index', $viewData);
    }
    public function exportPhieuNhap(Request $request)
    {
        $chonNhap = $request->input('chon_nhap', []);
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maPhieu = 'PNL-' . now()->format('dmY-His');

        $nguyenLieuNhap = [];

        foreach ($chonNhap as $maNL) {
            $soLuong = $request->input("so_luong_du_kien.$maNL");
            $donVi = $request->input("don_vi_tinh.$maNL");

            $nguyenLieu = DB::table('nguyen_lieus')
                ->where('ma_nguyen_lieu', $maNL)
                ->first();

            if ($nguyenLieu) {
                $nguyenLieuNhap[] = (object)[
                    'ma_nguyen_lieu'     => $nguyenLieu->ma_nguyen_lieu,
                    'ten_nguyen_lieu'    => $nguyenLieu->ten_nguyen_lieu,
                    'so_luong'           => $nguyenLieu->so_luong,
                    'don_vi'             => $nguyenLieu->don_vi,
                    'don_vi_tinh'        => $donVi,
                    'gia'                => $nguyenLieu->gia,
                    'so_luong_du_kien'   => $soLuong,
                ];
            }
        }

        $tongTien = collect($nguyenLieuNhap)->sum(function($nl) {
            return $nl->gia * $nl->so_luong_du_kien;
        });

        $cuaHang = DB::table('cua_hangs')
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
            ->first();

        $nguoiLap = $nhanVien->ho_ten_nhan_vien;

        $pdf = Pdf::loadView('exports.phieu_yeu_cau_nhap', [
            'nguyenLieuNhap' => $nguyenLieuNhap,
            'cuaHang'        => $cuaHang,
            'nguoiLap'       => $nguoiLap,
            'maPhieu'        => $maPhieu,
            'tongTien'       => $tongTien,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("{$maPhieu}.pdf");
    }
    public function exportPhieuXuat(Request $request)
    {
        $chonXuat = $request->input('chon_xuat', []);
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maPhieu = 'PNX-' . now()->format('dmY-His');

        $nguyenLieuXuat = [];

        foreach ($chonXuat as $maNL) {
            $soLuong = $request->input("so_luong_xuat.$maNL");
            $donVi = $request->input("don_vi_tinh.$maNL");

            $nguyenLieu = DB::table('nguyen_lieus')
                ->where('ma_nguyen_lieu', $maNL)
                ->first();

            if ($nguyenLieu) {
                $nguyenLieuXuat[] = (object)[
                    'ma_nguyen_lieu'     => $nguyenLieu->ma_nguyen_lieu,
                    'ten_nguyen_lieu'    => $nguyenLieu->ten_nguyen_lieu,
                    'so_luong'           => $nguyenLieu->so_luong,
                    'don_vi'             => $nguyenLieu->don_vi,
                    'don_vi_tinh'        => $donVi,
                    'gia'                => $nguyenLieu->gia,
                    'so_luong_xuat'      => $soLuong,
                ];
            }
        }

        $tongTien = collect($nguyenLieuXuat)->sum(function ($nl) {
            return $nl->gia * $nl->so_luong_xuat;
        });

        $cuaHang = DB::table('cua_hangs')
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
            ->first();

        $nguoiLap = $nhanVien->ho_ten_nhan_vien;

        $pdf = Pdf::loadView('exports.phieu_yeu_cau_xuat', [
            'nguyenLieuXuat' => $nguyenLieuXuat,
            'cuaHang'        => $cuaHang,
            'nguoiLap'       => $nguoiLap,
            'maPhieu'        => $maPhieu,
            'tongTien'       => $tongTien,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("{$maPhieu}.pdf");
    }
    public function exportPhieuKiemKho(Request $request)
    {
        $chonKiemKho = $request->input('chon_kiemkho', []);
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maPhieu = 'PKK-' . now()->format('dmY-His');

        $nguyenLieuList = [];

        foreach ($chonKiemKho as $maNL) {
            $nguyenLieu = $this->getNguyenLieuKiemKho($nhanVien->ma_cua_hang)
                ->firstWhere('ma_nguyen_lieu', $maNL);

            if ($nguyenLieu) {
                $tongTon = 0;
                foreach ($nguyenLieu->lo_hang ?? [] as $lo) {
                    $tongTon += $lo['ton_lo'] ?? 0;
                }

                $nguyenLieuList[] = (object)[
                    'ma_nguyen_lieu'   => $nguyenLieu->ma_nguyen_lieu,
                    'ten_nguyen_lieu'  => $nguyenLieu->ten_nguyen_lieu,
                    'don_vi'           => $nguyenLieu->don_vi,
                    'so_luong_goc'     => $nguyenLieu->so_luong_goc,
                    'don_vi_tinh'      => $nguyenLieu->don_vi_tinh,
                    'tong_ton'         => $tongTon,
                    'lo_hang'          => $nguyenLieu->lo_hang,
                ];
            }
        }

        $cuaHang = DB::table('cua_hangs')
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
            ->first();

        $nguoiLap = $nhanVien->ho_ten_nhan_vien;

        $pdf = Pdf::loadView('exports.phieu_kiem_kho', [
            'nguyenLieuList' => $nguyenLieuList,
            'cuaHang'        => $cuaHang,
            'nguoiLap'       => $nguoiLap,
            'maPhieu'        => $maPhieu,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("{$maPhieu}.pdf");
    }
}
