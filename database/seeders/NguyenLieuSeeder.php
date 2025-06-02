<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NguyenLieuSeeder extends Seeder
{
    public function run()
    {
        $nguyen_lieus = [
            //1
            [
                'ma_nguyen_lieu' => 'NL00000001',
                'ten_nguyen_lieu' => 'Cà phê rang ARABICA gói 1000G',
                'slug' => Str::slug('Cà phê rang ARABICA gói 1000G'),
                'ma_nha_cung_cap' => 1,
                'so_luong' => 1000,
                'gia' => 455000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/túi'
            ],
            //2
            [
                'ma_nguyen_lieu' => 'NL00000002',
                'ten_nguyen_lieu' => 'Sữa Đặc Ngôi Sao Phương Nam Nhãn xanh lá 380g',
                'slug' => Str::slug('Sữa Đặc Ngôi Sao Phương Nam Nhãn xanh lá 380g'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 380,
                'gia'=> 458006,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/hộp'
            ],
            //3
            [
                'ma_nguyen_lieu' => 'NL00000003',
                'ten_nguyen_lieu' => 'Sữa tươi tiệt trùng 100% không đường 1L',
                'slug' => Str::slug('Sữa tươi tiệt trùng 100% không đường 1L'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 1000,
                'gia'=> 408240,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/hộp'
            ],
            //4
            [
                'ma_nguyen_lieu' => 'NL00000004',
                'ten_nguyen_lieu' => 'Sữa tươi tiệt trùng có đường 1L',
                'slug' => Str::slug('Sữa tươi tiệt trùng có đường 1L'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 1000,
                'gia'=> 408240,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/hộp'
            ],
            //5
            [
                'ma_nguyen_lieu' => 'NL00000005',
                'ten_nguyen_lieu' => 'Sữa tươi tiệt trùng ít đường 1L',
                'slug' => Str::slug('Sữa tươi tiệt trùng ít đường 1L'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 1000,
                'gia'=> 408262,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/hộp'
            ],
            //6
            [
                'ma_nguyen_lieu' => 'NL00000006',
                'ten_nguyen_lieu' => 'NƯỚC ĐƯỜNG BẮP GLOFOOD ĐƯỜNG BẮP 6KG',
                'slug' => Str::slug('NƯỚC ĐƯỜNG BẮP GLOFOOD ĐƯỜNG BẮP 6KG'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 6000,
                'gia'=> 145000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/chai'
            ],
            //7
            [
                'ma_nguyen_lieu' => 'NL00000007',
                'ten_nguyen_lieu' => 'BỘT BÉO B ONE 1KG',
                'slug' => Str::slug('BỘT BÉO B ONE 1KG'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1000,
                'gia'=> 65000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/túi'
            ],
            //8
            [
                'ma_nguyen_lieu' => 'NL00000008',
                'ten_nguyen_lieu' => 'KEM TƯƠI TOPPING BASE RICHS - HỘP CAO 907G',
                'slug' => Str::slug('KEM TƯƠI TOPPING BASE RICHS - HỘP CAO 907G'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 907,
                'gia'=> 80000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/hộp'
            ],
            //9
            [
                'ma_nguyen_lieu' => 'NL00000009',
                'ten_nguyen_lieu' => 'TRÀ ĐEN PHÚC LONG 500G',
                'slug' => Str::slug('TRÀ ĐEN PHÚC LONG 500G'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 500,
                'gia'=> 105000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/túi'
            ],
            //10
            [
                'ma_nguyen_lieu' => 'NL00000010',
                'ten_nguyen_lieu' => 'ĐÀO NGÂM RHODES',
                'slug' => Str::slug('ĐÀO NGÂM RHODES'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 480,
                'gia'=> 65000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/hộp'
            ],
            //11
            [
                'ma_nguyen_lieu' => 'NL00000011',
                'ten_nguyen_lieu' => 'VẢI NGÂM THÁI',
                'slug' => Str::slug('VẢI NGÂM THÁI'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 230,
                'gia'=> 51000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/hộp'
            ],
            //12
            [
                'ma_nguyen_lieu' => 'NL00000012',
                'ten_nguyen_lieu' => 'SIRO MAULIN TÁO (APPLE)',
                'slug' => Str::slug('SIRO MAULIN TÁO (APPLE)'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 2250,
                'gia'=> 200000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/chai'
            ],
            //13
            [
                'ma_nguyen_lieu' => 'NL00000013',
                'ten_nguyen_lieu' => 'SIRO MAULIN XOÀI',
                'slug' => Str::slug('SIRO MAULIN XOÀI'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1300,
                'gia'=> 205000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/chai'
            ],
            //14 LY NHỰA PET NẮP CẦU 350ML/ THÙNG (1000 LY)	1.020.000 ₫	Thùng 1000ly
            [
                'ma_nguyen_lieu' => 'NL00000014',
                'ten_nguyen_lieu' => 'LY NHỰA PET NẮP CẦU 350ML/ THÙNG (1000 LY)',
                'slug' => Str::slug('LY NHỰA PET NẮP CẦU 350ML/ THÙNG (1000 LY)'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1000,
                'gia'=> 1020000,
                'loai_nguyen_lieu' => 1,
                'don_vi' => 'ly/thùng'
            ],
            //15 LY NHỰA PET NẮP CẦU 500ML/ THÙNG (1000 LY)	1.070.000 ₫	Thùng 1000ly
            [
                'ma_nguyen_lieu' => 'NL00000015',
                'ten_nguyen_lieu' => 'LY NHỰA PET NẮP CẦU 500ML/ THÙNG (1000 LY)',
                'slug' => Str::slug('LY NHỰA PET NẮP CẦU 500ML/ THÙNG (1000 LY)'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1000,
                'gia'=> 1070000,
                'loai_nguyen_lieu' => 1,
                'don_vi' => 'ly/thùng'
            ],
            //16 LY NHỰA PET NẮP CẦU 700ML/ THÙNG (1000 LY)	1.400.000 ₫	Thùng 1000ly
            [
                'ma_nguyen_lieu' => 'NL00000016',
                'ten_nguyen_lieu' => 'LY NHỰA PET NẮP CẦU 700ML/ THÙNG (1000 LY)',
                'slug' => Str::slug('LY NHỰA PET NẮP CẦU 700ML/ THÙNG (1000 LY)'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1000,
                'gia'=> 1400000,
                'loai_nguyen_lieu' => 1,
                'don_vi' => 'ly/thùng'
            ],
            //17 Ly giấy 12oz - 360ml	753.000 ₫	Thùng 1000ly
            [
                'ma_nguyen_lieu' => 'NL00000017',
                'ten_nguyen_lieu' => 'Ly giấy 12oz - 360ml',
                'slug' => Str::slug('Ly giấy 12oz - 360ml'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1000,
                'gia'=> 753000,
                'loai_nguyen_lieu' => 1,
                'don_vi' => 'ly/thùng'
            ],
            //18 Cà phê rang PHA MÁY gói 1000G 430.000 đ
            [
                'ma_nguyen_lieu' => 'NL00000018',
                'ten_nguyen_lieu' => 'Cà phê rang PHA MÁY gói 1000G',
                'slug' => Str::slug('Cà phê rang PHA MÁY gói 1000G'),
                'ma_nha_cung_cap' => 1,
                'so_luong' => 1000,
                'gia'=> 438000,
                'loai_nguyen_lieu' => 1,
                'don_vi' => 'g/gói'
            ],
            //19
            //20
            //21
            //22
            //23
            //24
            //25
        ];
        DB::table('nguyen_lieus')->insert($nguyen_lieus);
    }
}
