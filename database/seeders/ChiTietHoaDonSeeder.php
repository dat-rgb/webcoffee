<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietHoaDonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chi_tiet_hoa_dons')->insert([
            [
                'ma_hoa_don' => 'HD2506250723235585',
                'ma_san_pham' => 'SP00000015',
                'ten_san_pham' => 'Cà Phê Hòa Tan 3 trong 1 (50 gói)',
                'ten_size' => null,
                'gia_size' => 0,
                'so_luong' => 1,
                'don_gia' => 179000,
                'thanh_tien' => 179000,
                'ghi_chu' => null,
                'created_at' => '2025-06-25 07:23:23',
                'updated_at' => '2025-06-25 07:23:23',
            ],
            [
                'ma_hoa_don' => 'HD2506250743237128',
                'ma_san_pham' => 'SP00000015',
                'ten_san_pham' => 'Cà Phê Hòa Tan 3 trong 1 (50 gói)',
                'ten_size' => null,
                'gia_size' => 0,
                'so_luong' => 1,
                'don_gia' => 179000,
                'thanh_tien' => 179000,
                'ghi_chu' => null,
                'created_at' => '2025-06-25 07:43:23',
                'updated_at' => '2025-06-25 07:43:23',
            ],
            [
                'ma_hoa_don' => 'HD2506250800001554',
                'ma_san_pham' => 'SP00000001',
                'ten_san_pham' => 'Cà phê sữa Đá',
                'ten_size' => 'Nhỏ',
                'gia_size' => 0,
                'so_luong' => 1,
                'don_gia' => 30000,
                'thanh_tien' => 30000,
                'ghi_chu' => null,
                'created_at' => '2025-06-25 08:00:00',
                'updated_at' => '2025-06-25 08:00:00',
            ],
            [
                'ma_hoa_don' => 'HD2506251319508503',
                'ma_san_pham' => 'SP00000001',
                'ten_san_pham' => 'Cà phê sữa Đá',
                'ten_size' => 'Lớn',
                'gia_size' => 10000,
                'so_luong' => 4,
                'don_gia' => 30000,
                'thanh_tien' => 160000,
                'ghi_chu' => null,
                'created_at' => '2025-06-25 13:19:50',
                'updated_at' => '2025-06-25 13:19:50',
            ],
            [
                'ma_hoa_don' => 'HD2506251322407900',
                'ma_san_pham' => 'SP00000001',
                'ten_san_pham' => 'Cà phê sữa Đá',
                'ten_size' => 'Lớn',
                'gia_size' => 10000,
                'so_luong' => 10,
                'don_gia' => 30000,
                'thanh_tien' => 400000,
                'ghi_chu' => null,
                'created_at' => '2025-06-25 13:22:40',
                'updated_at' => '2025-06-25 13:22:40',
            ],
            [
                'ma_hoa_don' => 'HD2506251325267494',
                'ma_san_pham' => 'SP00000001',
                'ten_san_pham' => 'Cà phê sữa Đá',
                'ten_size' => 'Lớn',
                'gia_size' => 10000,
                'so_luong' => 10,
                'don_gia' => 30000,
                'thanh_tien' => 400000,
                'ghi_chu' => null,
                'created_at' => '2025-06-25 13:25:26',
                'updated_at' => '2025-06-25 13:25:26',
            ],
        ]);
    }
}
