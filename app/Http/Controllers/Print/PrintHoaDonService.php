<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Log;

class PrintHoaDonService extends Controller
{
    public function printHoaDon(HoaDon $order)
    {
        $soDong = count($order->chiTietHoaDons);
            $height = 400 + ($soDong * 28); // 400 là base, 28 là mỗi dòng

            $pdf = Pdf::loadView('exports.hoa_don', [
                'order' => $order,
            ])->setPaper([0, 0, 226.8, $height]); // khổ 80mm, cao động

        return $pdf->download('hoa_don_' . $order->ma_hoa_don . '.pdf');
    }

    /**
     * In tem dán ly.
     */


public function printTemLy(HoaDon $order)
{
    try {
        $order->load([
            'khachHang',
            'chiTietHoaDon.sanPham',
            'cuaHang',
            'transaction',
            'giaoHang',
            'lichSuHuyDonHang'
        ]);

        \Log::info('[In Tem Ly] Trạng thái đơn hàng: ' . $order->trang_thai);

        if ($order->trang_thai == 1) {
            \Log::info('[In Tem Ly] Bắt đầu render PDF cho đơn hàng: ' . $order->ma_hoa_don);

            $pdf = Pdf::loadView('exports.tem_ly', [
                'order' => $order,
            ])->setPaper([0, 0, 226.8, 420.9]);

            $filename = 'tem_ly_' . $order->ma_hoa_don . '.pdf';
            Storage::put('public/tem_ly/' . $filename, $pdf->output());

            \Log::info('[In Tem Ly] PDF đã được lưu tại: storage/app/public/tem_ly/' . $filename);

            return response()->json(['success' => true, 'message' => 'Đã lưu file PDF tem ly']);
        }
    } catch (\Exception $e) {
        \Log::error('[In Tem Ly] Lỗi in tem: ' . $e->getMessage());
    }

    return null;
}


}
