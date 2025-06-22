<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banners')->insert([
            // Top banner hero
            [
                'tieu_de' => 'CDMT Coffee & Tea',
                'noi_dung' => 'Thức uống thơm ngon Đậm vị riêng',
                'hinh_anh' => 'banners/h6.jpg',
                'link_dich' => '/products',
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'top_banner',
                'thu_tu' => 1,
                'trang_thai' => 1,
            ],

            // Main slider 1
            [
                'tieu_de' => 'Không gian đậm chất Coffee & Tea',
                'noi_dung' => 'Thư giãn trong không gian ấm cúng & hiện đại',
                'hinh_anh' => 'banners/h8.jpg',
                'link_dich' => '/products',
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'main_slider',
                'thu_tu' => 1,
                'trang_thai' => 1, 
            ],

            // Main slider 2
            [
                'tieu_de' => 'Cà phê nguyên chất 100%',
                'noi_dung' => 'Đậm đà hương vị Việt',
                'hinh_anh' => 'banners/h9.jpg',
                'link_dich' => '/products',
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'main_slider',
                'thu_tu' => 2,
                'trang_thai' => 1,
            ],

            // Main slider 3
            [
                'tieu_de' => 'Khám phá thế giới Trà thơm mát',
                'noi_dung' => 'Coffee & Tea',

                'hinh_anh' => 'banners/h6.jpg',
                'link_dich' => '/products',
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'main_slider',
                'thu_tu' => 3,
                'trang_thai' => 1,
            ],

            // Background section about
            [
                'tieu_de' => 'Giới thiệu CDMT',
                'noi_dung' => 'Không gian quán cà phê CDMT ấm cúng và hiện đại',
                'hinh_anh' => 'banners/h7.jpg',
                'link_dich' => '/products',
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'about_section_bg',
                'thu_tu' => 1,
                'trang_thai' => 1,
            ],

            // Store gallery
            [
                'tieu_de' => '',
                'noi_dung' => null,
                'hinh_anh' => 'banners/h1.jpg',
                'link_dich' => null,
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'store_gallery',
                'thu_tu' => 1,
                'trang_thai' => 1,
            ],
            [
                'tieu_de' => null,
                'noi_dung' => null,

                'hinh_anh' => 'banners/h2.jpg',
                'link_dich' => null,
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'store_gallery',
                'thu_tu' => 2,
                'trang_thai' => 1,
            ],
            [
                'tieu_de' => null,
                'noi_dung' => null,

                'hinh_anh' => 'banners/h3.jpg',
                'link_dich' => null,
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'store_gallery',
                'thu_tu' => 3,
                'trang_thai' => 1,
            ],
            [
                'tieu_de' => null,
                'noi_dung' => null,
                'hinh_anh' => 'banners/h4.jpg',
                'link_dich' => null,
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'store_gallery',
                'thu_tu' => 4,
                'trang_thai' => 1,
            ],
            [
                'tieu_de' => null,
                'noi_dung' => null,
                'hinh_anh' => 'banners/h5.jpg',
                'link_dich' => null,
                'trang_hien_thi' => 'trang_chu',
                'vi_tri' => 'store_gallery',
                'thu_tu' => 5,
                'trang_thai' => 1,
            ],
        ]);
    }
}
