<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\CuaHang;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{

    public function getStore(){
        return CuaHang::where('trang_thai',1)->get();
    }

    public function index(Request $request)
    {
        $cuaHang = $this->getStore();

        $orders = collect(); // mặc định rỗng

        // Nếu có chọn mã cửa hàng
        if ($request->filled('ma_cua_hang')) {
            $query = HoaDon::with(['khachHang', 'chiTietHoaDon'])
                ->where('ma_cua_hang', $request->ma_cua_hang);

            // Nếu có search mã đơn hàng
            if ($request->filled('search')) {
                $query->where('ma_hoa_don', 'like', '%'.$request->search.'%');
            }

            $orders = $query->get();
        }
        $ViewData = [
            'title' => 'Đơn hàng | CDMT Coffee & Tea',
            'subtitle' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'cuaHang' => $cuaHang
        ];

        return view('admins.orders.index', $ViewData);
    }

    public function filter(Request $request)
    {
        $query = HoaDon::with(['khachHang', 'chiTietHoaDon']);

        if ($request->filled('ma_cua_hang')) {
            $query->where('ma_cua_hang', $request->ma_cua_hang);
        } else {
            // Nếu không có cửa hàng thì trả về rỗng luôn
            return response()->json('');
        }

        if ($request->filled('pt_thanh_toan')) {
            $query->where('phuong_thuc_thanh_toan', $request->pt_thanh_toan);
        }

        if ($request->filled('tt_thanh_toan')) {
            $query->where('trang_thai_thanh_toan', $request->tt_thanh_toan);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ma_hoa_don', 'like', "%$search%")
                ->orWhere('ten_khach_hang', 'like', "%$search%");
            });
        }

        $orders = $query->get();

        return view('admins.orders._order_tbody', compact('orders'));
    }

    public function detail($id)
    {
        $order = HoaDon::with(['khachHang', 'chiTietHoaDon'])->where('ma_hoa_don',$id)->first();
        return view('admins.orders._order_detail', compact('order'));
    }
}
