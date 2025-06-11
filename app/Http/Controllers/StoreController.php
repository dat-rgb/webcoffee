<?php

namespace App\Http\Controllers;

use App\Models\CuaHang;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index()
    {
        return CuaHang::where('trang_thai', 1)->get();   // trả JSON
    }
    
    public function selectStore(Request $request)
    {
        $storeId = $request->input('store_id');
        $store = CuaHang::where('ma_cua_hang', $storeId)->first();

        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Cửa hàng không tồn tại']);
        }

        $cart = session('cart', []);

        if (!empty($cart)) {
            $cartController = new CartController(); 

            foreach ($cart as $item) {
                $result = $cartController->checkStore(
                    $item['product_id'],
                    $storeId,
                    $item['size_id'],
                    (int)$item['product_quantity'],
                    'add'
                );

                if (is_array($result) && isset($result['success']) && !$result['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'],
                    ]);
                }
            }
        }

        session([
            'selected_store_id' => $storeId,
            'selected_store_name' => $store->ten_cua_hang,
            'selected_store_dia_chi' => $store->dia_chi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã chọn cửa hàng: ' . $store->ten_cua_hang,
            'store_id' => $storeId,
            'store_name' => $store->ten_cua_hang,
        ]);
    }

    public function ganNhat(Request $request)
    {
        $lat = $request->latitude;
        $lng = $request->longitude;

        $cuaHangs = DB::table('cua_hangs')
            ->select('*')
            ->selectRaw(
                "ROUND(
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    ), 2
                ) AS khoang_cach",
                [$lat, $lng, $lat]          // bind 3 biến vào công thức
            )
            ->having('khoang_cach', '<=', 6) // chỉ lấy cửa hàng ≤ 6 km
            ->orderBy('khoang_cach')
            ->get();

        return response()->json($cuaHangs);
    }
}
