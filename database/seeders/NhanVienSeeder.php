<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nhanViens = [
            // 2 nhân viên quản lý cửa (chức vụ = 1)
            [    //CH1
                'ma_nhan_vien' => 'NV00000003',
                'ma_tai_khoan' => 3, 
                'ma_chuc_vu' => 1, 
                'ma_cua_hang' => 'CH00000001', 
                'ho_ten_nhan_vien' => 'Nguyễn Văn An', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 7, TP Hồ Chí Minh', 
                'ca_lam' => 2
            ],
            [
                //CH2
                'ma_nhan_vien' => 'NV00000004',
                'ma_tai_khoan' => 4, 
                'ma_chuc_vu' => 1, 
                'ma_cua_hang' => 'CH00000002', 
                'ho_ten_nhan_vien' => 'Trần Thị B', 
                'gioi_tinh' => 0, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 3, TP Hồ Chí Minh', 
                'ca_lam' => 2
            ],
            // 4 nhân viên bán hàng cho 2 cửa hàng (chức vụ = 3)
            // 2 NV BH CH1
            [
                'ma_nhan_vien' => 'NV00000005',
                'ma_tai_khoan' => 5, 
                'ma_chuc_vu' => 3, 
                'ma_cua_hang' => 'CH00000001', 
                'ho_ten_nhan_vien' => 'Phạm Anh Khôi', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận Bình Thạnh, TP Hồ Chí Minh', 
                'ca_lam' => 1
            ],
            [
                'ma_nhan_vien' => 'NV00000006',
                'ma_tai_khoan' => 6, 
                'ma_chuc_vu' => 3, 
                'ma_cua_hang' => 'CH00000001', 
                'ho_ten_nhan_vien' => 'Trần Ngọc Anh', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 7, TP Hồ Chí Minh', 
                'ca_lam' => 0
            ],
            // 2 NV BH CH2
            [
                'ma_nhan_vien' => 'NV00000007',
                'ma_tai_khoan' => 7, 
                'ma_chuc_vu' => 3, 
                'ma_cua_hang' => 'CH00000002', 
                'ho_ten_nhan_vien' => 'Nguyễn Thanh Tâm', 
                'gioi_tinh' => 0, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 7, TP Hồ Chí Minh', 
                'ca_lam' => 1
            ],
            [
                'ma_nhan_vien' => 'NV00000008',
                'ma_tai_khoan' => 8, 
                'ma_chuc_vu' => 3, 
                'ma_cua_hang' => 'CH00000002', 
                'ho_ten_nhan_vien' => 'Nguyễn Trọng Tấn', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 7, TP Hồ Chí Minh', 
                'ca_lam' => 0
            ],
            // 2 nhân viên kho (chức vụ = 4)
            [
                'ma_nhan_vien' => 'NV00000009',   
                'ma_tai_khoan' => 9, 
                'ma_chuc_vu' => 4,
                'ma_cua_hang' => 'CH00000001', 
                'ho_ten_nhan_vien' => 'Lê Văn Cường', 
                'gioi_tinh' => 1, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 7, TP Hồ Chí Minh', 
                'ca_lam' => 2
            ],
            [
                'ma_nhan_vien' => 'NV00000010',   
                'ma_tai_khoan' => 10, 
                'ma_chuc_vu' => 4,
                'ma_cua_hang' => 'CH00000002', 
                'ho_ten_nhan_vien' => 'Huỳnh Ngọc Trân', 
                'gioi_tinh' => 0, 
                'so_dien_thoai' => '',
                'dia_chi' => 'Quận 8, TP Hồ Chí Minh', 
                'ca_lam' => 2
            ],

            // 4 nhân viên phục vụ (chức vụ = 2)
            // 2 NV PV CH1
            [
                'ma_nhan_vien' => 'NV00000011',  
                'ma_tai_khoan' => 11, 
                'ma_chuc_vu' => 2, 
                'ma_cua_hang' => 'CH00000001', 
                'ho_ten_nhan_vien' => 'Hoàng Văn Trọng', 
                'gioi_tinh' => 1,
                'so_dien_thoai' => '', 
                'dia_chi' => 'Quận Phú Nhuận, TP Hồ Chí Minh', 
                'ca_lam' => 1
            ],
            [
                'ma_nhan_vien' => 'NV00000012',  
                'ma_tai_khoan' => 12, 
                'ma_chuc_vu' => 2, 
                'ma_cua_hang' => 'CH00000001', 
                'ho_ten_nhan_vien' => 'Nguyễn Ngọc Khả Như', 
                'gioi_tinh' => 0,
                'so_dien_thoai' => '', 
                'dia_chi' => 'Quận 5, TP Hồ Chí Minh', 
                'ca_lam' => 0
            ],
            // 2 NV PV CH2
            [
                'ma_nhan_vien' => 'NV00000013',  
                'ma_tai_khoan' => 13, 
                'ma_chuc_vu' => 2, 
                'ma_cua_hang' => 'CH00000002', 
                'ho_ten_nhan_vien' => 'Lưu Gia Hân', 
                'gioi_tinh' => 0,
                'so_dien_thoai' => '', 
                'dia_chi' => 'Quận 10, TP Hồ Chí Minh', 
                'ca_lam' => 1
            ],
            [
                'ma_nhan_vien' => 'NV00000014',  
                'ma_tai_khoan' => 19, 
                'ma_chuc_vu' => 2, 
                'ma_cua_hang' => 'CH00000002', 
                'ho_ten_nhan_vien' => 'Huỳnh Tuấn Kiệt', 
                'gioi_tinh' => 1,
                'so_dien_thoai' => '', 
                'dia_chi' => 'Quận 7, TP Hồ Chí Minh', 
                'ca_lam' => 2
            ],
        ];

        DB::table('nhan_viens')->insert($nhanViens);
    }
}
