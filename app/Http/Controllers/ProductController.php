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

        return $query->paginate(6)->appends(['search' => $search]);
    }

    public function productList()
    {
        $products = $this->getProduct();
        if ($products->currentPage() > $products->lastPage()) {
            return redirect()->route('product', ['page' => $products->lastPage()]);
        }
        $categories = DanhMucSanPham::where('trang_thai', 1)
            ->withCount(['sanPhams as product_count' => function ($q) {
                $q->where('trang_thai', 1);
            }])
            ->get();

        $productToHistory = $this->getProductToViewHistory();

        $viewData = [
            'title' => 'Tất cả sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'productToHistory' => $productToHistory,
            
        ];

        return view('clients.pages.products.product_list', $viewData);
    }
    public function getProductByCategorySlug($slug, $selected_store_id = null)
    {
        $categoryParent = DanhMucSanPham::with('childrenRecursive')
            ->where('slug', $slug)
            ->firstOrFail();

        // Đệ quy gom hết danh mục con
        function flattenCategories($category)
        {
            $flat = [];
            foreach ($category->childrenRecursive as $child) {
                $flat[] = $child;
                $flat = array_merge($flat, flattenCategories($child));
            }
            return $flat;
        }

        $flatCategories = flattenCategories($categoryParent);
        $categories = collect($flatCategories)->prepend($categoryParent);
        $categoryIDs = $categories->pluck('ma_danh_muc')->toArray();

        // Query sản phẩm theo danh sách danh mục
        $query = SanPham::with('danhMuc')
            ->where('trang_thai', 1)
            ->whereIn('ma_danh_muc', $categoryIDs);

        if ($selected_store_id) {
            $query->whereHas('sanPhamCuaHang', function ($q) use ($selected_store_id) {
                $q->where('ma_cua_hang', $selected_store_id)
                ->where('trang_thai', 1);
            });
        }

        return [
            'products' => $query->paginate(6),
            'categoryParent' => $categoryParent,
            'categories' => $categories
        ];
    }
    public function listProductsByCategoryParent($slug)
    {
        $selected_store_id = session('selected_store_id', null);
        $result = $this->getProductByCategorySlug($slug, $selected_store_id);

        $products = $result['products'];
        $categoryParent = $result['categoryParent'];
        $categories = $result['categories'];

        // Nếu người dùng truy cập page quá lớn
        if ($products->currentPage() > $products->lastPage()) {
            return redirect()->route('product.category.list', ['slug' => $slug, 'page' => $products->lastPage()]);
        }

        // Đếm sp theo từng danh mục con
        $countCate = [];
        foreach ($categories as $cate) {
            $countCate[$cate->ma_danh_muc] = $products->where('ma_danh_muc', $cate->ma_danh_muc)->count();
        }

        return view('clients.pages.products.product_list', [
            'title' => $categoryParent->ten_danh_muc . ' | CDMT coffee & tea',
            'subtitle' => $categoryParent->ten_danh_muc . ' tại nhà',
            'products' => $products,
            'categories' => $categories,
            'categoryParent' => $categoryParent,
            'countCate' => $countCate,
            'slugActive' => $slug,
        ]);
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
    protected function pushProductToViewedHistory($product)
    {
        $history = session()->get('viewed_products', []);

        $product_info = [
            'id' => $product->id,
            'ma_san_pham' => $product->ma_san_pham,
            'ten_san_pham' => $product->ten_san_pham,
            'anh_dai_dien' => $product->hinh_anh,
            'slug' => $product->slug,
            'is_new' => $product->is_new,
            'hot' => $product->hot,
            'viewed_at' => now()->timestamp,
        ];

        $history = array_filter($history, function ($item) use ($product_info) {
            return $item['ma_san_pham'] != $product_info['ma_san_pham'];
        });

        array_unshift($history, $product_info);

        $history = array_slice($history, 0, 5);

        session()->put('viewed_products', $history);
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
        $this->pushProductToViewedHistory($product);
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
        $productToHistory = $this->getProductToViewHistory();

        return view('clients.pages.products.product_detail', [
            'title' => $product->ten_san_pham . ' | CMDT Coffee & Tea',
            'product' => $product,
            'productRelate' => $productRelate,
            'sizes' => $sizes,
            'isFavorited' => $isFavorited,
            'productToHistory' => $productToHistory,
        ]);
    }

    public function getProductToViewHistory()
    {
        $viewedProducts = session()->get('viewed_products', []);

        $latestFourViewedProducts = array_slice($viewedProducts, 0, 4);

        return $latestFourViewedProducts;
    }

    public function removeProductFromViewHistory($productId)
    {
        $history = session()->get('viewed_products', []);

        $updatedHistory = array_values(array_filter($history, function ($item) use ($productId) {
            return $item['ma_san_pham'] != $productId;
        }));

        session()->put('viewed_products', $updatedHistory);

        toastr()->success('Đã xóa sản phẩm khỏi lịch sử đã xem!');
        return redirect()->back(); 
    }

    public function clearAllViewHistory()
    {
        session()->forget('viewed_products'); // Xóa key 'viewed_products' khỏi session

        toastr()->success('Đã xóa toàn bộ lịch sử sản phẩm đã xem!');
        return redirect()->back(); 
    }
}
