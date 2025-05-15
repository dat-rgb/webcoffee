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

        $categorys = DanhMucSanPham::where('trang_thai', 1)
            ->whereNotNull('danh_muc_cha_id')
            ->get(); 

        $countCate = [];

        foreach ($categorys as $cate) {
            $countCate[$cate->ma_danh_muc] = $products->where('ma_danh_muc', $cate->ma_danh_muc)->count();
        }
        
        $viewData = [
            'title' => 'Sản Phẩm | CMDT Coffee & Tea',
            'products' => $products,
            'categorys' => $categorys,
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
}
