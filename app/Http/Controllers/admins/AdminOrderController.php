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
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf; 

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
                'ma_nhan_vien' => null,
                'trang_thai' => 5,
            ]);

            if ($oldStatus < 2) {
                $this->restoreIngredientsAndVoucher($order);
                if ($oldStatus < 2) {
                    $this->restoreIngredientsAndVoucher($order);

                    if (
                        $order->phuong_thuc_thanh_toan === 'NAPAS247' &&
                        $order->trang_thai_thanh_toan === 1 &&
                        $order->transaction &&
                        $order->transaction->trang_thai === 'SUCCESS'
                    ) {
                        $order->update([
                            'trang_thai_thanh_toan' => 2, 
                        ]);

                        $order->transaction->update([
                            'trang_thai' => 'REFUNDING',
                        ]);
                    }
                }
            }

            LichSuHuyDonHang::create([
                'ma_hoa_don' => $order->ma_hoa_don,
                'ly_do_huy' => $data['cancel_reason'],
                'thoi_gian_huy' => now(),
                'nguoi_huy' => 'Admin',
            ]);

            $giaoHang = GiaoHang::where('ma_hoa_don', $order->ma_hoa_don)->first();
            if ($giaoHang) {
                $giaoHang->update([
                    'trang_thai' => 2,
                ]);
            }
        }
    }
    public function restoreIngredientsAndVoucher($hoaDon)
    {
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
    public function tinhDiemThanhVien($order)
    {
        if (!$order->ma_khach_hang) {
            return;
        }

        $diem = floor(($order->tong_tien - $order->tien_ship) / 10000); // 10k = 1 điểm

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
    public function manualRefund(Request $request, $maHoaDon)
    {
        $hoaDon = HoaDon::with('transaction')->where('ma_hoa_don', $maHoaDon)->first();

        if (!$hoaDon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy hóa đơn'], 404);
        }

        if (
            $hoaDon->trang_thai_thanh_toan == 2 &&
            $hoaDon->transaction &&
            $hoaDon->transaction->trang_thai === 'REFUNDING'
        ) {
            $hoaDon->update([
                'trang_thai_thanh_toan' => 3,
            ]);

            $hoaDon->transaction->update([
                'trang_thai' => 'REFUNDED',
            ]);

            return response()->json(['success' => true, 'message' => 'Đã hoàn tiền thành công.']);
        }

        return response()->json(['success' => false, 'message' => 'Không thể hoàn tiền cho đơn hàng này.'], 400);
    }

    public function exportPDF($id)
    {
        $order = HoaDon::with([
            'khachHang',
            'chiTietHoaDon.sanPham',
            'cuaHang',
            'transaction',
            'giaoHang',
            'lichSuHuyDonHang'
        ])->where('ma_hoa_don', $id)->first();

        if (!$order) {
            abort(404, 'Không tìm thấy hóa đơn');
        }

        $pdf = Pdf::loadView('exports.invoice', compact('order'))->setPaper('a4');
        return $pdf->download('hoadon_' . $order->ma_hoa_don . '.pdf');
    }
}
