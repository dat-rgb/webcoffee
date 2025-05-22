<?php

namespace App\Http\ViewComposers;

use App\Models\DanhMucSanPham;
use Illuminate\View\View;

class CategoryComposer
{
    public function compose(View $view)
    {
        $categories = DanhMucSanPham::with(['children.sanPhams', 'sanPhams'])
            ->whereNull('danh_muc_cha_id')
            ->where('trang_thai', 1)
            ->get();

        foreach ($categories as $category) {
            // Chỉ đếm sản phẩm đang hoạt động
            $parentProductsCount = $category->sanPhams->where('trang_thai', 1)->count();
            $childrenProductsCount = $category->children->sum(function ($child) {
                return $child->sanPhams->where('trang_thai', 1)->count();
            });

            $category->totalProductsCount = $parentProductsCount + $childrenProductsCount;
        }

        $view->with('danhMucCha', $categories);
    }


}
