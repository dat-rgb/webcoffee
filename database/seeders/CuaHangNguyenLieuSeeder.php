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
            //1
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000001', //Cà phê rang ARABICA gói 1kg
                'so_luong_ton' => 0, // 5 túi
                'so_luong_ton_min' => 2000, //2 túi
                'so_luong_ton_max' => 10000, // 10 túi
                'don_vi' => 'g',
            ],
            //2
            [
                 'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000002', //Sửa đặc 380g
                'so_luong_ton' => 1900, // 5 túi
                'so_luong_ton_min' => 760, // tối thiểu 2 hộp
                'so_luong_ton_max' => 3800, // tối đa 10 hộp
                'don_vi' => 'g',
            ],
            //3
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000003', // Sữa tươi tiệt trùng 100% không đường 1L
                'so_luong_ton' => 5000, //5 túi
                'so_luong_ton_min' => 2000, //2 túi
                'so_luong_ton_max' => 10000, //10 túi
                'don_vi' => 'ml',
            ],
            //4
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000004', // Sữa tươi tiệt trùng có đường 1L
                'so_luong_ton' => 5000, //5 túi
                'so_luong_ton_min' => 2000, //2 túi
                'so_luong_ton_max' => 10000, //10 túi
                'don_vi' => 'ml',
            ],
            //5
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000005', // Sữa tươi tiệt trùng ít đường 1L
                'so_luong_ton' => 10000,   //10 túi
                'so_luong_ton_min' => 2000, //2 túi
                'so_luong_ton_max' => 15000, //15 túi
                'don_vi' => 'ml',
            ],
            //6
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000006', // NƯỚC ĐƯỜNG BẮP GLOFOOD ĐƯỜNG BẮP 6KG
                'so_luong_ton' => 30000, // 5 can 6kg = 6000g
                'so_luong_ton_min' => 6000, // 1 can 6kg = 6000g
                'so_luong_ton_max' => 60000, // 10 can 6kg = 6000g
                'don_vi' => 'g',
            ],
            //7
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000007', // BỘT BÉO B ONE 1KG
                'so_luong_ton' => 5000, // 5 túi
                'so_luong_ton_min' => 2000, //2 túi
                'so_luong_ton_max' => 10000, // 10 túi
                'don_vi' => 'g',
            ],
            //8
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000008', // KEM TƯƠI TOPPING BASE RICH'S - HỘP CAO 907G
                'so_luong_ton' => 4535, //5 túi
                'so_luong_ton_min' => 1812, //2 túi
                'so_luong_ton_max' => 9070, //10 túi
                'don_vi' => 'g',
            ],
            //9
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000009', // TRÀ ĐEN PHÚC LONG 500G
                'so_luong_ton' => 2500, // 5 túi
                'so_luong_ton_min' => 1000, //2 túi
                'so_luong_ton_max' => 50000, //10 túi
                'don_vi' => 'g',
            ],
            //10
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000010', // ĐÀO NGÂM RHODES 480g
                'so_luong_ton' => 2400, // 5 túi
                'so_luong_ton_min' => 960, //2 túi
                'so_luong_ton_max' => 48000, //10 túi
                'don_vi' => 'g',
            ],
            //11
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000011', // VẢI NGÂM THÁI 230g
                'so_luong_ton' => 1150, //5
                'so_luong_ton_min' => 460, //2
                'so_luong_ton_max' => 2300, //10
                'don_vi' => 'g',
            ],
            //12
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000012', // SIRO MAULIN TÁO (APPLE) 2250g
                'so_luong_ton' => 11250,
                'so_luong_ton_min' => 45000,
                'so_luong_ton_max' => 225000,
                'don_vi' => 'g',
            ],
            //13
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000013', // SIRO MAULIN XOÀI 1300g
                'so_luong_ton' => 65000,
                'so_luong_ton_min' => 2600,
                'so_luong_ton_max' => 13000,
                'don_vi' => 'g',
            ],
            //14 LY NHỰA PET NẮP CẦU 350ML/ THÙNG (1000 LY)	1.020.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000014',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 10000,
                'don_vi' => 'ly',
            ],
            //15 LY NHỰA PET NẮP CẦU 500ML/ THÙNG (1000 LY)	1.070.000 ₫	Thùng 1000ly
            [
                 'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000015',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 10000,
                'don_vi' => 'ly',
            ],
            //16 LY NHỰA PET NẮP CẦU 700ML/ THÙNG (1000 LY)	1.400.000 ₫	Thùng 1000ly
            [
                 'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000016',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 10000,
                'don_vi' => 'ly',
            ],
            //17 Ly giấy 12oz - 360ml	753.000 ₫	Thùng 1000ly
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000017',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 10000,
                'don_vi' => 'ly',
            ],
            //18 Cà phê rang PHA MÁY gói 1000G 430.000 đ
            [
                'ma_cua_hang' =>'CH00000001',
                'ma_nguyen_lieu' => 'NL00000018',
                'so_luong_ton' => 5000,
                'so_luong_ton_min' => 2000,
                'so_luong_ton_max' => 10000,
                'don_vi' => 'g',
            ],
            /////
        ];

        DB::table('cua_hang_nguyen_lieus')->insert($cua_hang_nguyen_lieus);
    }
}
