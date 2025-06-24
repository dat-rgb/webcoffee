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
        $keyword = $request->input('search'); 

        $cuaHangs = CuaHang::all();

        $query = SanPhamCuaHang::with('sanPham', 'sanPhamCuaHang')
            ->where('ma_cua_hang', $storeId);

        if ($keyword) {
            $query->whereHas('sanPham', function($q) use ($keyword) {
                $q->where('ten_san_pham', 'like', '%'.$keyword.'%')
                ->orWhere('ma_san_pham', 'like', '%'.$keyword.'%')
                ->orWhereHas('danhMuc', function($dq) use ($keyword) {
                    $dq->where('ten_danh_muc', 'like', '%'.$keyword.'%');
                });
            });
        }

        $productShop = $query->paginate(15);
        if ($request->page > $productShop->lastPage()) {
            return redirect()->route('admin.product-shop.index', ['page' => $productShop->lastPage()]);
        }
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
                ['trang_thai' => 1, 'updated_at' => now(), 'created_at' => now()]
            );
        }
        toastr()->success('Đã thêm sản phẩm vào cửa hàng thành công.');
        return redirect()->back();
    }

    public function deleteProductShop(Request $request)
    {
        $productIds = $request->input('product_ids');
        $maCuaHang = $request->input('ma_cua_hang');

        if (!$productIds || !is_array($productIds)) {
            toastr()->error('Không có sản phẩm nào được chọn.');
            return redirect()->back();
        }

        // JOIN để lấy các sản phẩm đã nằm trong hóa đơn của cửa hàng
        $usedProductIds = \DB::table('chi_tiet_hoa_dons as ct')
            ->join('hoa_dons as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->whereIn('ct.ma_san_pham', $productIds)
            ->where('hd.ma_cua_hang', $maCuaHang)
            ->pluck('ct.ma_san_pham')
            ->toArray();

        // Lọc các sản phẩm có thể xóa
        $deletableProductIds = array_diff($productIds, $usedProductIds);

        if (empty($deletableProductIds)) {
            toastr()->warning('Tất cả sản phẩm đã có trong hóa đơn, không thể xóa.');
            return redirect()->back();
        }

        // Xoá khỏi bảng trung gian san_pham_cua_hangs hoặc bảng phù hợp
        \DB::table('san_pham_cua_hangs')
            ->where('ma_cua_hang', $maCuaHang)
            ->whereIn('ma_san_pham', $deletableProductIds)
            ->delete();

        toastr()->success('Đã xóa các sản phẩm chưa có trong hóa đơn.');
        return redirect()->back();
    }
}
