<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ];

        return view('staffs.dashboards.index', $viewData);
    }
}
