<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(){
        $viewData = [
            'title'=> 'Tin Tức | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.blogs.index', $viewData);
    }

    public function blogDetail(){
        $viewData = [
            'title'=> 'Chi tiết tin tức | CMDT Coffee & Tea'   
        ];

        return view('clients.pages.blogs.blog_detail', $viewData);
    }
}
