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
            'ban_do'          => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3919.8705307881387!2d106.7121313!3d10.7444603!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fd3fbe69ced%3A0x8e205ad3d194976c!2sThom%20House!5e0!3m2!1sen!2s!4v1749789538845!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
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
