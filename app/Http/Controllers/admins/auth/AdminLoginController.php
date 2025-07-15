<?php

namespace App\Http\Controllers\admins\auth;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\CaLamViec;
use App\Models\HoaDon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminLoginController extends Controller
{
    public function showLoginForm(){
        return view('admins.auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự.',
        ]);

        $admin = TaiKhoan::where('email', $request->email)->first();

        if (!$admin) {
            toastr()->error('Email không tồn tại. Hãy liên hệ đến nhà quản trị.');
            return redirect()->back();
        }
        if($admin->loai_tai_khoan == 3){
            toastr()->error('Bạn không có quyền truy cập.');
            return redirect()->back();
        }

        if (!Hash::check($request->password, $admin->mat_khau)) {
            toastr()->error('Mật khẩu không chính xác.');
            return redirect()->back();
        }

        if ($admin->trang_thai != 1) {
            toastr()->warning('Tài khoản chưa được kích hoạt.');
            return redirect()->back();
        }
        if($admin->loai_tai_khoan == 1){
            Auth::guard('admin')->login($admin);

            $request->session()->regenerate();
            toastr()->success('Đăng nhập thành công.');
            return redirect()->route('admin.dashboard');
        }
        else if($admin->loai_tai_khoan == 2){
            Auth::guard('staff')->login($admin);

            $nhanVien = Auth::guard('staff')->user()->nhanvien;

            if (in_array($nhanVien->ma_chuc_vu, [1, 3])) {
                $caDangMo = CaLamViec::where('ma_nhan_vien', $nhanVien->ma_nhan_vien)
                    ->whereNull('thoi_gian_ra')
                    ->first();

                if ($caDangMo) {
                    toastr()->warning('Bạn đã có ca làm việc đang mở!');
                } else {
                    CaLamViec::create([
                        'ma_nhan_vien' => $nhanVien->ma_nhan_vien,
                        'thoi_gian_vao' => now(),
                    ]);
                    toastr()->success('Đăng nhập thành công và bắt đầu ca làm việc!');
                }
            } else {
                toastr()->success('Đăng nhập thành công!');
            }
            $request->session()->regenerate();
            
            return redirect()->route('staff');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toastr()->success('Đăng xuất thành công.');
        return redirect()->route('admin.login.show');
    }

    public function ketCaLam(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        $ca = CaLamViec::where('ma_nhan_vien', $nhanVien->ma_nhan_vien)
            ->whereNull('thoi_gian_ra')
            ->latest()
            ->first();

        if (!$ca) return;

        $hoaDonQuery = HoaDon::where('ma_nhan_vien', $nhanVien->ma_nhan_vien)
            ->where('created_at', '>=', $ca->thoi_gian_vao)
            ->where('trang_thai', 4);

        $tongDon = $hoaDonQuery->count();
        $tongTienCod = (clone $hoaDonQuery)->where('phuong_thuc_thanh_toan', 'COD')->sum('tong_tien');
        $tongTienOnline = (clone $hoaDonQuery)->where('phuong_thuc_thanh_toan', '!=', 'COD')->sum('tong_tien');
        $tongTien = $tongTienCod + $tongTienOnline;

        $tienDauCa = $request->input('tien_dau_ca', 0);
        $tienThucNhan = $request->input('tien_thuc_nhan', 0);
        $tienChenhLech = $tienThucNhan - ($tienDauCa + $tongTienCod);

        $ca->update([
            'thoi_gian_ra' => now(),
            'tong_don_xac_nhan' => $tongDon,
            'tong_tien_cod' => $tongTienCod,
            'tong_tien_online' => $tongTienOnline,
            'tong_tien' => $tongTien,
            'tien_dau_ca' => $tienDauCa,
            'tien_thuc_nhan' => $tienThucNhan,
            'tien_chenh_lech' => $tienChenhLech,
        ]);

        // Optional xuất PDF ngay nếu cần
        return Pdf::loadView('exports.phieu_ket_ca', [
            'ca' => $ca,
            'nhanVien' => $nhanVien,
        ])->setPaper([0, 0, 226.8, 500]) // cho K80

        ->download('phieu_ket_ca_' . $nhanVien->ma_nhan_vien . '.pdf');
    }
}
