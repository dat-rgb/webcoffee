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
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();
            $table->char('ma_san_pham',10)->unique();
            $table->string('ten_san_pham', 100);
            $table->float('gia')->default(0);
            $table->string('slug', 150)->unique();
            $table->integer('thu_tu')->default(0); 
            $table->tinyInteger('hot')->default(0); // 1: hot, 0: không hot (nổi bật)
            $table->tinyInteger('is_new')->default(0);
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->integer('luot_xem')->default(0);
            $table->float('rating')->default(0);
            $table->unsignedBigInteger('ma_danh_muc'); 
            $table->tinyInteger('trang_thai')->default(1); // 1: Đang bán, 0: Ngừng bán
            $table->timestamps();

            $table->foreign('ma_danh_muc')->references('ma_danh_muc')
                ->on('danh_muc_san_phams')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};
