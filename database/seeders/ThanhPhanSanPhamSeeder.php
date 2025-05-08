<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThanhPhanSanPhamSeeder extends Seeder
{
    public function run()
    {
        DB::table('thanh_phan_san_phams')->truncate(); // Xóa dữ liệu cũ

        $data = [
            //1 Size: Nhỏ:1, Vừa:2, Lớn:3
            [
                'ma_san_pham' => 'SP00000001', // ID của Cà phê sữa đá
                'ma_nguyen_lieu' => 'NL00000001', // Cà phê rang ARABICA
                'ma_size' => 1,
                'dinh_luong' => 25,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000001', 
                'ma_nguyen_lieu' => 'NL00000002', // Sữa Đặc Ngôi Sao Phương Nam
                'ma_size' => 1,
                'dinh_luong' => 40,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000001', 
                'ma_nguyen_lieu' => 'NL00000014', // ly nhỏ
                'ma_size' => 1,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //1 Vừa2
            [
                'ma_san_pham' => 'SP00000001', // ID của Cà phê sữa đá
                'ma_nguyen_lieu' => 'NL00000001', // Cà phê rang ARABICA
                'ma_size' => 2,
                'dinh_luong' => 35,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000001', 
                'ma_nguyen_lieu' => 'NL00000002', // Sữa Đặc Ngôi Sao Phương Nam
                'ma_size' => 2,
                'dinh_luong' => 50,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000001', 
                'ma_nguyen_lieu' => 'NL00000015', // ly vừa
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //1 Lớn
            [
                'ma_san_pham' => 'SP00000001', // ID của Cà phê sữa đá
                'ma_nguyen_lieu' => 'NL00000001', // Cà phê rang ARABICA
                'ma_size' => 3,
                'dinh_luong' => 45,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000001', 
                'ma_nguyen_lieu' => 'NL00000002', // Sữa Đặc Ngôi Sao Phương Nam
                'ma_size' => 3,
                'dinh_luong' => 60,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000001', 
                'ma_nguyen_lieu' => 'NL00000016', // ly lớn
                'ma_size' => 3,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //2 Nhỏ:1, Vừa:2, Lớn:3
            [
                'ma_san_pham' => 'SP00000002',  // ID của sản phẩm "Cà Phê Đen Đá"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 1,
                'dinh_luong' => 25, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000002', 
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 1,
                'dinh_luong' => 20, // 20ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000002',  
                'ma_nguyen_lieu' => 'NL00000014', // ly nhỏ
                'ma_size' => 1,
                'dinh_luong' => 1,
                'don_vi' => 'g'
            ],
            //2 vừa
            [
                'ma_san_pham' => 'SP00000002',  // ID của sản phẩm "Cà Phê Đen Đá"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 2,
                'dinh_luong' => 35, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000002', 
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 2,
                'dinh_luong' => 30, // 20ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000002',  
                'ma_nguyen_lieu' => 'NL00000015', // ly vừa
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'g'
            ],
            //2 lớn
            [
                'ma_san_pham' => 'SP00000002',  // ID của sản phẩm "Cà Phê Đen Đá"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 3,
                'dinh_luong' => 45, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000002', 
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 3,
                'dinh_luong' => 40, // 20ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000002',  
                'ma_nguyen_lieu' => 'NL00000016', // ly lớn
                'ma_size' => 3,
                'dinh_luong' => 1,
                'don_vi' => 'g'
            ],
            //3 Nhỏ Vừa Lớn
            [
                'ma_san_pham' => 'SP00000003',   // ID của "Sữa Tươi Cà Phê"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 1,
                'dinh_luong' => 25, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000003',  
                'ma_nguyen_lieu' => 'NL00000003', // ID của "Sữa tươi tiệt trùng không đường"
                'ma_size' => 1,
                'dinh_luong' => 100, // 100ml sữa tươi
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000003',  
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 1,
                'dinh_luong' => 20, // 20ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000003',   
                'ma_nguyen_lieu' => 'NL00000014', // ly nhỏ
                'ma_size' => 1,
                'dinh_luong' => 1,
                'don_vi' => 'g'
            ],
            //3 vừa
            [
                'ma_san_pham' => 'SP00000003',   // ID của "Sữa Tươi Cà Phê"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 2,
                'dinh_luong' => 35, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000003',  
                'ma_nguyen_lieu' => 'NL00000003', // ID của "Sữa tươi tiệt trùng không đường"
                'ma_size' => 2,
                'dinh_luong' => 200, // 100ml sữa tươi
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000003',  
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 2,
                'dinh_luong' => 30, // 20ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000003',   
                'ma_nguyen_lieu' => 'NL00000015', // ly vừa
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'g'
            ],
            //3 lớn
            [
                'ma_san_pham' => 'SP00000003',   // ID của "Sữa Tươi Cà Phê"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 3,
                'dinh_luong' => 45, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000003',  
                'ma_nguyen_lieu' => 'NL00000003', // ID của "Sữa tươi tiệt trùng không đường"
                'ma_size' => 3,
                'dinh_luong' => 300, // 100ml sữa tươi
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000003',  
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 3,
                'dinh_luong' => 40, // 20ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000003',   
                'ma_nguyen_lieu' => 'NL00000016', // ly lớn
                'ma_size' => 3,
                'dinh_luong' => 1,
                'don_vi' => 'g'
            ],
            //4 Nhỏ
            [
                'ma_san_pham' => 'SP00000004',   // ID của "Cà Phê Sữa Nóng"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 1,
                'dinh_luong' => 25, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000004',  
                'ma_nguyen_lieu' => 'NL00000002', // ID của "Sữa đặc Ngôi Sao Phương Nam"
                'ma_size' => 1,
                'dinh_luong' => 40, // 40ml sữa đặc
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000004',  
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 1,
                'dinh_luong' => 10, // 10ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000004',  
                'ma_nguyen_lieu' => 'NL00000017',  // ly giấy
                'ma_size' => 1,
                'dinh_luong' => 1, // 1 ly
                'don_vi' => 'ly'
            ],
            //5 nhỏ
            [
                'ma_san_pham' => 'SP00000005',   // ID của "Cà Phê Đen Nóng"
                'ma_nguyen_lieu' => 'NL00000001', // ID của "Cà phê rang ARABICA"
                'ma_size' => 1,
                'dinh_luong' => 25, // 25g cà phê
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000005',  
                'ma_nguyen_lieu' => 'NL00000006', // ID của "Nước đường bắp"
                'ma_size' => 1,
                'dinh_luong' => 10, // 10ml nước đường
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000005',  
                'ma_nguyen_lieu' => 'NL00000017',  // ly giấy
                'ma_size' => 1,
                'dinh_luong' => 1, // 1 ly
                'don_vi' => 'ly'
            ],
            //6 nhỏ
            [
                'ma_san_pham' => 'SP00000006',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 1,
                'dinh_luong' => 9,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000006',  
                'ma_nguyen_lieu' => 'NL00000014',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 1,
                'dinh_luong' => 1, 
                'don_vi' => 'ly'
            ],
            //6 vừa
            [
                'ma_san_pham' => 'SP00000006',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 2,
                'dinh_luong' => 18, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000006',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //7 
            [
                'ma_san_pham' => 'SP00000007',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 2,
                'dinh_luong' => 18, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000007',  
                'ma_nguyen_lieu' => 'NL00000003',  //sửa tươi không đường
                'ma_size' => 2,
                'dinh_luong' => 200,
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000007',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //8
            [
                'ma_san_pham' => 'SP00000008',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 2,
                'dinh_luong' => 18, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000008',  
                'ma_nguyen_lieu' => 'NL00000004',  //sửa tươi có đường
                'ma_size' => 2,
                'dinh_luong' => 200,
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000008',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //9 
            [
                'ma_san_pham' => 'SP00000009',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 1,
                'dinh_luong' => 30  , 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000009',  
                'ma_nguyen_lieu' => 'NL00000004',  //sửa tươi có đường
                'ma_size' => 1,
                'dinh_luong' => 200,
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000009',  
                'ma_nguyen_lieu' => 'NL00000007',  // Bột béo
                'ma_size' => 1,
                'dinh_luong' => 10,
                'don_vi' => 'ly'
            ],
            [
                'ma_san_pham' => 'SP00000009',  
                'ma_nguyen_lieu' => 'NL00000017',  // ly giấy 360ml
                'ma_size' => 1,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            //10 nhỏ
            [
                'ma_san_pham' => 'SP00000010',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 1,
                'dinh_luong' => 9,
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000010',  
                'ma_nguyen_lieu' => 'NL00000014',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 1,
                'dinh_luong' => 1, 
                'don_vi' => 'ly'
            ],
            //10 vừa
            [
                'ma_san_pham' => 'SP00000010',  
                'ma_nguyen_lieu' => 'NL00000018',  // Cà phê rang PHA MÁY gói 1000G
                'ma_size' => 2,
                'dinh_luong' => 18, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000010',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
             // 11
             [
                'ma_san_pham' => 'SP00000011',  
                'ma_nguyen_lieu' => 'NL00000006',  // Nước đường
                'ma_size' => 2,
                'dinh_luong' => 10, 
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000011',  
                'ma_nguyen_lieu' => 'NL00000009',  // Trà đen phúc long
                'ma_size' => 2,
                'dinh_luong' => 8, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000011',  
                'ma_nguyen_lieu' => 'NL00000010',  // Đào ngâm
                'ma_size' => 2,
                'dinh_luong' => 20, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000011',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
            // 12
            [
                'ma_san_pham' => 'SP00000012',  
                'ma_nguyen_lieu' => 'NL00000006',  // Nước đường
                'ma_size' => 2,
                'dinh_luong' => 10, 
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000012',  
                'ma_nguyen_lieu' => 'NL00000009',  // Trà đen phúc long
                'ma_size' => 2,
                'dinh_luong' => 8, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000012',  
                'ma_nguyen_lieu' => 'NL00000011',  // Vải ngâm
                'ma_size' => 2,
                'dinh_luong' => 20, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000012',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
             //13
             [
                'ma_san_pham' => 'SP00000013',  
                'ma_nguyen_lieu' => 'NL00000006',  // Nước đường
                'ma_size' => 2,
                'dinh_luong' => 10, 
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000013',  
                'ma_nguyen_lieu' => 'NL00000009',  // Trà đen phúc long
                'ma_size' => 2,
                'dinh_luong' => 8, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000013',  
                'ma_nguyen_lieu' => 'NL00000013',  // SIRO MAULIN XOÀI
                'ma_size' => 2,
                'dinh_luong' => 20, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000013',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],
             //14
             [
                'ma_san_pham' => 'SP00000014',  
                'ma_nguyen_lieu' => 'NL00000006',  // Nước đường
                'ma_size' => 2,
                'dinh_luong' => 10, 
                'don_vi' => 'ml'
            ],
            [
                'ma_san_pham' => 'SP00000014',  
                'ma_nguyen_lieu' => 'NL00000009',  // Trà đen phúc long
                'ma_size' => 2,
                'dinh_luong' => 8, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000014',  
                'ma_nguyen_lieu' => 'NL00000012',  // SIRO MAULIN TÁO
                'ma_size' => 2,
                'dinh_luong' => 20, 
                'don_vi' => 'g'
            ],
            [
                'ma_san_pham' => 'SP00000014',  
                'ma_nguyen_lieu' => 'NL00000015',  // ly nhựa 500ml
                'ma_size' => 2,
                'dinh_luong' => 1,
                'don_vi' => 'ly'
            ],

        ];
        DB::table('thanh_phan_san_phams')->insert($data);
    }
}
