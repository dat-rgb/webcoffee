<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function home(){
        $viewData = [
            'title'=> 'Trang Chủ | CMDT Coffee & Tea'   
        ];
        //dd(Auth::user());

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
