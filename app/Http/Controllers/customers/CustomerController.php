<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(){
        $user = Auth::user(); // đang đăng nhập

        $taiKhoan = TaiKhoan::with('khachHang')
            ->where('ma_tai_khoan', $user->ma_tai_khoan)
            ->first();


        $viewData = [
            'title' => 'Xin chào ' . ($taiKhoan->khachHang->ten_khach_hang ?? 'bạn') . ' | CMDT Coffee & Tea',
            'taiKhoan' => $taiKhoan
        ];// hoặc $user->ma_tai_khoan nếu có cột đó

        return view('clients.customers.index', $viewData);
    }
}
