<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PrintHoaDonService extends Controller
{
    public function printHoaDon(HoaDon $order)
    {
        $order->load([
            'chiTietHoaDon',
            'khachHang',
            'transaction',
            'giaoHang',
            'cuaHang',
        ]);

        $soDong = count($order->chiTietHoaDon ?? []);
        $height = 400 + ($soDong * 28); 

        $pdf = Pdf::loadView('exports.hoa_don', [
            'order' => $order,
        ])->setPaper([0, 0, 226.8, $height]);

        return $pdf->download('hoa_don_' . $order->ma_hoa_don . '.pdf');
    }


    public function printTemLy(HoaDon $order)
    {
        try {
            $order->load([
                'chiTietHoaDon',
            ]);

            $temList = [];
            $index = 1;

            foreach ($order->chiTietHoaDon as $item) {
                for ($i = 0; $i < $item->so_luong; $i++) {
                    $temList[] = [
                        'ma_hoa_don'   => $order->ma_hoa_don,
                        'index'        => $index++,
                        'total'        => $order->chiTietHoaDon->sum('so_luong'),
                        'ten_san_pham' => $item->ten_san_pham,
                        'don_gia'     => $item->don_gia,
                        'ten_size'     => $item->ten_size,
                        'gia_size'     => $item->gia_size,
                        'ghi_chu'      => $item->ghi_chu,
                    ];
                }
            }

            $pdf = Pdf::loadView('exports.tem_ly', [
                'temList' => $temList
            ])->setPaper([0, 0, 226.8, 170]);

            return $pdf->download('tem_ly_' . $order->ma_hoa_don . '.pdf');
        } catch (\Exception $e) {
            \Log::error('[In Tem Ly] Lỗi in tem: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi máy chủ'], 500);
        }
    }
}
