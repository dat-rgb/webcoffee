<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanPhamCuaHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $ma_cua_hang = 'CH00000001';
        
        for ($i = 1; $i <= 18; $i++) {
            $ma_san_pham = 'SP' . str_pad($i, 8, '0', STR_PAD_LEFT);

            DB::table('san_pham_cua_hangs')->insert([
                'ma_san_pham' => $ma_san_pham,
                'ma_cua_hang' => $ma_cua_hang,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
