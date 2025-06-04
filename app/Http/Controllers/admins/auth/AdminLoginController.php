<?php

namespace App\Http\Controllers\admins\auth;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            return redirect()->route('admin');
        }
        else if($admin->loai_tai_khoan == 2){
            Auth::guard('staff')->login($admin);

            $request->session()->regenerate();
            toastr()->success('Đăng nhập thành công.');
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
}
