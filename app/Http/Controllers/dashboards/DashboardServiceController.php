<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use App\Models\CuaHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\PhieuNhapXuatNguyenLieu;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardServiceController extends Controller
{
    public function getCuaHangs()
    {
        return CuaHang::where('trang_thai',1)->get(); // lấy danh sách cửa hàng cho dropdown
    }
    public function countDonHangTheoTrangThai($ma_cua_hang = null, $trang_thai)
    {
        $query = HoaDon::where('trang_thai', $trang_thai);

        if ($ma_cua_hang) {
            $query->where('ma_cua_hang', $ma_cua_hang);
        }

        return $query->count(); // trả về số lượng đơn theo trạng thái
    }
    public function countDonHangTheoNgay($ma_cua_hang = null, $dateStart = null, $dateEnd = null, $dateTime = null) {
        $query = HoaDon::query();

        if ($ma_cua_hang) {
            $query->where('ma_cua_hang', $ma_cua_hang);
        }

        if ($dateStart && $dateEnd) {
            // Đếm đơn trong khoảng thời gian
            $query->whereBetween('ngay_lap_hoa_don', [$dateStart, $dateEnd]);
        } else if ($dateTime) {
            // Đếm đơn trong ngày cụ thể
            $query->whereDate('ngay_lap_hoa_don', $dateTime);
        } else if (is_null($dateStart) && is_null($dateEnd) && is_null($dateTime)) {
            // Nếu cả 3 đều null thì đếm toàn bộ đơn
            // Không thêm điều kiện ngày nào
        } else {
            // Nếu không đủ điều kiện trên thì lấy ngày hiện tại
            $query->whereDate('ngay_lap_hoa_don', now());
        }

        return $query->count();
    }
    public function tinhTyLeTangTruongDonHang($period = 'day', $ngay = null, $ma_cua_hang = null)
    {
        $ngay = $ngay instanceof Carbon ? $ngay : Carbon::parse($ngay ?? now());

        // Xác định “kỳ hiện tại” và “kỳ trước” dựa vào $period
        switch ($period) {
            case 'week':
                $start      = $ngay->copy()->startOfWeek();
                $end        = $ngay->copy()->endOfWeek();
                $prevStart  = $start->copy()->subWeek();
                $prevEnd    = $end->copy()->subWeek();
                break;

            case 'month':
                $start      = $ngay->copy()->startOfMonth();
                $end        = $ngay->copy()->endOfMonth();
                $prevStart  = $start->copy()->subMonth();
                $prevEnd    = $end->copy()->subMonth();
                break;

            case 'quarter':
                $start      = $ngay->copy()->firstOfQuarter();
                $end        = $ngay->copy()->lastOfQuarter();
                $prevStart  = $start->copy()->subQuarter();
                $prevEnd    = $end->copy()->subQuarter();
                break;

            case 'year':
                $start      = $ngay->copy()->startOfYear();
                $end        = $ngay->copy()->endOfYear();
                $prevStart  = $start->copy()->subYear();
                $prevEnd    = $end->copy()->subYear();
                break;

            default: // 'day'
                $start      = $ngay->copy()->startOfDay();
                $end        = $ngay->copy()->endOfDay();
                $prevStart  = $ngay->copy()->subDay()->startOfDay();
                $prevEnd    = $ngay->copy()->subDay()->endOfDay();
                break;
        }

        // Tạo query chung, lọc trạng thái (nếu cần) và mã cửa hàng
        $baseQueryNow  = HoaDon::query();
        $baseQueryPrev = HoaDon::query();

        if ($ma_cua_hang) {
            $baseQueryNow->where('ma_cua_hang', $ma_cua_hang);
            $baseQueryPrev->where('ma_cua_hang', $ma_cua_hang);
        }

        // Đếm số hóa đơn “kỳ hiện tại”
        $countNow = (clone $baseQueryNow)
            ->whereBetween('ngay_lap_hoa_don', [$start, $end])
            ->count();

        // Đếm số hóa đơn “kỳ trước”
        $countPrev = (clone $baseQueryPrev)
            ->whereBetween('ngay_lap_hoa_don', [$prevStart, $prevEnd])
            ->count();

        // Tính tỷ lệ tăng/giảm
        if ($countPrev == 0) {
            $tyLe  = $countNow > 0 ? 100 : 0;
            $status = $countNow > 0 ? 'increase' : 'no_change';
        } else {
            $tyLe   = round((($countNow - $countPrev) / $countPrev) * 100, 2);
            $status = $tyLe > 0 ? 'increase' : ($tyLe < 0 ? 'decrease' : 'no_change');
        }

        return [
            'ty_le'             => $tyLe,
            'status'            => $status,
            'so_luong_hien_tai' => $countNow,
            'so_luong_ky_truoc' => $countPrev,
            'label'             => match ($period) {
                'day'     => 'Hôm nay vs Hôm qua',
                'week'    => 'Tuần này vs Tuần trước',
                'month'   => 'Tháng này vs Tháng trước',
                'quarter' => 'Quý này vs Quý trước',
                'year'    => 'Năm nay vs Năm trước',
                default   => 'So sánh thời gian'
            }
        ];
    }
    public function countKhachHang(){
        $query = TaiKhoan::where('loai_tai_khoan',3)->where('trang_thai',1)->count();
        
        return $query;
    }
    public function countNhanVien($ma_cua_hang = null){
        $query = NhanVien::where('trang_thai',0 );
        if ($ma_cua_hang) {
            $query->where('ma_cua_hang', $ma_cua_hang);
        }
        return $query->count();
    }
    public function sumDoanhThuTheoThoiGian($ma_cua_hang = null, $loaiThoiGian = 'day')
    {
        $query = HoaDon::query()->where('trang_thai', 4);

        if ($ma_cua_hang) {
            $query->where('ma_cua_hang', $ma_cua_hang);
        }

        $now = now();

        switch ($loaiThoiGian) {
            case 'day':
                $query->whereDate('ngay_lap_hoa_don', $now->toDateString());
                break;

            case 'week':
                $query->whereBetween('ngay_lap_hoa_don', [$now->startOfWeek(), $now->endOfWeek()]);
                break;

            case 'month':
                $query->whereBetween('ngay_lap_hoa_don', [$now->startOfMonth(), $now->endOfMonth()]);
                break;

            case 'quarter':
                $query->whereBetween('ngay_lap_hoa_don', [$now->startOfQuarter(), $now->endOfQuarter()]);
                break;

            case 'year':
                $query->whereBetween('ngay_lap_hoa_don', [$now->startOfYear(), $now->endOfYear()]);
                break;

            case 'all':
                // Không lọc ngày -> lấy tất cả
                break;

            default:
                return 0;
        }
        return $query->sum('tong_tien');
    }

    public function doanhThuTheoKhoang($ma_cua_hang = null, $start = null, $end = null, $mode = 'month')
    {
        $query = HoaDon::query()->where('trang_thai', 4);

        if ($ma_cua_hang) $query->where('ma_cua_hang', $ma_cua_hang);
        if ($start) $query->whereDate('ngay_lap_hoa_don', '>=', $start);
        if ($end) $query->whereDate('ngay_lap_hoa_don', '<=', $end);

        switch ($mode) {
            case 'quarter':
                $rows = $query->selectRaw('CONCAT(YEAR(ngay_lap_hoa_don), "-Q", QUARTER(ngay_lap_hoa_don)) as label, SUM(tong_tien) as total')
                    ->groupBy('label')->orderBy('label')->get();
                break;
            case 'year':
                $rows = $query->selectRaw('YEAR(ngay_lap_hoa_don) as label, SUM(tong_tien) as total')
                    ->groupBy('label')->orderBy('label')->get();
                break;
            default:
                $rows = $query->selectRaw('DATE_FORMAT(ngay_lap_hoa_don, "%Y-%m") as label, SUM(tong_tien) as total')
                    ->groupBy('label')->orderBy('label')->get();
        }

        return [
            'labels' => $rows->pluck('label'),
            'values' => $rows->pluck('total')->map(fn($v) => (int)$v),
        ];
    }

    public function getDoanhThu(Request $request)
    {
        $mode = $request->query('mode', 'month');
        $ma_cua_hang = $request->query('ma_cua_hang');
        $start = $request->query('start');
        $end = $request->query('end');

        $data = $this->doanhThuTheoKhoang($ma_cua_hang, $start, $end, $mode);

        return response()->json($data);
    }


    public function tinhTyLeTangTruongDoanhThu($period = 'day', $ngay = null, $ma_cua_hang = null)
    {
        $ngay = $ngay instanceof Carbon ? $ngay : Carbon::parse($ngay ?? now());

        switch ($period) {
            case 'week':
                $start      = $ngay->copy()->startOfWeek();
                $end        = $ngay->copy()->endOfWeek();
                $prevStart  = $start->copy()->subWeek();
                $prevEnd    = $end->copy()->subWeek();
                break;
            case 'month':
                $start      = $ngay->copy()->startOfMonth();
                $end        = $ngay->copy()->endOfMonth();
                $prevStart  = $start->copy()->subMonth();
                $prevEnd    = $end->copy()->subMonth();
                break;
            case 'quarter':
                $start      = $ngay->copy()->firstOfQuarter();
                $end        = $ngay->copy()->lastOfQuarter();
                $prevStart  = $start->copy()->subQuarter();
                $prevEnd    = $end->copy()->subQuarter();
                break;
            case 'year':
                $start      = $ngay->copy()->startOfYear();
                $end        = $ngay->copy()->endOfYear();
                $prevStart  = $start->copy()->subYear();
                $prevEnd    = $end->copy()->subYear();
                break;
            default:
                $start      = $ngay->copy()->startOfDay();
                $end        = $ngay->copy()->endOfDay();
                $prevStart  = $ngay->copy()->subDay()->startOfDay();
                $prevEnd    = $ngay->copy()->subDay()->endOfDay();
                break;
        }

        $baseQueryNow  = HoaDon::query()->where('trang_thai', 4);
        $baseQueryPrev = HoaDon::query()->where('trang_thai', 4);

        if ($ma_cua_hang) {
            $baseQueryNow->where('ma_cua_hang', $ma_cua_hang);
            $baseQueryPrev->where('ma_cua_hang', $ma_cua_hang);
        }

        $revenueNow = (clone $baseQueryNow)
            ->whereBetween('ngay_lap_hoa_don', [$start, $end])
            ->sum('tong_tien');

        $revenuePrev = (clone $baseQueryPrev)
            ->whereBetween('ngay_lap_hoa_don', [$prevStart, $prevEnd])
            ->sum('tong_tien');

        if ($revenuePrev == 0) {
            $tyLe  = $revenueNow > 0 ? 100 : 0;
            $status = $revenueNow > 0 ? 'increase' : 'no_change';
        } else {
            $tyLe = round((($revenueNow - $revenuePrev) / $revenuePrev) * 100, 2);
            $status = $tyLe > 0 ? 'increase' : ($tyLe < 0 ? 'decrease' : 'no_change');
        }

        return [
            'ty_le'              => $tyLe,
            'status'             => $status,
            'doanh_thu_hien_tai' => $revenueNow,
            'doanh_thu_ky_truoc' => $revenuePrev,
            'label'              => match ($period) {
                'day'     => 'Hôm nay vs Hôm qua',
                'week'    => 'Tuần này vs Tuần trước',
                'month'   => 'Tháng này vs Tháng trước',
                'quarter' => 'Quý này vs Quý trước',
                'year'    => 'Năm nay vs Năm trước',
                default   => 'So sánh thời gian'
            }
        ];
    }

    public function topSanPhamBanChay($ma_cua_hang = null, $thoi_gian = 'thang', $limit = 5)
    {
        // Xác định khoảng thời gian
        $now = now();
        switch ($thoi_gian) {
            case 'tuan':
                $from = $now->copy()->startOfWeek();
                $to = $now->copy()->endOfWeek();
                break;
            case 'quy':
                $from = $now->copy()->startOfQuarter();
                $to = $now->copy()->endOfQuarter();
                break;
            case 'nam':
                $from = $now->copy()->startOfYear();
                $to = $now->copy()->endOfYear();
                break;
            default: // 'thang'
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }

        return DB::table('chi_tiet_hoa_dons')
        ->join('hoa_dons', 'chi_tiet_hoa_dons.ma_hoa_don', '=', 'hoa_dons.ma_hoa_don')
        ->join('san_phams', 'chi_tiet_hoa_dons.ma_san_pham', '=', 'san_phams.ma_san_pham')
        ->when($ma_cua_hang, function ($query) use ($ma_cua_hang) {
            return $query->where('hoa_dons.ma_cua_hang', $ma_cua_hang);
        })
        ->where('hoa_dons.trang_thai', 4) // Chỉ tính đơn đã nhận
        ->whereBetween('hoa_dons.ngay_lap_hoa_don', [$from, $to])
        ->select(
            'san_phams.ma_san_pham',
            'san_phams.ten_san_pham',
            DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_ban')
        )
        ->groupBy('san_phams.ma_san_pham', 'san_phams.ten_san_pham')
        ->orderByDesc('tong_ban')
        ->limit($limit)
        ->get();
    }

    public function getTopSanPham(Request $request)
    {
        $mode = $request->query('mode', 'month');
        $thoi_gian = match ($mode) {
            'quarter' => 'quy',
            'year'    => 'nam',
            default   => 'thang',
        };

        $ma_cua_hang = $request->query('ma_cua_hang');
        $limit = $request->query('limit', 7);

        $data = $this->topSanPhamBanChay($ma_cua_hang, $thoi_gian, $limit);

        return response()->json($data);
    }


    public function tinhLoiNhuan($ma_cua_hang = null,$start = null,$end   = null,$mode  = 'month'){
        // ==== 1. Query doanh thu (hóa đơn đã giao & đã thanh toán) ====
        $hd = HoaDon::query()
            ->where('trang_thai', 4)
            ->where('trang_thai_thanh_toan', 1);
        if ($ma_cua_hang) $hd->where('ma_cua_hang', $ma_cua_hang);
        if ($start)       $hd->whereDate('ngay_lap_hoa_don', '>=', $start);
        if ($end)         $hd->whereDate('ngay_lap_hoa_don', '<=', $end);

        // ==== 2. Query tiền nhập (phiếu nhập nguyên liệu) ====
        $pn = PhieuNhapXuatNguyenLieu::query()
            ->where('loai_phieu', 0); // 0 = nhập
        if ($ma_cua_hang) $pn->where('ma_cua_hang', $ma_cua_hang);
        if ($start)       $pn->whereDate('ngay_tao_phieu', '>=', $start);
        if ($end)         $pn->whereDate('ngay_tao_phieu', '<=', $end);

        // ==== 3. Group & sum theo $mode ====
        switch ($mode) {
            case 'quarter':
                $grpHd = $hd->selectRaw('CONCAT(YEAR(ngay_lap_hoa_don), "-Q", QUARTER(ngay_lap_hoa_don)) g, SUM(tong_tien) s')
                            ->groupBy('g')->pluck('s','g');
                $grpPn = $pn->selectRaw('CONCAT(YEAR(ngay_tao_phieu), "-Q", QUARTER(ngay_tao_phieu)) g, SUM(tong_tien) s')
                            ->groupBy('g')->pluck('s','g');
                break;
            case 'year':
                $grpHd = $hd->selectRaw('YEAR(ngay_lap_hoa_don) g, SUM(tong_tien) s')
                            ->groupBy('g')->pluck('s','g');
                $grpPn = $pn->selectRaw('YEAR(ngay_tao_phieu) g, SUM(tong_tien) s')
                            ->groupBy('g')->pluck('s','g');
                break;
            default: // month
                $grpHd = $hd->selectRaw('DATE_FORMAT(ngay_lap_hoa_don,"%Y-%m") g, SUM(tong_tien) s')
                            ->groupBy('g')->pluck('s','g');
                $grpPn = $pn->selectRaw('DATE_FORMAT(ngay_tao_phieu,"%Y-%m") g, SUM(tong_tien) s')
                            ->groupBy('g')->pluck('s','g');
        }

        // ==== 4. Ghép & tính lợi nhuận ====
        $labels = collect($grpHd)->keys()->merge($grpPn->keys())->unique()->sort()->values();
        $thu    = $labels->map(fn($l) => (float) ($grpHd[$l] ?? 0));
        $chi    = $labels->map(fn($l) => (float) ($grpPn[$l] ?? 0));
        $loi    = $labels->map(fn($i,$k) => $thu[$k] - $chi[$k]);

        return [
            'labels' => $labels->values(),
            'tongChi'  => $chi,
            'tongThu'  => $thu,
            'loiNhuan' => $loi,
        ];
    }
    public function getLoiNhuan(Request $request)
    {
        $mode = $request->query('mode', 'month');
        $ma_cua_hang = $request->query('ma_cua_hang');
        $start = $request->query('start');
        $end = $request->query('end');

        $data = $this->tinhLoiNhuan($ma_cua_hang, $start, $end, $mode);

        return response()->json($data);
    }
}
