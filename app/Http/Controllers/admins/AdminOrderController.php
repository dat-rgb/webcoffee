<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function getCustomer(){

    }
    public function getOrderItem($orderCode)
    {
        return ChiTietHoaDon::with('sanPham')
            ->where('ma_hoa_don', $orderCode)
            ->get();
    }

    public function getOrderByStatus($status, $search = null, $maCuaHang = null)
    {
        $query = HoaDon::with(['khachHang', 'chiTietHoaDon']);

        if ($status !== null) {
            $query->where('trang_thai', $status);
        }

        if ($maCuaHang !== null) {
            $query->where('ma_cua_hang', $maCuaHang);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ma_hoa_don', 'like', '%' . $search . '%')
                ->orWhere('ten_khach_hang', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('ngay_lap_hoa_don', 'desc')->get();
    }

    public function index(Request $request)
    {
        $status = $request->input('status', null);
        $search = $request->input('search', null);
        $maCuaHang = $request->input('ma_cua_hang', null);

        $orders = $this->getOrderByStatus($status, $search, $maCuaHang);

        $ViewData = [
            'title' => 'Đơn hàng | CDMT Coffee & Tea',
            'subtitle' => 'Quản lý đơn hàng',
            'orders' => $orders,
        ];

        return view('admins.orders.index', $ViewData);
    }
}
