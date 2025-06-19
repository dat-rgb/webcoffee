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
            ['email' => 'admin@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 1, 'trang_thai' => 1],
            // Nhân viên 
            ['email' => 'nv3@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv4@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv5@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv6@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv7@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv8@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv9@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv10@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv11@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv12@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            ['email' => 'nv13@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],
            // Khách Hàng
            ['email' => 'trdatt3737@gmail.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh2@gmail.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh3@gmail.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh4@gmail.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],
            ['email' => 'kh5@gmail.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 3, 'trang_thai' => 1],

            ['email' => 'nv14@cdmtcoffeetea.com', 'mat_khau' => Hash::make('Password@123'), 'loai_tai_khoan' => 2, 'trang_thai' => 1],

        ];
        DB::table('tai_khoans')->insert($taiKhoan);
    }
}
