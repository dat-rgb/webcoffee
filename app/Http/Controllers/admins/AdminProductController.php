<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function listProducts(){

        $products = SanPham::with('danhMuc')->paginate(10);

        $viewData = [
            'title' => 'Sản Phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Quản lý sản phẩm',
            'products' => $products,
        ];

        return view('admins.products.index', $viewData);
    }

    public function showProductForm(){
        $viewData = [
            'title' => 'Thêm sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Thêm sản phẩm',
        ];

        return view('admins.products.product_form', $viewData);
    }
}
