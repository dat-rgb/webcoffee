<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de')->nullable();            
            $table->text('noi_dung')->nullable();             
            $table->string('hinh_anh')->nullable();            
            $table->string('link_dich')->nullable();    
            $table->enum('trang_hien_thi', [
                'trang_chu', 'trang_san_pham', 'trang_bai_viet', 'trang_gio_hang', 'trang_lien_he'
            ])->default('trang_chu'); // Trang gốc mà banner này hiển thị

            $table->enum('vi_tri', [
                'top_banner',           // Banner đầu trang
                'main_slider',          // Slide giữa trang
                'about_section_bg',     // Background section giới thiệu
                'store_gallery',        // Hình ảnh cửa hàng (cuộn dưới chân trang)
                'popup'                 // Popup thông báo
            ])->default('top_banner');

            $table->integer('thu_tu')->default(0);            
            $table->tinyInteger('trang_thai')->default(0);      

            $table->timestamps();                          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
