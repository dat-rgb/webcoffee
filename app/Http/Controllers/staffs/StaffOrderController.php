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
        $order = HoaDon::with(['khachHang', 'chiTietHoaDon'])->where('ma_hoa_don',$id)->first();
        return view('staffs.orders._order_detail', compact('order'));
    }
    public function detailsMulti(Request $request) {
        \Log::info('Request maHoaDons:', $request->all());

        $orderIds = $request->input('maHoaDons');

        if (!$orderIds || !is_array($orderIds)) {
            return response()->json(['error' => 'Invalid order IDs'], 400);
        }

        $orders = HoaDon::with(['khachHang', 'chiTietHoaDon'])
            ->whereIn('ma_hoa_don', $orderIds)
            ->get();

        return response()->json(['orders' => $orders]);
    }




        //Nhân viên bán hàng hoặc quản lý cửa hàng thực hiện chức năng cập nhật trạng thái đơn hàng được miêu tả như sau:
        //Xử lý update trạng thái: 0:Chờ xác nhận, 1:đã xác nhận, 2:Đã Hoàn tất đơn hàng, 3:Đang giao đơn hàng (nếu deliver)/Chờ nhận đơn hàng(nếu pickup), 4:Đã nhận, 5:Đã hủy.
        //Điều kiện update: 
        //  - update: theo hướng tăng dần từ status 0 -> status 4. ví dụ: HĐ (hóa đơn) có status = 0, thì không thể lặp tức cập nhật sang status 2 hoặc 3 hoặc 4. Và không được về lại trạng thái vừa cập nhập, ví dụ, status = 1 => status = 2 thì status = 2 không => status = 1 (not)
        //  - update: khi update status = 3, phải lưu thông tin người giao hàng, ví dụ (số điện thoại, họ tên người giao hàng vào table).
        //  - update: khi update tatus = 5, phải lưu thông tin lý do hủy đơn (đối với nhân viên hoặc quản lý thực hiện thao tác này).
        //  - Các HĐ sau khi được update status sẽ có mã nhân viên thực hiện, ban đầu khách hàng order (ma_nha_vien = null).

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

        if ($status === 5) {
            // Cập nhật trạng thái và nhân viên hủy
            $order->update([
                'trang_thai' => 5,
                'ma_nhan_vien' => $nhanVien->ma_nhan_vien,
            ]);

            // Gọi hàm cộng lại nguyên liệu + voucher
            $this->restoreIngredientsAndVoucher($order);

            // Lưu lịch sử hủy đơn
            $lichSu = new LichSuHuyDonHang();
            $lichSu->ma_hoa_don = $order->ma_hoa_don;
            $lichSu->ly_do_huy = $data['cancel_reason'];
            $lichSu->thoi_gian_huy = now();
            $lichSu->ma_nhan_vien = $nhanVien->ma_nhan_vien;
            $lichSu->save();
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
