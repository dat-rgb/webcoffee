<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Http\Controllers\payments\PaymentController;
use App\Models\ChiTietHoaDon;
use App\Models\GiaoHang;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\LichSuHuyDonHang;
use App\Models\NhanVien;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffOrderController extends Controller
{
    public function orderStore()
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        if (!$nhanVien) {
            toastr()->error('Không tìm thấy thông tin nhân viên.');
            return redirect()->back();
        }

        if (!in_array($nhanVien->ma_chuc_vu, [1, 3])) {
            toastr()->error('Bạn không có quyền truy cập.');
            return redirect()->back();
        }

        $orders = HoaDon::with(['khachHang', 'chiTietHoaDon'])
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
            ->get();

        return view('staffs.orders.index', [
            'title' => 'Đơn hàng cửa hàng '. $nhanVien->ma_cua_hang.' | CDMT Coffee & Tea',
            'subtitle' => 'Quản lý đơn hàng ' . $nhanVien->ma_cua_hang,
            'orders' => $orders,
        ]); 
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

        return view('staffs.orders._order_tbody', compact('orders'));
    }
    public function detail($id)
    {
        $order = HoaDon::with(['khachHang', 'chiTietHoaDon','khuyenMai','giaoHang','lichSuHuyDonHang'])->where('ma_hoa_don',$id)->first();
        return view('staffs.orders._order_detail', compact('order'));
    }

    
    public function updateStatusOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:hoa_dons,ma_hoa_don',
                'status' => 'required|integer|min:0|max:5',
            ]);

            $order = HoaDon::where('ma_hoa_don', $validated['order_id'])->first();

            if(!$order){
                return $this->jsonError('Mã hóa đơn không tồn tại');
            }

            if ($validated['status'] < $order->trang_thai) {
                return $this->jsonError('Không thể lùi trạng thái đơn hàng!');
            }

            $nhanVien = Auth::guard('staff')->user()->nhanvien;

            match ($validated['status']) {
                3 => $this->handleStatus3($request, $order),
                5 => $this->handleCancelStatus($request, $order, $nhanVien, 5), 
                default => null,
            };

            $order->trang_thai = $validated['status'];
            $order->ma_nhan_vien = $nhanVien->ma_nhan_vien;
            $order->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ: ' . $e->getMessage(),
            ], 500);
        }
    }
    private function jsonError($msg)
    {
        return response()->json(['success' => false, 'message' => $msg]);
    }
    private function handleStatus3(Request $request, HoaDon $order)
    {
        $data = $request->validate([
            'shipper_name' => 'required|string',
            'shipper_phone' => 'required|string',
            'note' => 'nullable|string',
        ]);

        GiaoHang::create([
            'ma_hoa_don' => $order->ma_hoa_don,
            'ma_van_don' => GiaoHang::generateMaVanDon(),
            'ho_ten_shipper' => $data['shipper_name'],
            'so_dien_thoai' => $data['shipper_phone'],
            'ghi_chu' => $data['note'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    private function handleCancelStatus(Request $request, HoaDon $order, $nhanVien, int $status)
    {
        $data = $request->validate([
            'cancel_reason' => 'required|string',
        ]);

        // Chỉ xử lý khi status gửi vào là yêu cầu hủy
        if ($status === 5) {
            $oldStatus = $order->trang_thai;

            // Cập nhật trạng thái và nhân viên hủy
            $order->update([
                'trang_thai' => 5,
                'ma_nhan_vien' => $nhanVien->ma_nhan_vien,
            ]);

            // Rollback nguyên liệu và voucher nếu đơn chưa xử lý
            if ($oldStatus === 1) {
                $this->restoreIngredientsAndVoucher($order);
            }

            // Lưu lịch sử hủy đơn
            $lichSu = new LichSuHuyDonHang();
            $lichSu->ma_hoa_don = $order->ma_hoa_don;
            $lichSu->ly_do_huy = $data['cancel_reason'];
            $lichSu->thoi_gian_huy = now();
            $lichSu->ma_nhan_vien = $nhanVien->ma_nhan_vien;
            $lichSu->save();

            // Nếu đơn có giao hàng thì cập nhật trạng thái giao hàng
            $giaoHang = GiaoHang::where('ma_hoa_don', $order->ma_hoa_don)->first();
            if ($giaoHang) {
                $giaoHang->update([
                    'trang_thai' => 2, // Giao hàng không thành công
                ]);
            }
        }
    }
    public function restoreIngredientsAndVoucher($hoaDon)
    {
        // Nếu có voucher thì cộng lại số lượng
        if ($hoaDon->ma_voucher) {
            $voucher = KhuyenMai::where('ma_voucher', $hoaDon->ma_voucher)->first();
            if ($voucher) {
                $voucher->increment('so_luong', 1);
            }
        }

        // Lấy chi tiết hóa đơn
        $chiTietHoaDons = ChiTietHoaDon::where('ma_hoa_don', $hoaDon->ma_hoa_don)->get();

        foreach ($chiTietHoaDons as $chiTiet) {
            $maSanPham = $chiTiet->ma_san_pham;
            $maSize = Sizes::where('ten_size', $chiTiet->ten_size)->value('ma_size');

            if (!$maSize) continue; // nếu size không tồn tại thì skip

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
