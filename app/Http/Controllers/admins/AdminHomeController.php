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

    public function updateThongTinWebsite(Request $request)
    {
        $request->validate([
            'ten_website'   => 'required|string|max:100|min:2',
            'so_dien_thoai' => 'required|string|max:20',
            'email'         => 'required|email|max:255',
            'dia_chi'       => 'required|string',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon'       => 'nullable|image|mimes:jpeg,png,jpg,ico|max:1024',
            'facebook_url'  => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'zalo_url'      => 'nullable|url',
            'youtube_url'   => 'nullable|url',
            'tiktok_url'    => 'nullable|url',
            'mo_ta'         => 'nullable|string',
            'tu_khoa'       => 'nullable|string',
            'footer_text'   => 'nullable|string',
        ],
        [
            'ten_website.required' => 'Tên Website không được để trống.',
            'ten_website.max' => 'Tên Website không được quá 100 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.'
        ]);

        $thongTin = ThongTinWebsite::first();

        $thongTin->ten_website = $request->ten_website;
        $thongTin->so_dien_thoai = $request->so_dien_thoai;
        $thongTin->email = $request->email;
        $thongTin->dia_chi = $request->dia_chi;
        $thongTin->facebook_url = $request->facebook_url;
        $thongTin->instagram_url = $request->instagram_url;
        $thongTin->zalo_url = $request->zalo_url;
        $thongTin->youtube_url = $request->youtube_url;
        $thongTin->tiktok_url = $request->tiktok_url;
        $thongTin->mo_ta = $request->mo_ta;
        $thongTin->tu_khoa = $request->tu_khoa;
        $thongTin->footer_text = $request->footer_text;
        $thongTin->ban_do = $request->ban_do;

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoPath = public_path('images/website/logo.png');
            $logoFile->move(dirname($logoPath), basename($logoPath));
            $thongTin->logo = 'website/logo.png';
        }

        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconPath = public_path('images/website/favicon.png');
            $faviconFile->move(dirname($faviconPath), basename($faviconPath));
            $thongTin->favicon = 'website/favicon.png';
        }


        $thongTin->save();

        toastr()->success('Cập nhật thông tin thành công!');
        return redirect()->back();
    }

}
