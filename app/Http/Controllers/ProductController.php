<?php

namespace App\Http\Controllers;

use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    public function productList() {
        $products = SanPham::with('danhMuc')
            ->where('trang_thai', 1)
            ->get();
            

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

    public function productDetail($slug){

        $product = SanPham::with('danhMuc')
            ->where('slug',$slug)
            ->where('trang_thai',1)
            ->first();

        $productRelate = SanPham::where('ma_danh_muc', $product->ma_danh_muc)
            ->where('trang_thai',1)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Lấy danh sách size của sản phẩm
        $sizes = DB::table('thanh_phan_san_phams')
            ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
            ->where('thanh_phan_san_phams.ma_san_pham', $product->ma_san_pham)
            ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
            ->distinct()
            ->get();

        if(!$product){
            toastr()->error('Sản phẩm không tồn tại.');
            return redirect()->back();
        }

        $viewData = [
            'title'=> $product->ten_san_pham . ' | CMDT Coffee & Tea',
            'product' => $product,
            'productRelate' => $productRelate,
            'sizes' => $sizes
        ];

        return view('clients.pages.products.product_detail', $viewData);
    }

    public function listProductsByCategoryParent($slug) {
        // Lấy danh mục cha cùng toàn bộ con cháu đệ quy
        $categoryParent = DanhMucSanPham::with('childrenRecursive')
            ->where('slug', $slug)
            ->firstOrFail();

        // Hàm đệ quy lấy tất cả danh mục con cháu 
        function flattenCategories($category) {
            $flat = [];
            foreach ($category->childrenRecursive as $child) {
                $flat[] = $child;
                $flat = array_merge($flat, flattenCategories($child));
            }
            return $flat;
        }

        // Lấy mảng danh mục con cháu
        $flatCategories = flattenCategories($categoryParent);

        // Tạo collection từ mảng phẳng để tiện thao tác
        $categories = collect($flatCategories);
        
        //Lấy danh mục cha
        $categories->prepend($categoryParent);

        // Lấy tất cả mã danh mục con cháu + cha
        $categoryIDs = collect([$categoryParent->ma_danh_muc])
            ->merge($categories->pluck('ma_danh_muc'))
            ->toArray();

        // Lấy sản phẩm
        $products = SanPham::with('danhMuc')
            ->where('trang_thai', 1)
            ->whereIn('ma_danh_muc', $categoryIDs)
            ->get();

        // Đếm sản phẩm theo từng danh mục con + cha
        $countCate = [];
        foreach ($categories as $cate) {
            $countCate[$cate->ma_danh_muc] = $products
                ->where('ma_danh_muc', $cate->ma_danh_muc)
                ->count();
        }
        $countCate[$categoryParent->ma_danh_muc] = $products
            ->where('ma_danh_muc', $categoryParent->ma_danh_muc)
            ->count();

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
}
