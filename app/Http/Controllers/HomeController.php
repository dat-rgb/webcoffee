<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $viewData = [
            'title'=> 'Trang Chủ | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.home', $viewData);
    }

    public function about(){
        $viewData = [
            'title'=> 'Giới thiệu | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.about', $viewData);
    }

    public function contact(){
        $viewData = [
            'title'=> 'Liên Hệ | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.contact', $viewData);
    }
}
