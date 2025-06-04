<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function home(){
        $viewData = [
            'title'=> 'Trang Chủ | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.home', $viewData);
    }

    public function about() {
        $blog = Blog::with('danhMuc')->where('ma_danh_muc_blog', 1)->first(); 

        $viewData = [
            'title' => 'Giới thiệu | CMDT Coffee & Tea',
            'blog'  => $blog,
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
