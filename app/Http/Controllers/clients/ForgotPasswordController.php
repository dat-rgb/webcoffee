<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword(){
        $viewData = [
            'title'=> 'Quên mật khẩu | CMDT Coffee & Tea'   
        ];

        return view('clients.auth.forgot_password', $viewData);
    }

    public function sendResetPasswordLink(Request $request){
        
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

        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT){
            toastr()->success('Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
            return back();
        }

        toastr()->error('Không thể gửi email đặt lại mật khẩu.');
        return back()->withErrors(['email'=>__($status)]);
    }
}
