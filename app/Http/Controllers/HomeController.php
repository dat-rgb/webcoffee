<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function about() {
        $blog = Blog::with('danhMuc')->where('ma_danh_muc_blog', 1)->first(); 

        $viewData = [
            'title' => 'Giới thiệu',
            'blog'  => $blog,
        ];

        return view('clients.pages.about', $viewData);
    }

    public function contact(){
        $viewData = [
            'title'=> 'Liên Hệ',
            'subtitle' => 'Liên hệ',
        ];
        return view('clients.pages.contact', $viewData);
    }

    public function blogHot()
    {
        $blogs = Blog::where('trang_thai', 1)
                    ->where('hot', 1)
                    ->orderBy('ngay_dang', 'desc')
                    ->take(3)
                    ->get();

        return $blogs;
    }

    public function productsHot()
    {
        $products = SanPham::where('trang_thai', 1)
                    ->where('hot', 1)
                    ->take(8)
                    ->get();

        return $products;
    }

    public function home(){

        $blogs = $this->blogHot();
        $products = $this->productsHot();
        $viewData = [
            'title'=> 'Trang Chủ'   ,
            'blogs' => $blogs,
            'products' => $products,
        ];
        return view('clients.pages.home', $viewData);
    }
}
