<?php

namespace App\Jobs;

use App\Models\ChiTietHoaDon;
use App\Models\CuaHangNguyenLieu;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
class CheckExpiredOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredTime = now()->subSeconds(60);

        $orders = HoaDon::where('trang_thai', 0)
            ->where('trang_thai_thanh_toan', 0)
            ->where('phuong_thuc_thanh_toan', '!=', 'COD')
            ->where('ngay_lap_hoa_don', '<', $expiredTime)
            ->whereHas('transaction', function ($query) {
                $query->where('trang_thai', 'PENDING');
            })
            ->with('transaction')
            ->get();

        Log::info('Số đơn quá hạn cần xử lý: ' . $orders->count());

        foreach ($orders as $order) {
            $maHoaDon = $order->ma_hoa_don;
            Log::info("Kiểm tra đơn: {$maHoaDon}");

            $order->update(['trang_thai' => 5]);
            $order->transaction->update(['trang_thai' => 'CANCELLED']);

            $this->restorePackedProductOnly($order);
            $this->restoreVoucherAndBrewedProductOnly($order);

            Log::info("Đã huỷ đơn quá hạn: {$maHoaDon}");
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
            if (!$tp || !$tp->dinh_luong) continue;

            $soLuongHoanTra = $tp->dinh_luong * $chiTiet->so_luong;

            $affected = DB::table('cua_hang_nguyen_lieus')
                ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                ->increment('so_luong_ton', $soLuongHoanTra);

            if ($affected) {
                Log::info("✅ Packed: Cộng lại {$soLuongHoanTra} nguyên liệu [{$tp->ma_nguyen_lieu}] cho đơn [{$hoaDon->ma_hoa_don}]");
            } else {
                Log::warning("❌ Packed: KHÔNG tìm thấy nguyên liệu [{$tp->ma_nguyen_lieu}] để cộng lại (cửa hàng: {$hoaDon->ma_cua_hang})");
            }
        }
    }
    public function restoreVoucherAndBrewedProductOnly($hoaDon)
    {
        if ($hoaDon->ma_voucher) {
            $voucher = KhuyenMai::where('ma_voucher', $hoaDon->ma_voucher)->first();
            if ($voucher) {
                $voucher->increment('so_luong', 1);
                Log::info("Đã cộng lại voucher [{$hoaDon->ma_voucher}]");
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
                if (!$tp->dinh_luong) continue;

                $soLuongHoanTra = $tp->dinh_luong * $chiTiet->so_luong;

                $affected = DB::table('cua_hang_nguyen_lieus')
                    ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                    ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                    ->increment('so_luong_ton', $soLuongHoanTra);

                if ($affected) {
                    Log::info("Brewed: Cộng lại {$soLuongHoanTra} nguyên liệu [{$tp->ma_nguyen_lieu}] cho đơn [{$hoaDon->ma_hoa_don}]");
                } else {
                    Log::warning("Brewed: KHÔNG tìm thấy nguyên liệu [{$tp->ma_nguyen_lieu}] để cộng lại (cửa hàng: {$hoaDon->ma_cua_hang})");
                }
            }
        }
    }
}
