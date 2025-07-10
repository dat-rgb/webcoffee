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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id('ma_blog');
            $table->unsignedBigInteger('ma_danh_muc_blog')->nullable();
            $table->string('tieu_de',255);
            $table->string('slug',255)->unique();
            $table->string('sub_tieu_de',255)->nullable();
            $table->string('hinh_anh')->nullable();
            $table->longText('noi_dung');
            $table->tinyInteger('trang_thai')->default(0);
            $table->unsignedBigInteger('luot_xem')->default(0);
            $table->unsignedBigInteger('luot_thich')->default(0);
            $table->string('tac_gia',255);
            $table->dateTime('ngay_dang');
            $table->tinyInteger('hot')->default(0); // 1: hot, 0: không hot (nổi bật)
            $table->tinyInteger('is_new')->default(0);
            $table->integer('do_uu_tien')->default(0); //0: ưu tiên, 1: ưu tiên nhất, 2: nhỏ hơn 1..
            $table->timestamps();

            $table->foreign('ma_danh_muc_blog')->references('ma_danh_muc_blog')->on('danh_muc_blogs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
