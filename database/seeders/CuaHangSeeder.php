<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuaHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000001',
            'ten_cua_hang' => 'CDMT Coffee & Tea',
            'dia_chi' => '72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh',
            'so_dien_thoai' => '0901318766',
            'email' => 'cdmtcoffeetea@gmail.com',
            'chi_nhanh' => 0,
            'logo' => 'logo.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000002',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 1',
            'dia_chi' => '65, Huỳnh Thúc Kháng, Bến Nghé, Quận 1, TP.HCM',
            'so_dien_thoai' => '0901234567',
            'email' => 'cdmtcoffeeteaquan1@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000003',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 2',
            'dia_chi' => 'Quận 2, TP Hồ Chí Minh',
            'so_dien_thoai' => '0777741839',
            'email' => 'cdmtcoffeeteaquan2@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000004',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 3',
            'dia_chi' => 'Quận 4, TP Hồ Chí Minh',
            'so_dien_thoai' => '0787741839',
            'email' => 'cdmtcoffeeteaquan3@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000005',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 4',
            'dia_chi' => 'Quận 4, TP Hồ Chí Minh',
            'so_dien_thoai' => '0797741839',
            'email' => 'cdmtcoffeeteaquan4@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000006',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 5',
            'dia_chi' => 'Quận 5, TP Hồ Chí Minh',
            'so_dien_thoai' => '0798741839',
            'email' => 'cdmtcoffeeteaquan5@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000007',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 6',
            'dia_chi' => 'Quận 6, TP Hồ Chí Minh',
            'so_dien_thoai' => '0799741839',
            'email' => 'cdmtcoffeeteaquan6@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000008',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 8',
            'dia_chi' => 'Quận 8, TP Hồ Chí Minh',
            'so_dien_thoai' => '0799771839',
            'email' => 'cdmtcoffeeteaquan8@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000009',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận Thủ Đức',
            'dia_chi' => 'TP Thủ Đức, TP Hồ Chí Minh',
            'so_dien_thoai' => '0799841839',
            'email' => 'cdmtcoffeeteathuduc@gmail.com',
            'trang_thai' => 1,
            'chi_nhanh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
