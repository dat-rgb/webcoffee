<?php

namespace App\Http\ViewComposers;

use App\Models\ThongTinWebsite;
use Illuminate\View\View;

class ThongTinWebsiteComposer
{
    public function compose(View $view)
    {
        $thongTinWebsite = ThongTinWebsite::first(); 
        $view->with('thongTinWebsite', $thongTinWebsite);
    }
}
