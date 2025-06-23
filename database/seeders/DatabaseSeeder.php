<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            BannerSeeder::class,
            CuaHangSeeder::class,
            DanhMucSanPhamSeeder::class,
            SanPhamSeeder::class,
            NhaCungCapSeeder::class,
            NguyenLieuSeeder::class,
            SizeSeeder::class,
            ChucVuSeeder::class,
            TaiKhoanSeeder::class,
            KhachHangSeeder::class,
            NhanVienSeeder::class,
            ThanhPhanSanPhamSeeder::class,
            CuaHangNguyenLieuSeeder::class,
            DanhMucBlogSeeder::class,
            BlogSeeder::class,
            SanPhamCuaHangSeeder::class,
            ThongTinWebsiteSeeder::class,
            PhieuNhapXuatNguyenLieuSeeder::class,
            //HoaDonSeeder::class,
            //TransactionSeeder::class,
        ]);
    }
}
