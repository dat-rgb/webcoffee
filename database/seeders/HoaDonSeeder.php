<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoaDonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hoa_dons')->insert([
            [
                'id' => 1,
                'ma_hoa_don' => 'HD2506200942013109',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => '378 Lê Văn Lương, Phường Tân Hưng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 25000,
                'tien_ship' => 30000,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 55000,
                'phuong_thuc_nhan_hang' => 'delivery',
                'phuong_thuc_thanh_toan' => 'NAPAS247',
                'ghi_chu' => null,
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 0,
                'token_bao_mat' => 'aLnpCvzxYlpQzXnkB6ePOoynkbape2eO',
                'ngay_lap_hoa_don' => '2025-06-20 09:42:01',
                'created_at' => '2025-06-20 09:42:01',
                'updated_at' => '2025-06-20 10:55:12',
            ],
            [
                'id' => 2,
                'ma_hoa_don' => 'HD2506201054100848',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => '378 Lê Văn Lương, Phường Tân Hưng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 35000,
                'tien_ship' => 30000,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 65000,
                'phuong_thuc_nhan_hang' => 'delivery',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => '',
                'trang_thai_thanh_toan' => 0,
                'trang_thai' => 0,
                'token_bao_mat' => '1RjrivYBFbSdMQ0cZO8YmhXFq6vUj6N8',
                'ngay_lap_hoa_don' => '2025-06-20 10:54:10',
                'created_at' => '2025-06-20 10:54:10',
                'updated_at' => '2025-06-20 10:54:28',
            ],
        ]);
    }
}
