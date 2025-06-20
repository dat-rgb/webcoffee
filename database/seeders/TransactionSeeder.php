<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transactions')->insert([
            [
                'id' => 1,
                'ma_hoa_don' => 'HD2506200942013109',
                'tong_tien' => 55000,
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => '378 Lê Văn Lương, Phường Tân Hưng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'items_json' => json_encode([
                    "SP00000002_1" => [
                        "product_id" => "SP00000002",
                        "product_name" => "Cà Phê Đen Đá",
                        "product_loai" => 0,
                        "product_price" => 25000,
                        "product_quantity" => "1",
                        "product_image" => "products/cafe_den_da.jpg",
                        "product_slug" => "ca-phe-den-da",
                        "size_id" => "1",
                        "size_price" => 0,
                        "size_name" => "Nhỏ",
                        "money" => 25000
                    ]
                ], JSON_UNESCAPED_UNICODE),
                'payment_link' => 'https://pay.payos.vn/web/dc552c571c4c4d419f1cda86c07984f0',
                'trang_thai' => 'REFUNDING',
                'counter_account_bank_id' => '970407',
                'counter_account_bank_name' => '',
                'counter_account_name' => 'TRAN CHI DAT',
                'counter_account_number' => '6413012004',
                'virtual_account_name' => '',
                'virtual_account_number' => '',
                'created_at' => '2025-06-20 09:42:02',
                'updated_at' => '2025-06-20 10:55:12',
            ]
        ]);
    }
}
