<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function getSizeProduct($proId){
        $sizes = DB::table('thanh_phan_san_phams')
            ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
            ->where('thanh_phan_san_phams.ma_san_pham', $proId)
            ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
            ->distinct()
            ->get();
        return $sizes;
    }
    public function home(){

        $blogs = $this->blogHot();
        $products = $this->productsHot();
        $sizesMap = [];
        foreach ($products as $pro) {
            $sizesMap[$pro->ma_san_pham] = $this->getSizeProduct($pro->ma_san_pham);
        }
        $viewData = [
            'title'=> 'Trang Chủ'   ,
            'blogs' => $blogs,
            'products' => $products,
            'sizesMap'=> $sizesMap,
        ];
        return view('clients.pages.home', $viewData);
    }
}
