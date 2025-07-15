<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Http\Controllers\payments\PaymentController;
use App\Http\Controllers\Print\PrintHoaDonService;
use App\Models\CaLamViec;
use App\Models\ChiTietHoaDon;
use App\Models\GiaoHang;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\LichSuHuyDonHang;
use App\Models\NhanVien;
use App\Models\Settings;
use App\Models\SanPham;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffOrderController extends Controller
{
    protected $printService;

    public function __construct(PrintHoaDonService $printService)
    {
        $this->settings = Settings::first(); 
        $this->printService = $printService;
    }
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

        $orders = HoaDon::with(['khachHang', 'chiTietHoaDon','transaction'])
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
            ->orderByDesc('ngay_lap_hoa_don')
            ->get();

        

        return view('staffs.orders.index', [
            'title' => 'Đơn hàng cửa hàng '. $nhanVien->ma_cua_hang.' | CDMT Coffee & Tea',
            'subtitle' => 'Quản lý đơn hàng ' . $nhanVien->ma_cua_hang,
            'orders' => $orders,
            'highlightId' => request('highlight',null)
        ]); 
    }
    public function filter(Request $request)
    {
        $query = HoaDon::query();

        if (Auth::guard('staff')->check()) {
            $nhanVien = Auth::guard('staff')->user()->nhanvien;
            if ($nhanVien && $nhanVien->ma_cua_hang) {
                $query->where('ma_cua_hang', $nhanVien->ma_cua_hang);
            }
        }

        if ($request->pt_thanh_toan) {
            $query->where('phuong_thuc_thanh_toan', $request->pt_thanh_toan);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tt_thanh_toan')) {
            $query->where('trang_thai_thanh_toan', $request->tt_thanh_toan);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ma_hoa_don', 'like', "%$search%")
                ->orWhere('ten_khach_hang', 'like', "%$search%");
            });
        }

        $orders = $query->orderByDesc('ngay_lap_hoa_don')->get();


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

        if ($status === 5) {
            $oldStatus = $order->trang_thai;

            $order->update([
                'trang_thai' => 5,
                'ma_nhan_vien' => $nhanVien->ma_nhan_vien,
            ]);

            $this->restorePackedProductOnly($order);

            if ($oldStatus < 2) {
                $this->restoreVoucherAndBrewedProductOnly($order);

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

            LichSuHuyDonHang::create([
                'ma_hoa_don' => $order->ma_hoa_don,
                'ly_do_huy' => $data['cancel_reason'],
                'thoi_gian_huy' => now(),
                'nguoi_huy' => 'NV - ' . $nhanVien->ho_ten_nhan_vien,
            ]);

            $giaoHang = GiaoHang::where('ma_hoa_don', $order->ma_hoa_don)->first();
            if ($giaoHang) {
                $giaoHang->update([
                    'trang_thai' => 2,
                ]);
            }
        }
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

    public function tinhDiemThanhVien($order)
    {
        if (!$order->ma_khach_hang) {
            return;
        }

        $tongTien = $order->tam_tinh ?? $order->chiTietHoaDon->sum(function ($item) {
            return ($item->so_luong * $item->don_gia) + $item->gia_size;
        });

        $tyLe = intval($this->settings->ty_le_diem_thuong ?? 1000); // fallback nếu không có
        if ($tyLe <= 0) $tyLe = 1000; // tránh chia 0

        $diem = floor($tongTien / $tyLe); // ví dụ 1000 VND = 1 điểm

        if ($diem > 0) {
            $khach = $order->khachHang;
            $diemHienTai = $khach->diem_thanh_vien ?? 0;
            $diemSau = $diemHienTai + $diem;

            $khach->diem_thanh_vien = $diemSau;

            // Cập nhật hạng thành viên
            if($diemSau >= 900){
                $khach->hang_thanh_vien = 'Kim Cương';
            }elseif ($diemSau >= 600) {
                $khach->hang_thanh_vien = 'Vàng';
            } elseif ($diemSau >= 300) {
                $khach->hang_thanh_vien = 'Bạc';
            } else {
                $khach->hang_thanh_vien = 'Đồng';
            }

            $khach->save();
        }
    }

    public function countHoaDonMoi()
    {
        try {
            $maCuaHang = Auth::guard('staff')->user()->nhanVien->ma_cua_hang;

            // Đếm đơn hàng mới
            $count = HoaDon::countDonHangMoi($maCuaHang);

            // Lấy danh sách đơn hàng mới (giới hạn 5 đơn gần nhất)
            $donHangMoi = HoaDon::where('ma_cua_hang', $maCuaHang)
                ->where('trang_thai', 0)
                ->orderBy('ngay_lap_hoa_don', 'desc')
                ->take(5)
                ->get(['ma_hoa_don', 'ngay_lap_hoa_don']); // Chỉ lấy trường cần

            return response()->json([
                'orderCount' => $count,
                'orders' => $donHangMoi,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi lấy số lượng đơn hàng'], 500);
        }
    }

    public function loadOrdersPartial()
    {
        try {
            $nhanVien = Auth::guard('staff')->user()->nhanvien;

            if (!$nhanVien) {
                \Log::error('Không tìm thấy nhân viên');
                return response('Không có nhân viên', 403);
            }

            $orders = HoaDon::with(['khachHang', 'chiTietHoaDon', 'transaction'])
                ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
                ->orderByDesc('ngay_lap_hoa_don')
                ->get();

            return view('staffs.orders._order_tbody', compact('orders'));
        } catch (\Exception $e) {
            \Log::error('Lỗi loadOrdersPartial: ' . $e->getMessage());
            return response('Có lỗi xảy ra', 500);
        }
    }
    public function thongKeOrderNhanVien(Request $request)
    {
        try {
            $nhanVien = Auth::guard('staff')->user()->nhanvien;

            $ca = CaLamViec::where('ma_nhan_vien', $nhanVien->ma_nhan_vien)
                ->whereNull('thoi_gian_ra')
                ->latest()
                ->first();

            if (!$ca) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy ca làm việc đang mở.'
                ], 404);
            }

            $hoaDonQuery = HoaDon::where('ma_nhan_vien', $nhanVien->ma_nhan_vien)
                ->where('created_at', '>=', $ca->thoi_gian_vao)
                ->where('trang_thai', 4);

            $tong_don_xac_nhan = $hoaDonQuery->count();
            $tong_tien_cod = (clone $hoaDonQuery)->where('phuong_thuc_thanh_toan', 'COD')->sum('tong_tien');
            $tong_tien_online = (clone $hoaDonQuery)->where('phuong_thuc_thanh_toan', '!=', 'COD')->sum('tong_tien');
            $tong_tien = $tong_tien_cod + $tong_tien_online;

            return response()->json([
                'success' => true,
                'data' => compact('tong_don_xac_nhan', 'tong_tien_cod', 'tong_tien_online', 'tong_tien')
            ]);
        } catch (\Throwable $e) {
            \Log::error('Lỗi thống kê đơn hàng: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
}
