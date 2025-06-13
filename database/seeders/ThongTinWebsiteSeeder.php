<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ThongTinWebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('thong_tin_websites')->insert([
            'ten_cong_ty'     => 'Công ty TNHH CDMT Coffee & Tea',
            'ten_website' => 'CDMT Coffee & Tea',
            'logo'            => 'website/logo.png',
            'favicon'         => 'website/favicon.png',
            'mo_ta'           => 'CDMT Coffee & Tea mang đến trải nghiệm cà phê và trà hiện đại, tươi mới. Thực đơn đa dạng, không gian đẹp, phù hợp làm việc, hẹn hò và thư giãn.',
            'tu_khoa'         => 'cà phê, trà trái cây, CDMT Coffee & Tea',
            'dia_chi'         => 'Số 72, đường 37, phương Tân Kiểng, Quận 7, TP Hồ Chí Minh',
            'so_dien_thoai'   => '0901318766',
            'email'           => 'contact@cdmtcoffeetea.com',
            'ban_do'          => '<iframe src="https://www.google.com/maps/embed?..."></iframe>',
            'facebook_url'    => 'https://facebook.com/',
            'instagram_url'    => 'https://facebook.com/',
            'zalo_url'        => 'https://zalo.me',
            'youtube_url'     => 'https://youtube.com/',
            'tiktok_url'      => 'https://tiktok.com/',
            'is_active'       => true,
            'footer_text'     => '© 2025 CDMT Coffee & Tea. All rights reserved.',
            'script_header'   => '<script>console.log("Header script");</script>',
            'script_footer'   => '<script>console.log("Footer script");</script>',
        ]);
    }
}
