<?php

namespace App\Http\Controllers;

use App\Models\CuaHang;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
class StoreController extends Controller
{
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
}
