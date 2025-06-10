<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\CuaHang;
use App\Models\ChucVu;
use App\Models\Lichphancong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffLichlamviecController extends Controller
{

    public function showForm(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $cuaHangs = CuaHang::where('trang_thai', 1)->get(); // Chỉ lấy cửa hàng đang hoạt động
        $maCuaHang = $nhanVien->ma_cua_hang;

        $nhanViens = [];
        $tuanToi = [];
        $lichPhanCong = collect();
        // Mặc định tiêu đề
        $title = 'Phân công lịch làm việc';
        $subtitle = 'Vui lòng chọn cửa hàng.';
        if ($maCuaHang) {
            $nhanViens = NhanVien::with('chucVu')
                ->where('ma_cua_hang', $maCuaHang)
                ->get();
            $title = 'Phân công lịch làm việc tuần sau';
            $subtitle = 'Bảng phân công lịch làm việc tuần sau';
            $startDate = Carbon::now()->next(Carbon::MONDAY);
            for ($i = 0; $i < 7; $i++) {
                $tuanToi[] = $startDate->copy()->addDays($i);
            }
            $maNhanViens = $nhanViens->pluck('ma_nhan_vien')->toArray();
            $dates = array_map(fn($d) => $d->format('Y-m-d'), $tuanToi);
            $lichPhanCong = Lichphancong::whereIn('ma_nhan_vien', $maNhanViens)
                ->whereIn('ngay_lam', $dates)
                ->get()
                ->groupBy(['ma_nhan_vien', 'ngay_lam']);
        }
        return view('staffs.nhanviens.lichlamviec', compact(
            'cuaHangs',
            'nhanViens',
            'tuanToi',
            'maCuaHang',
            'lichPhanCong',
            'title',
            'subtitle'
        ));
    }
    public function assignWork(Request $request)
    {
        $work = $request->input('work', []);
        // Kiểm tra mỗi nhân viên chỉ có tối đa 5 ngày làm việc (không tính ngày nghỉ)
        foreach ($work as $maNhanVien => $ngayLamViec) {
        $soNgayLam = 0;
        foreach ($ngayLamViec as $caLam) {
            // Ca làm hợp lệ là ca khác rỗng, khác null và khác '3' (nghỉ)
            if ($caLam !== null && $caLam !== '' && $caLam != '3') {
                $soNgayLam++;
            }
        }
        // Nếu số ngày làm < 5, nghĩa là nghỉ quá 2 ngày
        if ($soNgayLam < 5) {
            // Lấy họ tên nhân viên từ DB để hiển thị thông báo rõ ràng
            $nhanVien = NhanVien::where($maNhanVien);
            $ho_ten_nhan_vien = $nhanVien ? $nhanVien->ho_ten : 'Không rõ';
            
            return redirect()->back()->withErrors("Nhân viên $ho_ten_nhan_vien phải làm tối thiểu 5 ngày/tuần (tối đa được nghỉ 2 ngày).");
        }
    }
        // Lưu hoặc cập nhật lịch làm việc
        foreach ($work as $maNhanVien => $ngayLamViec) {
            foreach ($ngayLamViec as $ngay => $caLam) {
                Lichphancong::updateOrCreate(
                    ['ma_nhan_vien' => $maNhanVien, 'ngay_lam' => $ngay],
                    ['ca_lam' => $caLam]
                );
            }
        }
        toastr()->success('Phân công lịch làm việc thành công!');
        return redirect()->route('staffs.nhanviens.lich.tuan');
    }
    // show lịch làm việc theo tuần các nhân viên đều xem đc
    public function showLichTheoTuan(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $nhanVien->ma_cua_hang;

        $weekOffset = (int)$request->input('week', 0);
        $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset);

        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push($startDate->copy()->addDays($i));
        }

        $nhanViens = [];
        $lichPhanCong = collect();

        if ($maCuaHang) {
            $nhanViens = NhanVien::with('chucVu')
                ->where('ma_cua_hang', $maCuaHang)
                ->get();

            $maNhanViens = $nhanViens->pluck('ma_nhan_vien')->toArray();
            $dateStrings = $dates->map(fn($d) => $d->format('Y-m-d'))->toArray();

            $lichPhanCong = Lichphancong::whereIn('ma_nhan_vien', $maNhanViens)
                ->whereIn('ngay_lam', $dateStrings)
                ->get()
                ->groupBy(['ma_nhan_vien', 'ngay_lam']);
        }

        return view('staffs.nhanviens.showlichlamviec', compact(
            'maCuaHang', 'nhanViens', 'dates', 'lichPhanCong', 'weekOffset'
        ));
    }

}
