<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart(){
        $viewData = [
            'title'=> 'Giỏ Hàng | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.carts.index', $viewData);
    }
}
