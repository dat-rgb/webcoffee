<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Email;

class ResetPasswordController extends Controller
{
    public function showRetsetForm($token){
        $viewData = [
            'title'=> 'Đặt lại mật khẩu | CMDT Coffee & Tea',
            'token' => $token
        ];

        return view('clients.auth.reset_password', $viewData);
    }

    public function resetPassword(Request $request){
    $request->validate([
        'email' => 'required|string|max:255|exists:tai_khoans,email',
        'password' => 'required|string|max:20|min:6|confirmed',  
        'token' => 'required'   
    ],[
        'email.email' => 'Email không hợp lệ.', 
        'email.required' => 'Email là bắt buộc.',  
        'email.exists' => 'Email này chưa được đăng ký.', 
        'email.max'=> 'Email sai định dạng',
        'password.required' => 'Mật khẩu là bắt buộc.', 
        'password.min' => 'Mật khẩu phải ít nhất 6 ký tự.', 
        'password.max' => 'Mật khẩu phải không vượt quá 20 ký tự.', 
        'password.confirmed' => 'Vui lòng nhập mật khẩu.',
        'token.required' => 'Mã token không hợp lệ hoặc đã hết hạn.',  
    ]);

    $status = Password::reset(
        $request->only('email','password','password_confirmation','token'),
        function($taiKhoan, $password){
            $taiKhoan->forceFill([
                'mat_khau' => Hash::make($password)
            ])->save();
        }
    );

    if($status === Password::PASSWORD_RESET){
        $user = TaiKhoan::where('email', $request->email)->first();

        if ($user && $user->loai_tai_khoan != 3) {
            toastr()->success('Đặt lại mật khẩu thành công.');
            return redirect()->route('admin.login.show');
        }

        toastr()->success('Đặt lại mật khẩu thành công.');
        return redirect()->route('login');
    }

    toastr()->error('Đặt lại mật khẩu không thành công.');
    return back()->withErrors(['email'=> __($status)]); 
}

}
