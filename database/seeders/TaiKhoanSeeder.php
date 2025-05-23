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
            ['email' => '', 'mat_khau' => '', 'loai_tai_khoan' => 0, 'trang_thai' => 1], // Guest
            
            // Admin
            ['email' => 'admin@example.com', 'mat_khau' => Hash::make(' Admin@123'), 'loai_tai_khoan' => 1, 'trang_thai' => 1],

            // Nhân viên 
            ['email' => 'nv3@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv4@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv5@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv6@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv7@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv8@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv9@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv10@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv11@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv12@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv13@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            
            // Khách Hàng
            ['email' => 'kh1@example.com', 'mat_khau' => Hash::make('Khach@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh2@example.com', 'mat_khau' => Hash::make('Khach@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh3@example.com', 'mat_khau' => Hash::make('Khach@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh4@example.com', 'mat_khau' => Hash::make('Khach@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh5@example.com', 'mat_khau' => Hash::make('Khach@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],

            ['email' => 'nv14@example.com', 'mat_khau' => Hash::make('NhanVien@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],

        ];
        DB::table('tai_khoans')->insert($taiKhoan);
    }
}
