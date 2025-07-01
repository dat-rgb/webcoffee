<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\TaiKhoan;
use App\Mail\ResetPassword;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        $viewData = [
            'title'=> 'Quên mật khẩu | CMDT Coffee & Tea'   
        ];

        return view('clients.auth.forgot_password', $viewData);
    }

    public function sendResetPasswordLink(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:tai_khoans,email'
            ],
            [
                'email.email' => 'Email không hợp lệ.',
                'email.required' => 'Email là bắt buộc.',
                'email.exists' => 'Email không tồn tại trong hệ thống.'
            ]
        );

        // Lấy user theo email
        $user = TaiKhoan::where('email', $request->email)->first();

        // Tạo token thuần
        $plainToken = Str::random(64);

        // Lưu vào bảng password_reset_tokens (Laravel 11)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($plainToken),
                'created_at' => now(),
            ]
        );

        // Gửi email custom
        Mail::to($user->email)->send(new ResetPassword($user->ten_tai_khoan ?? $user->email, $plainToken));

        toastr()->success('Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
        return back();
    }
}
