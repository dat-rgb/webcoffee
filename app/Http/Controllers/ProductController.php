<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productList(){
        $viewData = [
            'title'=> 'Sản Phẩm | CMDT Coffee & Tea'   
        ];

        return view('pages.products.product_list', $viewData);
    }

    public function productDetail(){
        $viewData = [
            'title'=> 'Chi tiết sản phẩm | CMDT Coffee & Tea'   
        ];

        return view('pages.products.product_detail', $viewData);
    }
}
