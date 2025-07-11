<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoaDonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hoa_dons')->insert([
            //Hóa đơn tháng 1
            [
                'ma_hoa_don' => 'HD0000000001',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'test',
                'email' => 'test@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 400000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 400000,
                'phuong_thuc_nhan_hang' => 'delivery',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => 'Đây là hóa đơn để test không có chi tiết SP',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => null,
                'ngay_lap_hoa_don' => '2025-01-20 09:42:01',
                'created_at' => '2025-01-20 09:42:01',
                'updated_at' => '2025-01-20 10:55:12',
            ],
            [
                'ma_hoa_don' => 'HD0000000002',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'test',
                'email' => 'test@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 25000,
                'tien_ship' => 30000,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 55000,
                'phuong_thuc_nhan_hang' => 'delivery',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => 'Đây là hóa đơn để test không có chi tiết SP',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => null,
                'ngay_lap_hoa_don' => '2025-01-21 09:42:01',
                'created_at' => '2025-01-21 09:42:01',
                'updated_at' => '2025-01-21 10:55:12',
            ],
            //tháng 2
            [
                'ma_hoa_don' => 'HD0000000003',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'test',
                'email' => 'test@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 540000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 540000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => 'Đây là hóa đơn để test không có chi tiết SP',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => null,
                'ngay_lap_hoa_don' => '2025-02-20 09:42:02',
                'created_at' => '2025-02-20 09:42:02',
                'updated_at' => '2025-02-20 10:55:12',
            ],
            //tháng 3
            [
                'ma_hoa_don' => 'HD0000000004',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'test',
                'email' => 'test@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 1450000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 1450000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => 'Đây là hóa đơn để test không có chi tiết SP',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => null,
                'ngay_lap_hoa_don' => '2025-03-20 09:42:02',
                'created_at' => '2025-03-20 09:42:02',
                'updated_at' => '2025-03-20 10:55:12',
            ],
            //tháng 4
            [
                'ma_hoa_don' => 'HD0000000005',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'test',
                'email' => 'test@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 3040000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 3040000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => 'Đây là hóa đơn để test không có chi tiết SP',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => null,
                'ngay_lap_hoa_don' => '2025-04-20 09:42:02',
                'created_at' => '2025-04-20 09:42:02',
                'updated_at' => '2025-04-20 10:55:12',
            ],
            //tháng 5
            [
                'ma_hoa_don' => 'HD0000000006',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'test',
                'email' => 'test@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 560000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 560000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => 'Đây là hóa đơn để test không có chi tiết SP',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => null,
                'ngay_lap_hoa_don' => '2025-05-20 09:42:02',
                'created_at' => '2025-05-20 09:42:02',
                'updated_at' => '2025-05-20 10:55:12',
            ],
            ///
            [
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
            [
                
                'ma_hoa_don' => 'HD2506250723235585',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000002',
                'ma_khach_hang' => null,
                'ten_khach_hang' => 'hhhhhhhhhhhhh',
                'email' => '0306221413@caothang.edu.vn',
                'so_dien_thoai' => '0901318766',
                'dia_chi' => 'CDMT Coffee & Tea Quận 1 - 65, Huỳnh Thúc Kháng, Bến Nghé, Quận 1, TP.HCM',
                'tam_tinh' => 179000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 179000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'NAPAS247',
                'ghi_chu' => null,
                'trang_thai_thanh_toan' => 0,
                'trang_thai' => 5,
                'token_bao_mat' => 'ZJlnN8Tu1DjOfhI3CTYc7GGl8QBOkGX9',
                'ngay_lap_hoa_don' => '2025-06-25 07:23:23',
                'created_at' => '2025-06-25 07:23:23',
                'updated_at' => '2025-06-25 07:23:36',
            ],
            [
                'ma_hoa_don' => 'HD2506250743237128',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000002',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => '378 huỳnh thúc kháng, Phường Tân Kiểng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 179000,
                'tien_ship' => 30000,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 209000,
                'phuong_thuc_nhan_hang' => 'delivery',
                'phuong_thuc_thanh_toan' => 'NAPAS247',
                'ghi_chu' => null,
                'trang_thai_thanh_toan' => 3,
                'trang_thai' => 5,
                'token_bao_mat' => 'uXfbP1HKidilILhestnD8aEbl1M1FcH4',
                'ngay_lap_hoa_don' => '2025-06-25 07:43:23',
                'created_at' => '2025-06-25 07:43:23',
                'updated_at' => '2025-06-25 07:49:23',
            ],
            [
                'ma_hoa_don' => 'HD2506250800001554',
                'ma_nhan_vien' => 'NV00000004',
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000002',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'CDMT Coffee & Tea Quận 1 - 65, Huỳnh Thúc Kháng, Bến Nghé, Quận 1, TP.HCM',
                'tam_tinh' => 30000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 30000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => '',
                'trang_thai_thanh_toan' => 1,
                'trang_thai' => 4,
                'token_bao_mat' => 'tJdFRwscBAoY0QvHBABE6YIwGzkGlfFk',
                'ngay_lap_hoa_don' => '2025-06-25 08:00:00',
                'created_at' => '2025-06-25 08:00:00',
                'updated_at' => '2025-06-25 08:01:09',
            ],
            [
                'ma_hoa_don' => 'HD2506251319508503',
                'ma_nhan_vien' => 'NV00000003',
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => '72 37, Phường Tân Kiểng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam',
                'tam_tinh' => 160000,
                'tien_ship' => 30000,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 190000,
                'phuong_thuc_nhan_hang' => 'delivery',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => '',
                'trang_thai_thanh_toan' => 0,
                'trang_thai' => 5,
                'token_bao_mat' => 'qxhTxYSXoX5zy4cssfBq0rSiFgib8LEK',
                'ngay_lap_hoa_don' => '2025-06-25 13:19:50',
                'created_at' => '2025-06-25 13:19:50',
                'updated_at' => '2025-06-25 13:23:34',
            ],
            [
                'ma_hoa_don' => 'HD2506251322407900',
                'ma_nhan_vien' => 'NV00000003',
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'CDMT Coffee & Tea Quận 7 - 72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh',
                'tam_tinh' => 400000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 400000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => '',
                'trang_thai_thanh_toan' => 0,
                'trang_thai' => 5,
                'token_bao_mat' => '8idMO5bKT4meR0J6UT1A50R5uUi6xp3y',
                'ngay_lap_hoa_don' => '2025-06-25 13:22:40',
                'created_at' => '2025-06-25 13:22:40',
                'updated_at' => '2025-06-25 13:24:24',
            ],
            [
                'ma_hoa_don' => 'HD2506251325267494',
                'ma_nhan_vien' => null,
                'ma_voucher' => null,
                'ma_cua_hang' => 'CH00000001',
                'ma_khach_hang' => 'KH00000001',
                'ten_khach_hang' => 'Ngô Thành Công',
                'email' => 'trdatt3737@gmail.com',
                'so_dien_thoai' => '0123456789',
                'dia_chi' => 'CDMT Coffee & Tea Quận 7 - 72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh',
                'tam_tinh' => 400000,
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => 400000,
                'phuong_thuc_nhan_hang' => 'pickup',
                'phuong_thuc_thanh_toan' => 'COD',
                'ghi_chu' => '',
                'trang_thai_thanh_toan' => 0,
                'trang_thai' => 0,
                'token_bao_mat' => 'J49l733PMQFfaWUNfj0ZYA0LiKpNJn55',
                'ngay_lap_hoa_don' => '2025-06-25 13:25:26',
                'created_at' => '2025-06-25 13:25:26',
                'updated_at' => '2025-06-25 13:25:26',
            ],
            //
           
            [
                "ma_hoa_don" => "HD2506271212123341",
                "ma_nhan_vien" => "NV00000003",
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => null,
                "ten_khach_hang" => "Trần Chí Đạt",
                "email" => "0308221413@caothang.edu.vn",
                "so_dien_thoai" => "0901318766",
                "dia_chi" => "CDMT Coffee & Tea Quận 7 - 72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh",
                "tam_tinh" => 350000,
                "tien_ship" => 0,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 350000,
                "phuong_thuc_nhan_hang" => "pickup",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 1,
                "trang_thai" => 4,
                "token_bao_mat" => "hjPkct2xx6MznbkWIOHkxSMen5dtlCpw",
                "ngay_lap_hoa_don" => "2025-06-27 12:12:12",
                "created_at" => "2025-06-27 12:12:12",
                "updated_at" => "2025-06-27 12:12:52",
            ],
            [
                "ma_hoa_don" => "HD2506271107277542",
                "ma_nhan_vien" => "NV00000003",
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => "KH00000001",
                "ten_khach_hang" => "Ngô Thành Công",
                "email" => "trdatt3737@gmail.com",
                "so_dien_thoai" => "0123456789",
                "dia_chi" => "CDMT Coffee & Tea Quận 7 - 72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh",
                "tam_tinh" => 105000,
                "tien_ship" => 0,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 105000,
                "phuong_thuc_nhan_hang" => "pickup",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 1,
                "trang_thai" => 4,
                "token_bao_mat" => "phwN5Ku3Qwt4J2gAqu0uiANioTGcmXFG",
                "ngay_lap_hoa_don" => "2025-06-27 11:07:27",
                "created_at" => "2025-06-27 11:07:27",
                "updated_at" => "2025-06-27 12:11:01",
            ],
            [
                "ma_hoa_don" => "HD2506271214055085",
                "ma_nhan_vien" => "NV00000003",
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => null,
                "ten_khach_hang" => "Chi Dat",
                "email" => "0306221413@caothang.edu.vn",
                "so_dien_thoai" => "0901318766",
                "dia_chi" => "72 Đường 37, Phường Tân Kiểng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam",
                "tam_tinh" => 75000,
                "tien_ship" => 30000,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 105000,
                "phuong_thuc_nhan_hang" => "delivery",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 1,
                "trang_thai" => 4,
                "token_bao_mat" => "EEwBtLb0eq8Bi4wHTFmMVhLilr03UfMZ",
                "ngay_lap_hoa_don" => "2025-06-27 12:14:05",
                "created_at" => "2025-06-27 12:14:05",
                "updated_at" => "2025-06-27 12:16:31",
            ],
            [
                "ma_hoa_don" => "HD2506271215178840",
                "ma_nhan_vien" => "NV00000003",
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => "KH00000001",
                "ten_khach_hang" => "Nguyễn Katinat",
                "email" => "trdatt3737@gmail.com",
                "so_dien_thoai" => "0123456789",
                "dia_chi" => "378 Nguyễn Thị Thập, Phường Tân Quy, Quận 7, Thành phố Hồ Chí Minh, Việt Nam",
                "tam_tinh" => 805000,
                "tien_ship" => 0,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 805000,
                "phuong_thuc_nhan_hang" => "delivery",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 1,
                "trang_thai" => 4,
                "token_bao_mat" => "BjZ31p9nvL7QuwAz21lTmIiWKx8fvsH4",
                "ngay_lap_hoa_don" => "2025-06-27 12:15:17",
                "created_at" => "2025-06-27 12:15:17",
                "updated_at" => "2025-06-27 12:16:53",
            ],
            [
                "ma_hoa_don" => "HD2506271219140731",
                "ma_nhan_vien" => null,
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => null,
                "ten_khach_hang" => "Nguyễn Văn An",
                "email" => "0306221413@caothang.edu.vn",
                "so_dien_thoai" => "0901318744",
                "dia_chi" => "CDMT Coffee & Tea Quận 7 - 72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh",
                "tam_tinh" => 25000,
                "tien_ship" => 0,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 25000,
                "phuong_thuc_nhan_hang" => "pickup",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 0,
                "trang_thai" => 5,
                "token_bao_mat" => "BDpTeIUI6cWNGaZBtp6HBY0DR2ROTWP2",
                "ngay_lap_hoa_don" => "2025-06-27 12:19:14",
                "created_at" => "2025-06-27 12:19:14",
                "updated_at" => "2025-06-27 12:19:29",
            ],
            [
                "ma_hoa_don" => "HD2506271220252638",
                "ma_nhan_vien" => "NV00000003",
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => "KH00000001",
                "ten_khach_hang" => "Nguyễn Katinat",
                "email" => "trdatt3737@gmail.com",
                "so_dien_thoai" => "0123456789",
                "dia_chi" => "72 Đường 37, Phường Tân Kiểng, Quận 7, Thành phố Hồ Chí Minh, Việt Nam",
                "tam_tinh" => 35000,
                "tien_ship" => 30000,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 65000,
                "phuong_thuc_nhan_hang" => "delivery",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 1,
                "trang_thai" => 4,
                "token_bao_mat" => "sH2CX6GULjEFpm1qcP7YmAe1J2dkA3kr",
                "ngay_lap_hoa_don" => "2025-06-27 12:20:25",
                "created_at" => "2025-06-27 12:20:25",
                "updated_at" => "2025-06-27 12:21:17",
            ],
            [
                "ma_hoa_don" => "HD2506271224089092",
                "ma_nhan_vien" => "NV00000003",
                "ma_voucher" => null,
                "ma_cua_hang" => "CH00000001",
                "ma_khach_hang" => "KH00000001",
                "ten_khach_hang" => "Nguyễn Katinat",
                "email" => "trdatt3737@gmail.com",
                "so_dien_thoai" => "0123456789",
                "dia_chi" => "72 Đường 37, Phường Tân Thuận Tây, Quận 7, Thành phố Hồ Chí Minh, Việt Nam",
                "tam_tinh" => 700000,
                "tien_ship" => 0,
                "khuyen_mai" => 0,
                "giam_gia" => 0,
                "tong_tien" => 700000,
                "phuong_thuc_nhan_hang" => "delivery",
                "phuong_thuc_thanh_toan" => "COD",
                "ghi_chu" => "",
                "trang_thai_thanh_toan" => 1,
                "trang_thai" => 4,
                "token_bao_mat" => "UCcUCie8bLGl41cBFRFeuvTTI4OUi78s",
                "ngay_lap_hoa_don" => "2025-06-27 12:24:08",
                "created_at" => "2025-06-27 12:24:08",
                "updated_at" => "2025-06-27 12:24:45",
            ]
            
        ]);
    }
}
