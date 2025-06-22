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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'loai_san_pham' => 0,
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
                'hinh_anh' => 'products/tra-tao.webp',
                'ma_danh_muc' => 7, 
                'trang_thai' => 1,
                'loai_san_pham' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //các phẩm tại nhà
            //Các sản phẩm thuộc mã danh mục = 8 (Cà phê tại nhà)
            //15
            [
                'ma_san_pham' => 'SP00000015',
                'ten_san_pham' => 'Cà Phê Hòa Tan 3 trong 1 (50 gói)',
                'gia' => 179000,
                'slug' => Str::slug('Cà Phê Hòa Tan 3 trong 1 (50 gói)'),
                'thu_tu' => 13,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Cà Phê Highlands Coffee 3in1 Hòa Tan túi 50 Gói x 17g Hương vị thơm ngon, đậm đà vị cà phê. Thích hợp cho uống nóng và lạnh',
                'hinh_anh' => 'products/highlands_3_trong_1_50_goi.jpg',
                'ma_danh_muc' => 8, 
                'trang_thai' => 1,
                'loai_san_pham' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //16
            [
                'ma_san_pham' => 'SP00000016',
                'ten_san_pham' => 'Cà phê hoà tan 3 trong 1 (20 gói)',
                'gia' => 75000,
                'slug' => Str::slug('Cà phê hoà tan 3 trong 1 (20 gói)'),
                'thu_tu' => 13,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Cà Phê Highlands Coffee 3in1 Hòa Tan túi 20 Gói x 17g Hương vị thơm ngon, đậm đà vị cà phê. Thích hợp cho uống nóng và lạnh',
                'hinh_anh' => 'products/highlands_3_trong_1_50_goi.jpg',
                'ma_danh_muc' => 8, 
                'trang_thai' => 1,
                'loai_san_pham' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //Các sản phẩm thuộc mã danh mục = 9 (Trà tại nhà)
            //17
            [
                'ma_san_pham' => 'SP00000017',
                'ten_san_pham' => 'Trà đen Phúc Long 500g',
                'gia' => 135000,
                'slug' => Str::slug('Trà đen Phúc Long 500g'),
                'thu_tu' => 13,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Trà đen là trà xanh khi thu hoạch và được ủ lên men sau khi phơi khô. Phản ứng lên men giúp trà đen có mùi thơm đặc biệt và lá trà có màu sậm, từ đỏ hung đến đen tuyền. Trà đen với hậu vị chát đậm, khi uống chất trà thấm vào cơ thể nhẹ nhàng đánh thức các giác quan, tạo tâm trạng sảng khoái',
                'hinh_anh' => 'products/tra_den_phuc_long.jpg',
                'ma_danh_muc' => 9, 
                'trang_thai' => 1,
                'loai_san_pham' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //18
            [
                'ma_san_pham' => 'SP00000018',
                'ten_san_pham' => 'Trà lài hộp giấy 150g',
                'gia' => 75000,
                'slug' => Str::slug('Trà lài hộp giấy 150g'),
                'thu_tu' => 13,
                'hot' => 0,
                'is_new' => 0,
                'mo_ta' => 'Trà lài là sự kết hợp hoàn hảo giữa lá trà xanh chất lượng cao và hoa lài. Trà được lên men và pha trộn với hoa lài, quá trình này được lặp lại nhiều lần giúp cho trà có mùi thơm tự nhiên của hoa lài, trà lài khi pha có màu vàng đẹp, vị ngọt của hoa lài và vị đắng nhẹ của trà tạo nên cảm giác khoan khoái và thư giãn cho người dùng.',
                'hinh_anh' => 'products/tra_lai_hop_giay.jpg',
                'ma_danh_muc' => 9, 
                'trang_thai' => 1,
                'loai_san_pham' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //18
            //19
            //...
        ];
        DB::table('san_phams')->insert($sanPhams);
    }
}
