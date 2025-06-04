<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\KhachHang;
use Auth;
use Illuminate\Http\Request;
use function Flasher\Toastr\Prime\toastr;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $khachHang = optional(Auth::user())->khachHang;

        if (!$khachHang) {
            return redirect()->route('login');
        }

        $customerId = $khachHang->ma_khach_hang;

        $orders = HoaDon::with(['chiTietHoaDon.sanPham', 'transaction','giaoHang'])
            ->where('ma_khach_hang', $customerId)
            ->orderByDesc('created_at')
            ->get();

        return view('clients.customers.orders.index', [
            'title' => 'Đơn hàng của bạn',
            'orders' => $orders,
        ]);
    }

    public function orderCancel($orderId){
        
    }
    public function showFormTraCuuDonHang()
    {
        return view('clients.pages.tra_cuu_don_hang', ['title' => 'Tra cứu đơn hàng | CMDT Coffee & Tea']);
    }

    public function traCuuDonHang(Request $request)
    {
        $maDonHang = $request->input('ma_don_hang');

        $order = HoaDon::with(['khachHang', 'chiTietHoaDon', 'giaoHang', 'lichSuHuyDonHang'])
            ->where('ma_hoa_don', $maDonHang)
            ->first();

        if (!$order) {
            toastr()->error('Mã đơn hàng không tồn tại');
            return redirect()->back();
        }

        return view('clients.pages.tra_cuu_don_hang', compact('order'))->with('title', 'Kết quả tra cứu đơn hàng');
    }
}
