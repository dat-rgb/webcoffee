<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardServiceController();
    }

    public function index(Request $request)
    {
        $ma_cua_hang = $request->query('ma_cua_hang', null);
        if($ma_cua_hang){
            $subtitle = 'Thống kê cửa hàng '. $ma_cua_hang;
        }
        else{
            $subtitle = 'Thống kê'. $ma_cua_hang;
        }

        $hoaDonDaNhan = $this->dashboardService->countDonHangTheoTrangThai($ma_cua_hang,4);
        $hoaDonDaHuy = $this->dashboardService->countDonHangTheoTrangThai($ma_cua_hang,5);
        $hoaDonNgay = $this->dashboardService->countDonHangTheoNgay($ma_cua_hang,null,null, now()->toDateString());
        $tangTruongNgay = $this->dashboardService->tinhTyLeTangTruongDonHang('day', now(), $ma_cua_hang);
        $tongHoaDon = $this->dashboardService->countDonHangTheoNgay($ma_cua_hang,null,null, null);
        $doanhThuNgay = $this->dashboardService->sumDoanhThuTheoThoiGian($ma_cua_hang, 'day');
        $tongDoanhThu = $this->dashboardService->sumDoanhThuTheoThoiGian($ma_cua_hang, 'all');           
        $countKhachHang = $this->dashboardService->countKhachHang();
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
            'title' => 'Admin Dashboard | CDMT Coffee & Tea',
            'subtitle' => $subtitle,
            'hoaDonDaNhan' => $hoaDonDaNhan,
            'hoaDonDaHuy' => $hoaDonDaHuy,
            'hoaDonNgay' => $hoaDonNgay,
            'tangTruongNgay' => $tangTruongNgay,
            'tongHoaDon' => $tongHoaDon,
            'doanhThuNgay' => $doanhThuNgay,
            'tongDoanhThu' => $tongDoanhThu,
            'countKhachHang' => $countKhachHang,
            'countNhanVien' => $countNhanVien,
            'topSPBanChay' =>$topSPBanChay,
            'doanhTungThangTrongNam' => $doanhTungThangTrongNam,
            'dataChart' => $dataChart,
            'labelsChart' => $labelsChart,
            'cuaHangs' => $this->dashboardService->getCuaHangs(), 
            'selectedCuaHang' => $ma_cua_hang,
        ];

        return view('admins.dashboards.index', $viewData);
    }
}
// Ví dụ tính tăng trưởng đơn hàng “hôm nay so với hôm qua”
// $resultDay = $this->dashboardService->tinhTyLeTangTruongDonHang('day', now(), $ma_cua_hang);

// Tăng trưởng “tuần này so với tuần trước”
// $resultWeek = $this->dashboardService->tinhTyLeTangTruongDonHang('week', now(), $ma_cua_hang);

// Tăng trưởng “tháng này so với tháng trước”
// $resultMonth = $this->dashboardService->tinhTyLeTangTruongDonHang('month', now(), $ma_cua_hang);

// Tăng trưởng “quý này so với quý trước”
// $resultQuarter = $this->dashboardService->tinhTyLeTangTruongDonHang('quarter', now(), $ma_cua_hang);

// Tăng trưởng “năm nay so với năm trước”
// $resultYear = $this->dashboardService->tinhTyLeTangTruongDonHang('year', now(), $ma_cua_hang);
