<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'phi_ship' => 30000,
            'so_luong_toi_thieu' => 1,
            'so_luong_toi_da' => 10,
            'ban_kinh_giao_hang' => 3,
            'ban_kinh_hien_thi_cua_hang' => 3,
            'vat_mac_dinh' => 10,
            'che_do_bao_tri' => false,
            'ty_le_diem_thuong' => 1000,
            'nguong_mien_phi_ship' => 200000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
