<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ThongTinWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminHomeController extends Controller
{
    public function index(){
    
        $viewData = [
            
        ];
        return view('admins.pages.index');
    }

    public function thongTinWebsite(){

        $thongTinWebsite = ThongTinWebsite::first(); 

        $viewData = [
            'title' => 'Thông tin website | CDMT Coffee & tea',
            'subtitle' => 'Thông tin website',
            'thongTinWebsite' => $thongTinWebsite,
        ];
        return view('admins.pages.thong_tin_website', $viewData);
    }
}
