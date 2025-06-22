<?php

namespace App\Http\ViewComposers;

use App\Models\Banners;
use App\Models\ThongTinWebsite;
use Illuminate\View\View;

class ThongTinWebsiteComposer
{
    public function compose(View $view)
    {
        $thongTinWebsite = ThongTinWebsite::first(); 
        $banners = Banners::where('trang_thai', 1)
                    ->orderBy('thu_tu')
                    ->get()
                    ->groupBy('vi_tri');

        $view->with([
            'thongTinWebsite' => $thongTinWebsite,
            'banners' => $banners
        ]);
    }
}
