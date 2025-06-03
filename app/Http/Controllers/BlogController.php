<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::with('danhMuc')
            ->where('trang_thai', 1)
            ->where('ma_danh_muc_blog', '>', 2)
            ->orderByDesc('ngay_dang') 
            ->get();

        return view('clients.pages.blogs.index', [
            'title' => 'Tin Tức | CMDT Coffee & Tea',
            'blogs' => $blogs,
        ]);
    }
    public function detail($slug)
    {
        $blog = Blog::where('slug', $slug)->where('trang_thai', 1)->first();

        if(!$blog){
            toastr()->error('Bài viết không tồn tại');
            return redirect()->back();
        }

        $viewData = [
            'title' => $blog->tieu_de . ' | CMDT Coffee & Tea',
            'blog' => $blog,
        ];

        return view('clients.pages.blogs.blog_detail', $viewData);
    }

}
