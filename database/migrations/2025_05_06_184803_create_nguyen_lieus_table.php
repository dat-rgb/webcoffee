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
        Schema::create('nguyen_lieus', function (Blueprint $table) {
            $table->id();
            $table->char('ma_nguyen_lieu',10)->unique();
            $table->string('ten_nguyen_lieu', 255);
            $table->string('slug',255);
            $table->unsignedBigInteger('ma_nha_cung_cap')->nullable();
            $table->float(column: 'so_luong')->default(0);
            $table->float('gia')->default(0);
            $table->integer('loai_nguyen_lieu')->default(0); // 0: chế biến, 1: tiêu dùng: ly, muổng, ống hút, túi T, 2: Đóng gói
            $table->string('don_vi', 50); // 500ml/chai g, ml, ly,..
            $table->tinyInteger('is_ban_duoc')->default(0);
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Khóa ngoại
            $table->foreign('ma_nha_cung_cap')->references('ma_nha_cung_cap')->on('nha_cung_caps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieus');
    }
};
