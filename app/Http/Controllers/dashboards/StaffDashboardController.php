<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardServiceController();
    }

    public function index(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        if (!$nhanVien) {
            toastr()->error('Không tìm thấy thông tin nhân viên.');
            return redirect()->back();
        }

        $ma_cua_hang = $nhanVien->ma_cua_hang;
        $startDate   = $request->query('start');
        $endDate     = $request->query('end');
        $mode        = $request->query('mode', 'month');

        $subtitle = 'Thống kê cửa hàng ' . $ma_cua_hang;

        // 1. Doanh thu
        $revenueData = $this->dashboardService->doanhThuTheoKhoang(
            $startDate,
            $endDate,
            $mode,
            $ma_cua_hang
        );
        $labelsChart = $revenueData['labels'];
        $dataChart   = $revenueData['values'];

        // 2. Lợi nhuận
        $profitData = $this->dashboardService->tinhLoiNhuan(
            $ma_cua_hang,
            $startDate,
            $endDate,
            $mode
        );

        // 3. Tổng hợp dashboard
        $viewData = [
            'title' => 'Quản lý cửa hàng ' . $ma_cua_hang . ' | CDMT Coffee & Tea',
            'subtitle' => $subtitle,
            'hoaDonDaNhan' => $this->dashboardService->countDonHangTheoTrangThai($ma_cua_hang, 4),
            'hoaDonDaHuy' => $this->dashboardService->countDonHangTheoTrangThai($ma_cua_hang, 5),
            'hoaDonNgay' => $this->dashboardService->countDonHangTheoNgay($ma_cua_hang, null, null, now()->toDateString()),
            'tangTruongNgay' => $this->dashboardService->tinhTyLeTangTruongDonHang('day', now(), $ma_cua_hang),
            'tongHoaDon' => $this->dashboardService->countDonHangTheoNgay($ma_cua_hang),
            'doanhThuNgay' => $this->dashboardService->sumDoanhThuTheoThoiGian($ma_cua_hang, 'day'),
            'tongDoanhThu' => $this->dashboardService->sumDoanhThuTheoThoiGian($ma_cua_hang, 'all'),
            'countNhanVien' => $this->dashboardService->countNhanVien($ma_cua_hang),
            'topSPBanChay' => $this->dashboardService->topSanPhamBanChay($ma_cua_hang),
            'labelsChart' => $labelsChart,
            'dataChart' => $dataChart,
            'cuaHangs' => $this->dashboardService->getCuaHangs(),
            'selectedCuaHang' => $ma_cua_hang,
            'labelsProfit' => $profitData['labels'],
            'tongChi' => $profitData['tongChi'],
            'tongThu' => $profitData['tongThu'],
            'loiNhuan' => $profitData['loiNhuan'],
            'nguyen_lieu_cua_hang' => $this->getNguyenLieuCuaHang($ma_cua_hang),
            'nguyen_lieu_kiem_kho' => $this->getNguyenLieuKiemKho($ma_cua_hang),
        ];

        return view('staffs.dashboards.index', $viewData);
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
                'chnl.gia_nhap',
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

        $now = Carbon::now()->startOfDay();

        foreach ($nglKho as $nl) {
            $loList = DB::table('phieu_nhap_xuat_nguyen_lieus')
                ->where('ma_cua_hang', $ma_cua_hang)
                ->where('ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->where('loai_phieu', 0)
                ->where('han_su_dung', '>=', $now)
                ->orderBy('han_su_dung', 'asc')
                ->orderBy('ngay_tao_phieu', 'asc')
                ->get();

            // Pha chế (loai_san_pham = 0)
            $transactionsBrewed = DB::table('chi_tiet_hoa_dons as cthd')
                ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                ->join('san_phams as sp', 'cthd.ma_san_pham', '=', 'sp.ma_san_pham')
                ->join('thanh_phan_san_phams as tp', function ($join) {
                    $join->on('cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                        ->on('cthd.ma_size', '=', 'tp.ma_size');
                })
                ->where('hd.ma_cua_hang', $ma_cua_hang)
                ->where('sp.loai_san_pham', 0)
                ->where('hd.trang_thai', 4) // 
                ->where('tp.ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->select(
                    'hd.ngay_lap_hoa_don as ngay_phat_sinh',
                    'sp.loai_san_pham',
                    DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong')
            );

            // Đóng gói (loai_san_pham = 1)
            $transactionsPacked = DB::table('chi_tiet_hoa_dons as cthd')
                ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                ->join('san_phams as sp', 'cthd.ma_san_pham', '=', 'sp.ma_san_pham')
                ->join('thanh_phan_san_phams as tp', 'cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                ->where('hd.ma_cua_hang', $ma_cua_hang)
                ->where('sp.loai_san_pham', 1)
                ->where('hd.trang_thai', 4) // 
                ->where('tp.ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->select(
                    'hd.ngay_lap_hoa_don as ngay_phat_sinh',
                    'sp.loai_san_pham',
                    DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong')
            );
            $transactionsHD = $transactionsBrewed->unionAll($transactionsPacked)->get();
                    
            $transactionsPX = DB::table('phieu_nhap_xuat_nguyen_lieus')
                ->where('ma_cua_hang', $ma_cua_hang)
                ->where('ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->where('loai_phieu', 1)
                ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                ->get();

            $transactionsHuy = DB::table('phieu_nhap_xuat_nguyen_lieus')
                ->where('ma_cua_hang', $ma_cua_hang)
                ->where('ma_nguyen_lieu', $nl->ma_nguyen_lieu)
                ->where('loai_phieu', 2)
                ->where('dinh_luong', '>', 0)
                ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                ->get();

            $transactions = $transactionsHD
                ->merge($transactionsPX)
                ->merge($transactionsHuy)
                ->sortBy('ngay_phat_sinh')
                ->values();

            // Tính tồn lô theo FIFO
            $availableBatches = collect();

            foreach ($loList as $lo) {
                $left = $lo->dinh_luong;

                foreach ($transactions as $tx) {
                    if (Carbon::parse($tx->ngay_phat_sinh)->lt(Carbon::parse($lo->ngay_tao_phieu))) continue;
                    if ($tx->dinh_luong <= 0) continue;

                    $used = min($left, $tx->dinh_luong);
                    $left -= $used;
                    $tx->dinh_luong -= $used;

                    if ($left <= 0) break;
                }

                if ($left > 0) {
                    $availableBatches->push([
                        'so_lo'       => $lo->so_lo,
                        'con_lai'     => $left,
                        'han_su_dung' => $lo->han_su_dung,
                        'don_vi'      => $nl->don_vi,
                    ]);
                }
            }

            $nl->available_batches = $availableBatches;
        }

        return $nglKho;
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
            $gia = $request->input("gia.$maNL");
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
                    'gia'                => $gia,
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
            $gia = $request->input("gia.$maNL");
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
                    'gia'                => $gia,
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
        // 1) Lấy danh sách NL được tick
        $chonKiemKho = $request->input('chon_kiemkho', []);
        if (empty($chonKiemKho)) {
            toastr()->error('Bạn chưa chọn nguyên liệu nào!');
            return back();
        }

        $staff     = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $staff->ma_cua_hang;
        $maPhieu   = 'PKK-' . now()->format('dmY-His');

        $kho = $this->getNguyenLieuKiemKho($maCuaHang)->keyBy('ma_nguyen_lieu');

        $nguyenLieuList = collect();

        foreach ($chonKiemKho as $maNL) {
            if (!$kho->has($maNL)) continue;
            $nl = $kho[$maNL];

            $tongDinhLuong = $nl->available_batches->sum('con_lai');

            $tongTon = floor($tongDinhLuong / max($nl->so_luong_goc, 1));

            $loHang = $nl->available_batches->map(function ($b) use ($nl) {
                $soLuongGoc = max($nl->so_luong_goc, 1); // tránh chia cho 0

                return (object)[
                    'so_lo'        => $b['so_lo'] ?? '-',
                    'ton_lo'       => floor(($b['con_lai'] ?? 0) / $soLuongGoc),
                    'han_su_dung'  => $b['han_su_dung'] ?? null,
                    'so_luong_goc' => $soLuongGoc, // nếu cần hiển thị thêm
                ];
            });

            $nguyenLieuList->push((object)[
                'ma_nguyen_lieu'  => $nl->ma_nguyen_lieu,
                'ten_nguyen_lieu' => $nl->ten_nguyen_lieu,
                'don_vi'          => $nl->don_vi,
                'don_vi_tinh'     => $nl->don_vi_tinh,
                'so_luong_goc'    => $nl->so_luong_goc,
                'tong_ton'        => $tongTon,
                'lo_hang'         => $loHang,
            ]);
        }

        if ($nguyenLieuList->isEmpty()) {
            toastr()->error('Không có dữ liệu để xuất phiếu!');
            return back();
        }

        $cuaHang = DB::table('cua_hangs')
            ->where('ma_cua_hang', $maCuaHang)
            ->first();

        $pdf = Pdf::loadView('exports.phieu_kiem_kho', [
                    'nguyenLieuList' => $nguyenLieuList,
                    'cuaHang'        => $cuaHang,
                    'nguoiLap'       => $staff->ho_ten_nhan_vien,
                    'maPhieu'        => $maPhieu,
                ])
                ->setPaper('a4', 'landscape');

        return $pdf->stream("{$maPhieu}.pdf");
    }
}
