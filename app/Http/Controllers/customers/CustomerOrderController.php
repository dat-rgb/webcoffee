<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\KhachHang;
use Auth;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $khachHang = optional(Auth::user())->khachHang;

        if (!$khachHang) {
            return redirect()->route('login');
        }

        $customerId = $khachHang->ma_khach_hang;

        // Lấy đơn hàng với chi tiết hóa đơn + sản phẩm + transaction
        $orders = HoaDon::with(['chiTietHoaDon.sanPham', 'transaction'])
            ->where('ma_khach_hang', $customerId)
            ->orderByDesc('created_at')
            ->get();

        return view('clients.customers.orders.index', [
            'title' => 'Đơn hàng của bạn',
            'orders' => $orders,
        ]);
    }
}
