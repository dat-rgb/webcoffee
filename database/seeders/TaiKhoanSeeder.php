<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TaiKhoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taiKhoan = [
            //Guest: 1
            ['mail' => '', 'mat_khau' => '', 'loai_tai_khoan' => 0, 'trang_thai' => 1], // Guest
            
            // Admin
            ['mail' => 'admin@example.com', 'mat_khau' => Hash::make('admin123'), 'loai_tai_khoan' => 1, 'trang_thai' => 1],

            // Nhân viên 
            ['mail' => 'nv3@example.com', 'mat_khau' => Hash::make('nvbh123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv4@example.com', 'mat_khau' => Hash::make('nvbh123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv5@example.com', 'mat_khau' => Hash::make('nvbh123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv6@example.com', 'mat_khau' => Hash::make('nvkho123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv7@example.com', 'mat_khau' => Hash::make('nvkho123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv8@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv9@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv10@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv11@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv12@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['mail' => 'nv13@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            
            // Khách Hàng
            ['mail' => 'kh1@example.com', 'mat_khau' => Hash::make('kh123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['mail' => 'kh2@example.com', 'mat_khau' => Hash::make('kh123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['mail' => 'kh3@example.com', 'mat_khau' => Hash::make('kh123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['mail' => 'kh4@example.com', 'mat_khau' => Hash::make('kh123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['mail' => 'kh5@example.com', 'mat_khau' => Hash::make('kh123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],

            ['mail' => 'nv14@example.com', 'mat_khau' => Hash::make('nvpv123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],

        ];
        DB::table('tai_khoans')->insert($taiKhoan);
    }
}
