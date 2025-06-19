<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\CuaHang;
use App\Models\GiaoHang;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\LichSuHuyDonHang;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    public function getStore(){
        return CuaHang::where('trang_thai',1)->get();
    }

    public function index(Request $request)
    {
        $cuaHang = $this->getStore();

        $orders = collect(); 

        if ($request->filled('ma_cua_hang')) {
            $query = HoaDon::with(['khachHang', 'chiTietHoaDon'])
                ->where('ma_cua_hang', $request->ma_cua_hang);
                

            if ($request->filled('search')) {
                $query->where('ma_hoa_don', 'like', '%'.$request->search.'%');
            }

            $orders = $query->orderByDesc('ngay_lap_hoa_don')->get();
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

    public function updateStatusOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:hoa_dons,ma_hoa_don',
                'status' => 'required|integer|min:0|max:5',
            ]);

            $order = HoaDon::where('ma_hoa_don', $validated['order_id'])->first();

            if (!$order) {
                return $this->jsonError('Mã hóa đơn không tồn tại');
            }

            if ($validated['status'] < $order->trang_thai) {
                return $this->jsonError('Không thể lùi trạng thái đơn hàng!');
            }

            match ($validated['status']) {
                3 => $order->phuong_thuc_nhan_hang !== 'pickup'
                        ? $this->handleStatus3($request, $order)
                        : null,
                4 => [
                    $order->trang_thai_thanh_toan = 1,
                    $this->tinhDiemThanhVien($order)
                ],
                5 => $this->handleCancelStatusAdmin($request, $order, 5),
                default => null,
            };

            $order->trang_thai = $validated['status'];
            $order->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function handleCancelStatusAdmin(Request $request, HoaDon $order, int $status)
    {
        $data = $request->validate([
            'cancel_reason' => 'required|string',
        ]);

        if ($status === 5) {
            $oldStatus = $order->trang_thai;

            $order->update([
                'trang_thai' => 5,
            ]);

            if ($oldStatus < 2) {
                $this->restoreIngredientsAndVoucher($order);
            }

            LichSuHuyDonHang::create([
                'ma_hoa_don' => $order->ma_hoa_don,
                'ly_do_huy' => $data['cancel_reason'],
                'thoi_gian_huy' => now(),
                'ma_nhan_vien' => null // không có nhân viên
            ]);

            $giaoHang = GiaoHang::where('ma_hoa_don', $order->ma_hoa_don)->first();
            if ($giaoHang) {
                $giaoHang->update([
                    'trang_thai' => 2,
                ]);
            }
        }
    }
}
