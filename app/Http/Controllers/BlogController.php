<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\DanhMucBlog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::with('danhMuc')
            ->where('trang_thai', 1)
            ->where('ma_danh_muc_blog', '>', 2)
            ->orderByDesc('ngay_dang') 
            ->paginate(6);

        if ($blogs->currentPage() > $blogs->lastPage()) {
            return redirect()->route('blog', ['page' => $blogs->lastPage()]);
        }

        $viewData = [
            'title' => 'Tin Tức',
            'subtitle' => 'Chuyện nhà CDMT',
            'blogs' => $blogs,    
        ];
        return view('clients.pages.blogs.index',$viewData);
    }
    public function getBlogsByCate($slug)
    {
        $danhMuc = DanhMucBlog::where('slug', $slug)->first();

        if (!$danhMuc) {
            toastr()->error('Không tìm thấy danh mục');
            return back();
        }

        $blogs = Blog::with('danhMuc')
            ->where('trang_thai', 1)
            ->where('ma_danh_muc_blog', $danhMuc->ma_danh_muc_blog)
            ->orderByDesc('ngay_dang')
            ->paginate(6);

        if ($blogs->currentPage() > $blogs->lastPage()) {
            return redirect()->route('blog', ['page' => $blogs->lastPage()]);
        }
        
        $viewData = [
            'title' => 'Danh mục ' .  $danhMuc->ten_danh_muc_blog,
            'subtitle' =>  $danhMuc->ten_danh_muc_blog,
            'blogs' => $blogs,  
            'slugActive' => $slug,  
        ];
        return view('clients.pages.blogs.index', $viewData);
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
