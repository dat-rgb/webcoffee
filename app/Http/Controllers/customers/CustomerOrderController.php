<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staffs\StaffOrderController;
use App\Models\ChiTietHoaDon;
use App\Models\GiaoHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\LichSuHuyDonHang;
use App\Models\SanPham;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        if ($order->trang_thai >= 2) {
            toastr()->error('Không thể hủy đơn hàng đã được xử lý');
            return redirect()->back();
        }

        $order->update(['trang_thai' => 5]);

        $this->restorePackedProductOnly($order);
        $this->restoreVoucherAndBrewedProductOnly($order);

        LichSuHuyDonHang::create([
            'ma_hoa_don' => $order->ma_hoa_don,
            'ly_do_huy' => $data['cancel_reason'],
            'thoi_gian_huy' => now(),
            'nguoi_huy' => 'KH - ' . $order->ten_khach_hang,
        ]);

        toastr()->success('Đã huỷ đơn hàng thành công');
        return redirect()->back();
    }

    public function restorePackedProductOnly($hoaDon)
    {
        $chiTietHoaDons = ChiTietHoaDon::where('ma_hoa_don', $hoaDon->ma_hoa_don)->get();

        foreach ($chiTietHoaDons as $chiTiet) {
            $maSanPham = $chiTiet->ma_san_pham;
            $sanPham = SanPham::where('ma_san_pham', $maSanPham)->first();
            if (!$sanPham || $sanPham->loai_san_pham != 1) continue;

            $tp = ThanhPhanSanPham::where('ma_san_pham', $maSanPham)->first();
            if (!$tp) continue;

            $soLuongHoanTra = $tp->dinh_luong * $chiTiet->so_luong;

            DB::table('cua_hang_nguyen_lieus')
                ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                ->increment('so_luong_ton', $soLuongHoanTra);
        }
    }
    
    public function restoreVoucherAndBrewedProductOnly($hoaDon)
    {
        if ($hoaDon->ma_voucher) {
            $voucher = KhuyenMai::where('ma_voucher', $hoaDon->ma_voucher)->first();
            if ($voucher) {
                $voucher->increment('so_luong', 1);
            }
        }

        $chiTietHoaDons = ChiTietHoaDon::where('ma_hoa_don', $hoaDon->ma_hoa_don)->get();

        foreach ($chiTietHoaDons as $chiTiet) {
            $maSanPham = $chiTiet->ma_san_pham;
            $sanPham = SanPham::where('ma_san_pham', $maSanPham)->first();
            if (!$sanPham || $sanPham->loai_san_pham != 0) continue;

            $maSize = Sizes::where('ten_size', $chiTiet->ten_size)->value('ma_size');
            if (!$maSize) continue;

            $thanhPhanNLs = ThanhPhanSanPham::where('ma_san_pham', $maSanPham)
                ->where('ma_size', $maSize)
                ->get();

            foreach ($thanhPhanNLs as $tp) {
                $soLuongHoanTra = $tp->dinh_luong * $chiTiet->so_luong;

                DB::table('cua_hang_nguyen_lieus')
                    ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                    ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                    ->increment('so_luong_ton', $soLuongHoanTra);
            }
        }
    }
}
