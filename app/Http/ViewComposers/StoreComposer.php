<?php

namespace App\Http\ViewComposers;

use App\Models\CuaHang;
use Illuminate\View\View;

class StoreComposer
{
    public function compose(View $view)
    {
        $stores = CuaHang::where('trang_thai', 1)->get();
        $view->with('stores', $stores);
    }
}
