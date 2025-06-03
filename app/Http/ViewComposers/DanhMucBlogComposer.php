<?php

namespace App\Http\ViewComposers;

use App\Models\Blog;
use App\Models\DanhMucBlog;
use Illuminate\View\View;

class DanhMucBlogComposer
{
    public function compose(View $view)
    {
        $danhMucBlog = DanhMucBlog::where('trang_thai', 1)
        ->where('ma_danh_muc_blog','>',2)
        ->get();
        
        $chinhSachs = Blog::with('danhMuc')
            ->where('trang_thai', 1)
            ->where('ma_danh_muc_blog', 2)
            ->get();

        $blogHots = Blog::with('danhMuc')
            ->where('trang_thai', 1)
            ->where('hot', 1)
            ->get();

        $data = [
            'danhMucBlog' => $danhMucBlog,
            'chinhSachs' => $chinhSachs ?? null,
            'blogHots' => $blogHots ?? null
        ];
        $view->with($data);
    }
}
