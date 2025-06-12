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
            ->orderByDesc('ngay_lap_hoa_don')
            ->get();

        return view('staffs.orders.index', [
            'title' => 'Đơn hàng cửa hàng '. $nhanVien->ma_cua_hang.' | CDMT Coffee & Tea',
            'subtitle' => 'Quản lý đơn hàng ' . $nhanVien->ma_cua_hang,
            'orders' => $orders,
        ]); 
    }
    public function filter(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien ?? null;
        $query = HoaDon::query();

        if ($nhanVien && $nhanVien->ma_cua_hang) {
            $query->where('ma_cua_hang', $nhanVien->ma_cua_hang);
        }

        if ($request->pt_thanh_toan) {
            $query->where('phuong_thuc_thanh_toan', $request->pt_thanh_toan);
        }

        if (is_numeric($request->trang_thai)) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_hoa_don', 'like', "%$search%")
                ->orWhere('ten_khach_hang', 'like', "%$search%");
            });
        }

        $orders = $query->latest()->get();

        return view('staffs.orders._order_tbody', compact('orders'))->render();
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

            if (!$order) {
                return $this->jsonError('Mã hóa đơn không tồn tại');
            }

            if ($validated['status'] < $order->trang_thai) {
                return $this->jsonError('Không thể lùi trạng thái đơn hàng!');
            }

            $nhanVien = Auth::guard('staff')->user()->nhanvien;

            match ($validated['status']) {
                3 => $order->phuong_thuc_nhan_hang !== 'pickup'
                        ? $this->handleStatus3($request, $order)
                        : null,
                4 => [
                    $order->trang_thai_thanh_toan = 1,
                    $this->tinhDiemThanhVien($order)
                ],
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
            if ($oldStatus < 2) {
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
    public function tinhDiemThanhVien($order)
    {
        if (!$order->ma_khach_hang) {
            return;
        }

        $tongTien = $order->tam_tinh ?? $order->chiTietHoaDon->sum(function ($item) {
            return ($item->so_luong * $item->don_gia) + $item->gia_size;
        });

        $diem = floor($tongTien / 10000); // 10k = 1 điểm

        if ($diem > 0) {
            $khach = $order->khachHang;
            $diemHienTai = $khach->diem_thanh_vien ?? 0;
            $diemSau = $diemHienTai + $diem;

            $khach->diem_thanh_vien = $diemSau;

            if ($diemSau >= 600) {
                $khach->hang_thanh_vien = 'Vàng';
            } elseif ($diemSau >= 300) {
                $khach->hang_thanh_vien = 'Bạc';
            } else {
                $khach->hang_thanh_vien = 'Đồng';
            }

            $khach->save();
        }
    }
}
