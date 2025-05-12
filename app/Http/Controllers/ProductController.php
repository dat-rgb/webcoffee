<?php

namespace App\Http\Controllers;

use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function productList() {
        $products = SanPham::with('danhMuc')
            ->where('trang_thai', 1)
            ->get();

        $categorys = DanhMucSanPham::where('trang_thai', 1)
            ->whereNotNull('danh_muc_cha_id')
            ->get(); 

        $viewData = [
            'title' => 'Sản Phẩm | CMDT Coffee & Tea',
            'products' => $products,
            'categorys' => $categorys
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
            ->take(3)
            ->get();

        if(!$product){
            toastr()->error('Sản phẩm không tồn tại.');
            return redirect()->back();
        }

        $viewData = [
            'title'=> $product->ten_san_pham . ' | CMDT Coffee & Tea',
            'product' => $product,
            'productRelate' => $productRelate
        ];

        return view('clients.pages.products.product_detail', $viewData);
    }
}
