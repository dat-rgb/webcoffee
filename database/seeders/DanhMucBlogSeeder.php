<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\DanhMucBlog;

class DanhMucBlogSeeder extends Seeder
{
    public function run(): void
    {
        $danhMucs = [
            'Giới thiệu',
            'Chính sách',
            'Chuyện Trà',
            'Chuyện Coffee',
        ];

        foreach ($danhMucs as $ten) {
            DanhMucBlog::create([
                'ten_danh_muc_blog' => $ten,
                'slug' => Str::slug($ten),
                'mo_ta' => null,
                'trang_thai' => 1,
            ]);
        }
    }
}
