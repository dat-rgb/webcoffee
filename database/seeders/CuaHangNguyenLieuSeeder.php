<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuaHangNguyenLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Chỉ seed dữ liệu cho cửa hàng có mã = CH00000001
        $cua_hang_nguyen_lieus = [
            //1 - CH1
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000001', //Cà phê rang ARABICA gói 1kg
                'so_luong_ton' => 5000, // 5 Gói
                'so_luong_ton_min' => 1000, //1 Gói
                'so_luong_ton_max' => 0,
                'don_vi' => 'Gói',
            ],
            //1 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000001', //Cà phê rang ARABICA gói 1kg
                'so_luong_ton' => 5000, // 5 Gói
                'so_luong_ton_min' => 1000, //1 Gói
                'so_luong_ton_max' => 0,
                'don_vi' => 'Gói',
            ],
            //2
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000002', //Sửa đặc 380g
                'so_luong_ton' => 1900, // 5 Hộp
                'so_luong_ton_min' => 380, // 1 Hộp
                'so_luong_ton_max' => 0,
                'don_vi' => 'Hộp',
            ],
            //2 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000002', //Sửa đặc 380g
                'so_luong_ton' => 1900, // 5 Hộp
                'so_luong_ton_min' => 380, // 1 Hộp
                'so_luong_ton_max' => 0,
                'don_vi' => 'Hộp',
            ],

            //3
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000003', // Sữa tươi tiệt trùng 100% không đường 1L
                'so_luong_ton' => 5000, //5 Hộp
                'so_luong_ton_min' => 1000, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],
            //3 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000003', // Sữa tươi tiệt trùng 100% không đường 1L
                'so_luong_ton' => 5000, //5 Hộp
                'so_luong_ton_min' => 1000, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],

            //4
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000004', // Sữa tươi tiệt trùng có đường 1L
                'so_luong_ton' => 5000, //5 Hộp
                'so_luong_ton_min' => 1000, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],
            //4 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000004', // Sữa tươi tiệt trùng có đường 1L
                'so_luong_ton' => 5000, //5 Hộp
                'so_luong_ton_min' => 1000, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],

            //5
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000005', // Sữa tươi tiệt trùng ít đường 1L
                'so_luong_ton' => 5000, //5 Hộp
                'so_luong_ton_min' => 1000, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],
            //5 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000005', // Sữa tươi tiệt trùng ít đường 1L
                'so_luong_ton' => 5000, //5 Hộp
                'so_luong_ton_min' => 1000, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],
            //6
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000006', // NƯỚC ĐƯỜNG BẮP GLOFOOD ĐƯỜNG BẮP 6KG
                'so_luong_ton' => 30000, // 5 chai 6kg = 6000g
                'so_luong_ton_min' => 6000, // 1 chai 6kg = 6000g
                'so_luong_ton_max' => 0, // 10 chai 6kg = 6000g
                'don_vi' => 'Chai',
            ],
            //6 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000006', // NƯỚC ĐƯỜNG BẮP GLOFOOD ĐƯỜNG BẮP 6KG
                'so_luong_ton' => 30000, // 5 chai 6kg = 6000g
                'so_luong_ton_min' => 6000, // 1 chai 6kg = 6000g
                'so_luong_ton_max' => 0, // 10 chai 6kg = 6000g
                'don_vi' => 'Chai',
            ],

            //7
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000007', // BỘT BÉO B ONE 1KG
                'so_luong_ton' => 5000, // 5 túi
                'so_luong_ton_min' => 1000, //1 túi
                'so_luong_ton_max' => 0, // 10 túi
                'don_vi' => 'Túi',
            ],
            //7 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000007', // BỘT BÉO B ONE 1KG
                'so_luong_ton' => 5000, // 5 túi
                'so_luong_ton_min' => 1000, //1 túi
                'so_luong_ton_max' => 0, // 10 túi
                'don_vi' => 'Túi',
            ],

            //8
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000008', // KEM TƯƠI TOPPING BASE RICH'S - Hộp CAO 907G
                'so_luong_ton' => 4535, //5 túi
                'so_luong_ton_min' => 907, //1 túi
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Túi',
            ],
            //8 - CH2 
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000008', // KEM TƯƠI TOPPING BASE RICH'S - Hộp CAO 907G
                'so_luong_ton' => 4535, //5 túi
                'so_luong_ton_min' => 907, //1 túi
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Túi',
            ],

            //9
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000009', // TRÀ ĐEN PHÚC LONG 500G
                'so_luong_ton' => 2500, // 5 túi
                'so_luong_ton_min' => 500, //1 túi
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Túi',
            ],
            //9 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000009', // TRÀ ĐEN PHÚC LONG 500G
                'so_luong_ton' => 2500, // 5 túi
                'so_luong_ton_min' => 500, //1 túi
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Túi',
            ],

            //10
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000010', // ĐÀO NGÂM RHODES 480g
                'so_luong_ton' => 2400, // 5 Hộp
                'so_luong_ton_min' => 480, //1 Hộp
                'so_luong_ton_max' => 0,
                'don_vi' => 'Hộp',
            ],
            //10 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000010', // ĐÀO NGÂM RHODES 480g
                'so_luong_ton' => 2400, // 5 Hộp
                'so_luong_ton_min' => 480, //1 Hộp
                'so_luong_ton_max' => 0,
                'don_vi' => 'Hộp',
            ],
            //11
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000011', // VẢI NGÂM THÁI 230g
                'so_luong_ton' => 1150, //5 Hộp
                'so_luong_ton_min' => 230, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],
            //11-CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000011', // VẢI NGÂM THÁI 230g
                'so_luong_ton' => 1150, //5 Hộp
                'so_luong_ton_min' => 230, //1 Hộp
                'so_luong_ton_max' => 0, 
                'don_vi' => 'Hộp',
            ],

            //12
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000012', // SIRO MAULIN TÁO (APPLE) 2250g
                'so_luong_ton' => 11250, //5 chai
                'so_luong_ton_min' => 2250, //1 chai
                'so_luong_ton_max' => 0,
                'don_vi' => 'Chai',
            ],
            //12 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000012', // SIRO MAULIN TÁO (APPLE) 2250g
                'so_luong_ton' => 11250, //5 chai
                'so_luong_ton_min' => 2250, //1 chai
                'so_luong_ton_max' => 0,
                'don_vi' => 'Chai',
            ],
            //13
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000013', // SIRO MAULIN XOÀI 1300g
                'so_luong_ton' => 65000, //5 chai
                'so_luong_ton_min' => 1300, //1 chai
                'so_luong_ton_max' => 0,
                'don_vi' => 'Chai',
            ],
            //13 - CH2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000013', // SIRO MAULIN XOÀI 1300g
                'so_luong_ton' => 65000, //5 chai
                'so_luong_ton_min' => 1300, //1 chai
                'so_luong_ton_max' => 0,
                'don_vi' => 'Chai',
            ],

            //14 LY NHỰA PET NẮP CẦU 350ML/ THÙNG (1000 LY)	1.020.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000014',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],
            //14 LY NHỰA PET NẮP CẦU 350ML/ THÙNG (1000 LY)	1.020.000 ₫	Thùng 1000ly - CH 2
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000014',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],

            //15 LY NHỰA PET NẮP CẦU 500ML/ THÙNG (1000 LY)	1.070.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000015',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],
            //15 - CH2 LY NHỰA PET NẮP CẦU 500ML/ THÙNG (1000 LY)	1.070.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000015',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],

            //16 LY NHỰA PET NẮP CẦU 700ML/ THÙNG (1000 LY)	1.400.000 ₫	Thùng 1000ly
            [
                 'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000016',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],
            //16 CH2 LY NHỰA PET NẮP CẦU 700ML/ THÙNG (1000 LY)	1.400.000 ₫	Thùng 1000ly
            [
                 'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000016',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],
            
            //17 Ly giấy 12oz - 360ml	753.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000017',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],
            //17 CH 2 Ly giấy 12oz - 360ml	753.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000017',
                'so_luong_ton' => 5000, //5 thùng
                'so_luong_ton_min' => 1000, //1 thùng
                'so_luong_ton_max' => 0,
                'don_vi' => 'Thùng',
            ],

            //18 Cà phê rang PHA MÁY gói 1000G 430.000 đ
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000018',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 0,
                'don_vi' => 'Gói',
            ],
            //18 CH2 Cà phê rang PHA MÁY gói 1000G 430.000 đ
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000018',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 0,
                'don_vi' => 'Gói',
            ],

            //19 Cà Phê Hòa Tan 3 trong 1 (50 gói)
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000019',
                'so_luong_ton' => 500, //10 túi
                'so_luong_ton_min' => 50, //1 túi
                'so_luong_ton_max' => 0,
                'don_vi' => 'Túi',
            ],
            //19 - CH2 - Cà Phê Hòa Tan 3 trong 1 (50 gói)
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000019',
                'so_luong_ton' => 500, //10 túi
                'so_luong_ton_min' => 50, //1 túi
                'so_luong_ton_max' => 0,
                'don_vi' => 'Túi',
            ],
            //20 
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000020',
                'so_luong_ton' => 200, //10 túi
                'so_luong_ton_min' => 20, //1 túi
                'so_luong_ton_max' => 0,
                'don_vi' => 'Túi',
            ],
            //20  Cà Phê Hòa Tan 3 trong 1 (20 gói)
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000020',
                'so_luong_ton' => 200, //10 túi
                'so_luong_ton_min' => 20, //1 túi
                'so_luong_ton_max' => 0,
                'don_vi' => 'Túi',
            ],

            //21 Trà lài hộp giấy 150g
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000021',
                'so_luong_ton' => 750, //10 túi
                'so_luong_ton_min' => 150, //1 túi
                'so_luong_ton_max' => 0,
                'don_vi' => 'Túi',
            ],
            //21 - CH2 Trà lài hộp giấy 150g
            [
                'ma_cua_hang' =>'CH00000002',
                'ma_nguyen_lieu' => 'NL00000021',
                'so_luong_ton' => 750, //10 túi
                'so_luong_ton_min' => 150, //1 túi
                'so_luong_ton_max' => 0,
                'don_vi' => 'Túi',
            ],
            /////
        ];

        DB::table('cua_hang_nguyen_lieus')->insert($cua_hang_nguyen_lieus);
    }
}
