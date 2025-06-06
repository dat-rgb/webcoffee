<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staffs\StaffOrderController;
use App\Models\GiaoHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\LichSuHuyDonHang;
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

        $orders = HoaDon::with([
            'chiTietHoaDon.sanPham',
            'chiTietHoaDon.review' => function($query) use ($customerId) {
                $query->where('ma_khach_hang', $customerId);
            },
            'transaction',
            'giaoHang'
        ])
        ->where('ma_khach_hang', $customerId)
        ->orderByDesc('created_at')
        ->get();

        return view('clients.customers.orders.index', [
            'title' => 'Đơn hàng của bạn',
            'orders' => $orders,
        ]);
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
    public function cancelOrderByCustomer(Request $request, $orderId)
    {
        $order = HoaDon::where('ma_hoa_don', $orderId)->first();
        if (!$order) {
            toastr()->error('Đơn hàng không tồn tại');
            return redirect()->back();
        }

        $data = $request->validate([
            'cancel_reason' => 'required|string',
        ]);

        if ($order->trang_thai < 2) {
            $updated = $order->update(['trang_thai' => 5]);

            if (!$updated) {
                toastr()->error('Không thể huỷ đơn hàng');
                return redirect()->back();
            }

            (new StaffOrderController)->restoreIngredientsAndVoucher($order);

            $maKhachHang = optional(auth()->user())->ma_khach_hang;

            $lichSu = new LichSuHuyDonHang();
            $lichSu->ma_hoa_don = $order->ma_hoa_don;
            $lichSu->ly_do_huy = $data['cancel_reason'];
            $lichSu->thoi_gian_huy = now();
            if ($maKhachHang) {
                $lichSu->ma_khach_hang = $maKhachHang;
            }
            $lichSu->save();

            toastr()->success('Đã huỷ đơn hàng thành công');
            return redirect()->back();
        }

        return response()->json(['message' => 'Không thể huỷ đơn đã được xử lý.'], 400);
    }
}
