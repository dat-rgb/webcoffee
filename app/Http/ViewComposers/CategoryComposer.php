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
            // Sản phẩm của danh mục cha
            $parentProductsCount = $category->sanPhams->count();

            // Sản phẩm của tất cả danh mục con
            $childrenProductsCount = $category->children->sum(fn($child) => $child->sanPhams->count());

            // Tổng sản phẩm trong danh mục cha (bao gồm con)
            $category->totalProductsCount = $parentProductsCount + $childrenProductsCount;
        }

        $view->with('danhMucCha', $categories);
    }

}
