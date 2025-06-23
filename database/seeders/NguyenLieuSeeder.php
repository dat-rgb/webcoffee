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
                'don_vi' => 'g/túi',
                'is_ban_duoc' => 1,
            ],
            //2
            [
                'ma_nguyen_lieu' => 'NL00000002',
                'ten_nguyen_lieu' => 'Sữa Đặc Ngôi Sao Phương Nam Nhãn xanh lá 380g',
                'slug' => Str::slug('Sữa Đặc Ngôi Sao Phương Nam Nhãn xanh lá 380g'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 380,
                'gia'=> 20088, 
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/hộp',
                'is_ban_duoc' => 0,
            ],
            //3
            [
                'ma_nguyen_lieu' => 'NL00000003',
                'ten_nguyen_lieu' => 'Sữa tươi tiệt trùng 100% không đường 1L',
                'slug' => Str::slug('Sữa tươi tiệt trùng 100% không đường 1L'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 1000,
                'gia'=> 37638,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/hộp',
                'is_ban_duoc' => 0,
            ],
            //4
            [
                'ma_nguyen_lieu' => 'NL00000004',
                'ten_nguyen_lieu' => 'Sữa tươi tiệt trùng có đường 1L',
                'slug' => Str::slug('Sữa tươi tiệt trùng có đường 1L'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 1000,
                'gia'=> 37638,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/hộp',
                'is_ban_duoc' => 0,
            ],
            //5
            [
                'ma_nguyen_lieu' => 'NL00000005',
                'ten_nguyen_lieu' => 'Sữa tươi tiệt trùng ít đường 1L',
                'slug' => Str::slug('Sữa tươi tiệt trùng ít đường 1L'),
                'ma_nha_cung_cap' => 2,
                'so_luong' => 1000,
                'gia'=> 37638,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/hộp',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'g/chai',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'g/túi',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'g/hộp',
                'is_ban_duoc' => 0,
            ],
            //9
            [
                'ma_nguyen_lieu' => 'NL00000009',
                'ten_nguyen_lieu' => 'TRÀ ĐEN PHÚC LONG 500G',
                'slug' => Str::slug('TRÀ ĐEN PHÚC LONG 500G'),
                'ma_nha_cung_cap' => 5,
                'so_luong' => 500,
                'gia'=> 105000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/túi',
                'is_ban_duoc' => 1,
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
                'don_vi' => 'g/hộp',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'g/hộp',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'ml/chai',
                'is_ban_duoc' => 0,
            ],
            //13 SIRO MAULIN XOÀI
            [
                'ma_nguyen_lieu' => 'NL00000013',
                'ten_nguyen_lieu' => 'SIRO MAULIN XOÀI',
                'slug' => Str::slug('SIRO MAULIN XOÀI'),
                'ma_nha_cung_cap' => 3,
                'so_luong' => 1300,
                'gia'=> 205000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'ml/chai',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'ly/thùng',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'ly/thùng',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'ly/thùng',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'ly/thùng',
                'is_ban_duoc' => 0,
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
                'don_vi' => 'g/gói',
                'is_ban_duoc' => 0,
            ],
            //19 Cà Phê Hòa Tan 3 trong 1 (50 gói)
            [
                'ma_nguyen_lieu' => 'NL00000019',
                'ten_nguyen_lieu' => 'Cà Phê Hòa Tan 3 trong 1 (50 gói)',
                'slug' => Str::slug('Cà Phê Hòa Tan 3 trong 1 (50 gói)'),
                'ma_nha_cung_cap' => 4,
                'so_luong' => 50,
                'gia'=> 179000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'gói/túi',
                'is_ban_duoc' => 1,
            ],
            //20 Cà Phê Hòa Tan 3 trong 1 (20 gói)
            [
                'ma_nguyen_lieu' => 'NL00000020',
                'ten_nguyen_lieu' => 'Cà Phê Hòa Tan 3 trong 1 (20 gói)',
                'slug' => Str::slug('Cà Phê Hòa Tan 3 trong 1 (20 gói)'),
                'ma_nha_cung_cap' => 4,
                'so_luong' => 20,
                'gia'=> 75000,
                'loai_nguyen_lieu' => 2,
                'don_vi' => 'gói/hộp',
                'is_ban_duoc' => 1,
            ],
            //21 Trà lài hộp giấy 150g
            [
                'ma_nguyen_lieu' => 'NL00000021',
                'ten_nguyen_lieu' => 'Trà lài hộp giấy 150g',
                'slug' => Str::slug('Trà lài hộp giấy 150g'),
                'ma_nha_cung_cap' => 5,
                'so_luong' => 150,
                'gia'=> 75000,
                'loai_nguyen_lieu' => 0,
                'don_vi' => 'g/hộp',
                'is_ban_duoc' => 1,
            ],
            //22
            //23
            //24
            //25
        ];
        DB::table('nguyen_lieus')->insert($nguyen_lieus);
    }
}
