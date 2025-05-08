<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhachHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $khachHangs = [
            [
                'ma_khach_hang' => 'KH00000001',    
                'ma_tai_khoan' => 14, 
                'ho_ten_khach_hang' => 'Ngô Thành Công',
                'gioi_tinh' => 1,  
                'so_dien_thoai' => '0123456789',
                'diem_thanh_vien' => 100, 
                'hang_thanh_vien' => 'Đồng'
            ],
            [
                'ma_khach_hang' => 'KH00000002', 
                'ma_tai_khoan' => 15, 
                'ho_ten_khach_hang' => 'Bùi Thị K', 
                'gioi_tinh' => 0,  
                'so_dien_thoai' => '',
                'diem_thanh_vien' => 200, 
                'hang_thanh_vien' => 'Bạc'
            ],
            [
                'ma_khach_hang' => 'KH00000003', 
                'ma_tai_khoan' => 16, 
                'ho_ten_khach_hang' => 'Tô Văn L', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '0901318999',
                'diem_thanh_vien' => 300, 
                'hang_thanh_vien' => 'Vàng'
            ],
            [
                'ma_khach_hang' => 'KH00000004', 
                'ma_tai_khoan' => 17, 
                'ho_ten_khach_hang' => 'Lâm Thị M', 
                'gioi_tinh' => 0,   
                'so_dien_thoai' => '',
                'diem_thanh_vien' => 150, 
                'hang_thanh_vien' => 'Đồng'
            ],
            [
                'ma_khach_hang' => 'KH00000005', 
                'ma_tai_khoan' => 18, 
                'ho_ten_khach_hang' => 'Vương Văn N', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '0777741879',
                'diem_thanh_vien' => 250, 
                'hang_thanh_vien' => 'Bạc'
            ],
        ];

        DB::table('khach_hangs')->insert($khachHangs);
    }
}
