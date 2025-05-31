<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffHomeController extends Controller
{
    public function index()
    {
        $taiKhoanId = Auth::id();
        
        $nhanVien = NhanVien::with(['taiKhoan', 'chucVu', 'cuaHang'])
            ->where('ma_tai_khoan', $taiKhoanId)
            ->first();
       
        $viewData = [
            'title' => 'Trang chủ | CDMT Coffee & Tea',
            'subtitle' => 'Nhân viên',
            'info' => $nhanVien,
        ];
        return view('staffs.pages.index', $viewData);
    }
}
