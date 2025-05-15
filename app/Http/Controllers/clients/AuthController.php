<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Mail\ActivationMail;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


use Str;
use function Flasher\Toastr\Prime\toastr;

class AuthController extends Controller
{
    public function showRegisterForm(){
        $viewData = [
            'title'=> 'Đăng ký | CMDT Coffee & Tea'   
        ];

        return view('clients.auth.register', $viewData);
    }

    public function register( Request $request){
       
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|max:255|unique:tai_khoans',
            'password' => 'required|string|max:255|min:6',     
        ],[
            'name.required' => 'Tên là bắt buộc.',  
            'name.min'=> 'Tên phải ít nhất 2 ký tự',
            'email.required' => 'Email là bắt buộc.',  
            'email.unique' => 'Email này đã được sử dụng.', 
            'password.required' => 'Mật khẩu là bắt buộc.', 
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự.',  
        ]);

        //check email
        $emailExit = TaiKhoan::with('taiKhoan')->where('email',$request->email)->first();
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
            'mat_khau' => Hash::make($request->password),
            'loai_tai_khoan' => 3, // tài khoản khách hàng
            'activation_token' => $token
        ]);

        $maKH = KhachHang::generateMaKhachHang();

        $khachHang = KhachHang::create([
            'ma_khach_hang' => $maKH,
            'ma_tai_khoan' => $taiKhoan->ma_tai_khoan,
            'ho_ten_khach_hang' => $request->name,
        ]);

        Mail::to($taiKhoan->email)->send(new ActivationMail($token, $taiKhoan, $khachHang->ho_ten_khach_hang));


        toastr()->success('Đăng ký tài khoản thành công. Vui lòng kiểm tra email của bạn để kích hoạt tài khoản.');
        return redirect()->route('login');
    }

    public function activate($token){
        $taiKhoan = TaiKhoan::where('activation_token',$token)->first();

        if($taiKhoan){
            $taiKhoan->trang_thai = 1;
            $taiKhoan->activation_token = null;
            $taiKhoan->save();

            toastr()->success('Kích hoạt tài khoản thành công. Hãy đăng nhập tài khoản.');
            return redirect()->route('login');
        }

        toastr()->error('Token không hợp lệ');
        return redirect()->route('login');
    }

    public function showLoginForm(){
        $viewData = [
            'title'=> 'Đăng nhập | CMDT Coffee & Tea'   
        ];

        return view('clients.auth.login', $viewData);
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

        $user = TaiKhoan::where('email', $request->email)->first();

        if (!$user) {
            toastr()->error('Email không tồn tại. Hãy đăng ký tài khoản.');
            return redirect()->back();
        }

        if (!Hash::check($request->password, $user->mat_khau)) {
            toastr()->error('Mật khẩu không chính xác.');
            return redirect()->back();
        }

        if ($user->trang_thai != 1) {
            toastr()->warning('Tài khoản chưa được kích hoạt.');
            return redirect()->back();
        }

        Auth::login($user);
        $request->session()->regenerate();
        toastr()->success('Đăng nhập thành công.');
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        toastr()->success('Đăng xuất thành công.');
        return redirect()->route('login');
    }
}
