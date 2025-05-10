<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SanPhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sanPhams = [
            // 5 Sản phẩm thuộc danh mục Cà Phê Việt Nam
            //1
            [
                'ma_san_pham' => 'SP00000001',
                'ten_san_pham' => 'Cà phê sữa Đá',
                'gia' => 30000, // S, L, XL
                'slug' => Str::slug('Cà phê sữa Đá'),
                'thu_tu' => 1,
                'hot' => 1,
                'is_new'=> 0,
                'mo_ta' => 'Cà phê sữa đậm đà, béo ngậy.',
                'hinh_anh' => 'products/cafe_sua_da.jpg',
                'ma_danh_muc' => 5,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //2 Hot
            [
                'ma_san_pham' => 'SP00000002',
                'ten_san_pham' => 'Cà Phê Đen Đá',
                'gia' => 25000, // S, L, XL
                'slug' => Str::slug('Cà Phê Đen Đá'),
                'thu_tu' => 2,
                'hot' => 0,
                'is_new'=> 0,
                'mo_ta' => 'Cà phê đen đá đậm đà chất Việt.',
                'hinh_anh' => 'products/cafe_den_da.jpg',
                'ma_danh_muc' => 5, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //3 New
            [
                'ma_san_pham' => 'SP00000003',
                'ten_san_pham' => 'Sữa Tươi Cà Phê',
                'gia' => 35000, // S, L, XL
                'slug' => Str::slug('Sữa Tươi Cà Phê'),
                'thu_tu' => 3,
                'hot' => 0,
                'is_new'=> 1,
                'mo_ta' => 'Sữa tươi cà phê đậm đà, béo ngậy.',
                'hinh_anh' => 'products/sua_tuoi_cafe.jpg',
                'ma_danh_muc' => 5,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //4
            [
                'ma_san_pham' => 'SP00000004',
                'ten_san_pham' => 'Cà Phê Sữa Nóng',
                'gia' => 30000, // S, L
                'slug' => Str::slug('Cà Phê Sữa Nóng'),
                'thu_tu' => 4,
                'hot' => 0,
                'is_new'=> 0,
                'mo_ta' => 'Cà phê sữa nóng đậm đà, béo ngậy.',
                'hinh_anh' => 'products/cafe_sua_nong.jpg',
                'ma_danh_muc' => 5, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //5
            [
                'ma_san_pham' => 'SP00000005',
                'ten_san_pham' => 'Cà Phê Đen Nóng',
                'gia' => 25000, // S, L
                'slug' => Str::slug('Cà Phê Đen Nóng'),
                'thu_tu' => 5,
                'hot' => 0,
                'is_new'=> 0,
                'mo_ta' => 'Cà phê đen nóng đậm đà chất Việt.',
                'hinh_anh' => 'products/cafe_den_nong.jpg',
                'ma_danh_muc' => 5, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 5 Sản phẩm thuộc danh mục Cà Phê Pha Máy
            //6
            [
                'ma_san_pham' => 'SP00000006',
                'ten_san_pham' => 'Americano',
                'gia' => 30000, // S, L
                'slug' => Str::slug('Americano'),
                'thu_tu' => 6,
                'hot' => 0,
                'is_new'=> 0,
                'mo_ta' => 'Americano',
                'hinh_anh' => 'products/americano_ly.jpg',
                'ma_danh_muc' => 6, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //7 Hot
            [
                'ma_san_pham' => 'SP00000007',
                'ten_san_pham' => 'Latte Đá',
                'gia' => 30000, // S, L, XL
                'slug' => Str::slug('Latte Đá'),
                'thu_tu' => 7,
                'hot' => 1,
                'is_new' => 0,
                'mo_ta' => 'Latte Đá',
                'hinh_anh' => 'products/latte_da.jpg',
                'ma_danh_muc' => 6, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //8 New
            [
                'ma_san_pham' => 'SP00000008',
                'ten_san_pham' => 'Capuchino Đá',
                'gia' => 35000,
                'slug' => Str::slug('Capuchino Đá'),
                'thu_tu' => 7,
                'hot' => 0,
                'is_new' => 1,
                'mo_ta' => 'Capuchino Đá',
                'hinh_anh' => 'products/capuchino_da.jpg',
                'ma_danh_muc' => 6, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //9
            [
                'ma_san_pham' => 'SP00000009',
                'ten_san_pham' => 'Capuchino Nóng',
                'gia' => 35000,
                'slug' => Str::slug('Capuchino Nóng'),
                'thu_tu' => 8,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Capuchino Nóng',
                'hinh_anh' => 'products/capuchino_nong.jpg',
                'ma_danh_muc' => 6, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //10
            [
                'ma_san_pham' => 'SP00000010',
                'ten_san_pham' => 'Espresso',
                'gia' => 35000,
                'slug' => Str::slug('Espresso'),
                'thu_tu' => 9,
                'hot' => 1,
                'is_new' => 0,
                'mo_ta' => 'Espresso',
                'hinh_anh' => 'products/Espresso.jpg',
                'ma_danh_muc' => 6, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 5 sản phẩm thuộc danh mục Trà Trái Cây.
            //11
            [
                'ma_san_pham' => 'SP00000011',
                'ten_san_pham' => 'Trà Đào',
                'gia' => 30000,
                'slug' => Str::slug('Trà Đào'),
                'thu_tu' => 10,
                'hot' => 1,
                'is_new' => 0,
                'mo_ta' => 'Trà Đào Cam Xả',
                'hinh_anh' => 'products/tra_dao_cam_xa.jpg',
                'ma_danh_muc' => 7, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //12
            [
                'ma_san_pham' => 'SP00000012',
                'ten_san_pham' => 'Trà Vải',
                'gia' => 30000,
                'slug' => Str::slug('Trà Vải'),
                'thu_tu' => 11,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Trà Vải',
                'hinh_anh' => 'products/TraVai.webp',
                'ma_danh_muc' => 7, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //13
            [
                'ma_san_pham' => 'SP00000013',
                'ten_san_pham' => 'Trà Xoài',
                'gia' => 30000,
                'slug' => Str::slug('Trà Xoài'),
                'thu_tu' => 12,
                'hot' => 0,
                'is_new' => 1,
                'mo_ta' => 'Trà Xoài',
                'hinh_anh' => 'products/TraXoai.webp',
                'ma_danh_muc' => 7, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //14
            [
                'ma_san_pham' => 'SP00000014',
                'ten_san_pham' => 'Trà Táo',
                'gia' => 30000,
                'slug' => Str::slug('Trà Táo'),
                'thu_tu' => 13,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Trà Táo',
                'hinh_anh' => 'products/TraTao.webp',
                'ma_danh_muc' => 7, 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ];
        DB::table('san_phams')->insert($sanPhams);
    }
}
