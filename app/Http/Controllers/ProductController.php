<?php

namespace App\Http\Controllers;

use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use App\Models\SanPhamYeuThich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    public function getProduct($search = '')
    {
        $selected_store_id = session('selected_store_id', null);

        $query = SanPham::with('danhMuc')->where('trang_thai', 1);

        if ($selected_store_id) {
            $query->whereHas('sanPhamCuaHang', function ($q) use ($selected_store_id) {
                $q->where('ma_cua_hang', $selected_store_id)
                ->where('trang_thai', 1);
            });
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ten_san_pham', 'like', "%{$search}%")
                ->orWhere('ma_san_pham', 'like', "%{$search}%")
                ->orWhereHas('danhMuc', function ($q2) use ($search) {
                    $q2->where('ten_danh_muc', 'like', "%{$search}%");
                })
                ->orWhere('mo_ta', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        return $products;
    }
    public function getProductByCategoryIDs(array $categoryIDs, $selected_store_id = null)
    {
        if ($selected_store_id) {
            $products = SanPham::whereHas('sanPhamCuaHang', function ($q) use ($selected_store_id) {
                    $q->where('ma_cua_hang', $selected_store_id)
                    ->where('trang_thai', 1);
                })
                ->with('danhMuc')
                ->where('trang_thai', 1)
                ->whereIn('ma_danh_muc', $categoryIDs)
                ->get();
        } else {
            $products = SanPham::with('danhMuc')
                ->where('trang_thai', 1)
                ->whereIn('ma_danh_muc', $categoryIDs)
                ->get();
        }

        return $products;
    }
    public function productList()
    {
        $products = $this->getProduct();

        $categories = DanhMucSanPham::where('trang_thai', 1)->get();

        $countCate = [];
        foreach ($categories as $cate) {
            $countCate[$cate->ma_danh_muc] = $products->where('ma_danh_muc', $cate->ma_danh_muc)->count();
        }

        $viewData = [
            'title' => 'Tất cả sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'countCate' => $countCate
        ];

        return view('clients.pages.products.product_list', $viewData);
    }
    public function listProductsByCategoryParent($slug)
    {
        $categoryParent = DanhMucSanPham::with('childrenRecursive')
            ->where('slug', $slug)
            ->firstOrFail();

        // Đệ quy lấy danh mục con cháu
        function flattenCategories($category) {
            $flat = [];
            foreach ($category->childrenRecursive as $child) {
                $flat[] = $child;
                $flat = array_merge($flat, flattenCategories($child));
            }
            return $flat;
        }

        $flatCategories = flattenCategories($categoryParent);
        $categories = collect($flatCategories);
        $categories->prepend($categoryParent);

        $categoryIDs = collect([$categoryParent->ma_danh_muc])
            ->merge($categories->pluck('ma_danh_muc'))
            ->toArray();

        $selected_store_id = session('selected_store_id', null);
        $products = $this->getProductByCategoryIDs($categoryIDs, $selected_store_id);

        // Đếm sp theo danh mục
        $countCate = [];
        foreach ($categories as $cate) {
            $countCate[$cate->ma_danh_muc] = $products->where('ma_danh_muc', $cate->ma_danh_muc)->count();
        }

        $viewData = [
            'title' => $categoryParent->ten_danh_muc . ' | CDMT coffee & tea',
            'subtitle' => $categoryParent->ten_danh_muc . ' tại nhà',
            'products' => $products,
            'categories' => $categories,
            'categoryParent' => $categoryParent,
            'countCate' => $countCate
        ];

        return view('clients.pages.products.product_list', $viewData);
    }
    public function searchProduct(Request $request) {
        $search = trim($request->input('search'));

        $products = $this->getProduct($search);

        $categories = DanhMucSanPham::where('trang_thai', 1)->get();

        $countCate = [];
        foreach ($categories as $cate) {
            $countCate[$cate->ma_danh_muc] = $products->where('ma_danh_muc', $cate->ma_danh_muc)->count();
        }

        $viewData = [
            'title' => 'Kết quả tìm kiếm cho từ khóa "' . $search . '" | CMDT Coffee & Tea',
            'subtitle' => 'Kết quả tìm kiếm cho từ khóa "' . $search . '"',
            'products' => $products,
            'categories' => $categories,
            'countCate' => $countCate,
            'search' => $search,
        ];

        return view('clients.pages.products.result_search', $viewData);
    }
    public function productDetail($slug) {
        $selected_store_id = session('selected_store_id', null);

        $product = SanPham::with('danhMuc')
            ->where('slug', $slug)
            ->where('trang_thai', 1)
            ->when($selected_store_id, function ($query) use ($selected_store_id) {
                $query->whereHas('sanPhamCuaHang', function ($q) use ($selected_store_id) {
                    $q->where('ma_cua_hang', $selected_store_id)
                    ->where('trang_thai', 1);
                });
            })
            ->first();

        if (!$product) {
            toastr()->error('Sản phẩm không tồn tại hoặc không khả dụng ở cửa hàng này.');
            return redirect()->route('product');
        }

        // Sản phẩm liên quan cùng danh mục
        $productRelate = SanPham::where('ma_danh_muc', $product->ma_danh_muc)
            ->where('trang_thai', 1)
            ->where('id', '!=', $product->id)
            ->when($selected_store_id, function ($query) use ($selected_store_id) {
                $query->whereHas('sanPhamCuaHang', function ($q) use ($selected_store_id) {
                    $q->where('ma_cua_hang', $selected_store_id)
                    ->where('trang_thai', 1);
                });
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Size sản phẩm
        $sizes = DB::table('thanh_phan_san_phams')
            ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
            ->where('thanh_phan_san_phams.ma_san_pham', $product->ma_san_pham)
            ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
            ->distinct()
            ->get();

        // Check yêu thích
        $isFavorited = false;
        if (auth()->check()) {
            $user = auth()->user();
            $khachHang = $user->khachHang;

            if ($khachHang) {
                $isFavorited = SanPhamYeuThich::where('ma_khach_hang', $khachHang->ma_khach_hang)
                    ->where('ma_san_pham', $product->ma_san_pham)
                    ->exists();
            }
        }

        return view('clients.pages.products.product_detail', [
            'title' => $product->ten_san_pham . ' | CMDT Coffee & Tea',
            'product' => $product,
            'productRelate' => $productRelate,
            'sizes' => $sizes,
            'isFavorited' => $isFavorited,
        ]);
    }
}
