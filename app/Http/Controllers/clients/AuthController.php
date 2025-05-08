<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Str;

class AuthController extends Controller
{
    public function showRegisterForm(){
        $viewData = [
            'title'=> 'Đăng ký | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.register', $viewData);
    }

    public function register( Request $request){
       
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|max:255|unique:tai_khoans',
            'password' => 'required|string|max:255|min:6',     
        ],[
            'name.required' => 'Tên là bắt buộc.',  
            'name.min'=> 'Tên phải ít nhất 3 ký tự',
            'email.required' => 'Email là bắt buộc.',  
            'email.unique' => 'Email này đã được sử dụng.', 
            'password.required' => 'Mật khẩu là bắt buộc.', 
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự.',  
        ]);

        //check email
        $emailExit = TaiKhoan::where('email',$request->email)->first();
        if($emailExit){
            if($emailExit->status()){
                toastr()->error('Email đã được đăng ký và đang chờ kích hoạt');
                return redirect()->route('register');
            }
            return redirect()->route('register');
        }

        $token = Str::random(64);

        $taiKhoan = TaiKhoan::create([
            'email' => $request->email,
            'mat_khau' => $request->password,
            'loai_tai_khoan' => 3, // tài khoản khách hàng
            'access_token' => $token
        ]);

        $maKH = KhachHang::generateMaKhachHang();

        $khachHang = KhachHang::create([
            'ma_khach_hang' => $maKH,
            'ma_tai_khoan' => $taiKhoan->ma_tai_khoan,
            'ho_ten_khach_hang' => $request->name,
        ]);

        toastr()->success('Đăng ký tài khoản thành công. Vui lòng kiểm tra email của bạn để kích hoạt tài khoản.');
        return redirect()->route('login');
    }
    public function showLoginForm(){
        $viewData = [
            'title'=> 'Đăng nhập | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.login', $viewData);
    }
}
