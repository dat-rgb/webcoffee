<?php

namespace App\Http\Controllers;

use App\Models\CuaHang;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function selectStore(Request $request)
    {
        $storeId = $request->input('store_id');
        $store = CuaHang::where('ma_cua_hang', $storeId)->first();

        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Cửa hàng không tồn tại']);
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
