<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\CuaHang;
use App\Models\SanPham;
use App\Models\SanPhamCuaHang;
use Illuminate\Http\Request;

class AdminProductShopController extends Controller
{
    public function getProductsNoShop($storeId)
    {
        $productIdsInShop = SanPhamCuaHang::where('ma_cua_hang', $storeId)
            ->pluck('ma_san_pham')
            ->toArray();

        $productsNoShop = SanPham::whereNotIn('ma_san_pham', $productIdsInShop)
            ->get();

        return $productsNoShop;
    }

    public function index(Request $request){

        $storeId = $request->input('ma_cua_hang');
        
        $cuaHangs = CuaHang::all();
        $productShop = SanPhamCuaHang::with('sanPham', 'sanPhamCuaHang')->where('ma_cua_hang',$storeId)->get();
        $productsNoShop = $storeId ? $this->getProductsNoShop($storeId) : collect([]);
        

        $viewData = [
            'title' => 'Sản phẩm tại cửa hàng | CMDT Coffee & Tea',
            'subtitle' => 'Sản phẩm tại cửa hàng',
            'cuaHangs' => $cuaHangs,
            'productShop' => $productShop,
            'productsNoShop' => $productsNoShop,
        ];

        return view('admins.products.productshop.index', $viewData);
    }

    public function addToShop(Request $request)
    {
        $validated = $request->validate([
            'ma_cua_hang' => 'required|exists:cua_hangs,ma_cua_hang',
            'san_pham_ids' => 'required|array|min:1',
            'san_pham_ids.*' => 'exists:san_phams,ma_san_pham'
        ]);

        $maCuaHang = $validated['ma_cua_hang'];
        $sanPhamIds = $validated['san_pham_ids'];

        foreach ($sanPhamIds as $maSanPham) {
            \DB::table('san_pham_cua_hangs')->updateOrInsert(
                ['ma_san_pham' => $maSanPham, 'ma_cua_hang' => $maCuaHang],
                ['trang_thai' => 0, 'updated_at' => now(), 'created_at' => now()]
            );
        }
        toastr()->success('Đã thêm sản phẩm vào cửa hàng thành công.');
        return redirect()->back();
    }
}
