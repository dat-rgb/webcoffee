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
        //Quận 7
        DB::table('cua_hangs')->insert([
            'ma_cua_hang' => 'CH00000001',
            'ten_cua_hang' => 'CDMT Coffee & Tea Quận 7',
            'slug' => 'cdmt-coffee-tea-quan-7',
            'dia_chi' => '72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh',
            'gio_mo_cua' => '07:00:00',
            'gio_dong_cua' => '23:00:00',
            'ma_tinh' => '79',     
            'ma_quan' => '769',     
            'ma_xa' => '26878',  
            'so_dien_thoai' => '0901318766',
            'email' => 'q7@cdmtcoffeetea.com',
            'latitude' => 10.7394546,
            'longitude' => 106.6932548,
            'trang_thai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Quận 1
        DB::table('cua_hangs')->insert([
            'ma_cua_hang'   => 'CH00000002',
            'ten_cua_hang'  => 'CDMT Coffee & Tea Quận 1',
            'slug' => 'cdmt-coffee-tea-quan-1',
            'dia_chi'       => '65, Huỳnh Thúc Kháng, Bến Nghé, Quận 1, TP.HCM',
            'gio_mo_cua'    => '07:00:00',
            'gio_dong_cua'  => '23:00:00',
            'ma_tinh'       => '79',     
            'ma_quan'       => '760',   
            'ma_xa'         => '26734',  
            'so_dien_thoai' => '0901234567',
            'email'         => 'q1@cdmtcoffeetea.com',
            'latitude'      => 10.77143,
            'longitude'     => 106.701173,
            'trang_thai'    => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        //TP Thủ Đức
        DB::table('cua_hangs')->insert([
            'ma_cua_hang'   => 'CH00000003',
            'ten_cua_hang'  => 'CDMT Coffee & Tea TP Thủ Đức',
            'slug' => 'cdmt-coffee-tea-tp-thu-duc',
            'dia_chi'       => '22/6  Đường Số 7, khu phố 3, Phường Linh Trung TP Thủ Đức, TP Hồ Chí Minh',
            'gio_mo_cua'    => '07:00:00',
            'gio_dong_cua'  => '23:00:00',
            'ma_tinh'       => '79',     // TP HCM
            'ma_quan'       => '769',    // TP Thủ Đức (gộp từ Q2, Q9, Thủ Đức cũ)
            'ma_xa'         => '27913',  // Phường Linh Trung
            'so_dien_thoai' => '0777741839',
            'email'         => 'thuduc@cdmtcoffeetea.com', 
            'latitude'      => 10.86776,
            'longitude'     => 106.76742,
            'trang_thai'    => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Quận 4
        DB::table('cua_hangs')->insert([
            'ma_cua_hang'   => 'CH00000004',
            'ten_cua_hang'  => 'CDMT Coffee & Tea Quận 4',
            'slug' => 'cdmt-coffee-tea-quan-4',
            'dia_chi'       => '124 Đường Khánh Hội, Phường 9, Quận 4, TP Hồ Chí Minh',
            'gio_mo_cua'    => '07:00:00',
            'gio_dong_cua'  => '23:00:00',
            'ma_tinh'       => '79',   // TP.HCM
            'ma_quan'       => '773',  // Quận 4
            'ma_xa'         => '27262',// Phường 9
            'so_dien_thoai' => '0787741839',
            'email'         => 'q4@cdmtcoffeetea.com',
            'latitude'      => 10.75786,
            'longitude'     => 106.70041,
            'trang_thai'    => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        //Quận 5
        DB::table('cua_hangs')->insert([
            'ma_cua_hang'   => 'CH00000005',
            'ten_cua_hang'  => 'CDMT Coffee & Tea Quận 5',
            'slug' => 'cdmt-coffee-tea-quan-5',
            'dia_chi'       => '307, Đường Hồng Bàng, Phường 11, Quận 5, TP Hồ Chí Minh',
            'gio_mo_cua'    => '07:00:00',
            'gio_dong_cua'  => '23:00:00',
            'ma_tinh'       => '79',
            'ma_quan'       => '774',
            'ma_xa'         => '27328',
            'so_dien_thoai' => '0797741839',
            'email'         => 'q5@cdmtcoffeetea.com',
            'latitude'      => 10.75884,
            'longitude'     => 106.66057,
            'trang_thai'    => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
