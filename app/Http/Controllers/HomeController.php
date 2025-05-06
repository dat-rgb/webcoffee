<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $viewData = [
            'title'=> 'Trang Chủ | CMDT Coffee & Tea'   
        ];

        return view('pages.home', $viewData);
    }

    public function about(){
        $viewData = [
            'title'=> 'Giới thiệu | CMDT Coffee & Tea'   
        ];

        return view('pages.about', $viewData);
    }

    public function contact(){
        $viewData = [
            'title'=> 'Liên Hệ | CMDT Coffee & Tea'   
        ];

        return view('pages.contact', $viewData);
    }

    //demo giao diện trang đăng ký/ đăng nhập.
    public function login(){
        $viewData = [
            'title'=> 'Đăng nhập | CMDT Coffee & Tea'   
        ];

        return view('pages.login', $viewData);
    }
    public function register(){
        $viewData = [
            'title'=> 'Đăng ký | CMDT Coffee & Tea'   
        ];

        return view('pages.register', $viewData);
    }
}
