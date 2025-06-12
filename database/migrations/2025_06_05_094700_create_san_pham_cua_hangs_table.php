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
        Schema::create('san_pham_cua_hangs', function (Blueprint $table) {
            $table->id();
            $table->char('ma_san_pham', 10);
            $table->char('ma_cua_hang', 10);
            $table->tinyInteger('trang_thai')->default(1); // 1: Đang bán, 0: Ngừng bán
            $table->timestamps();
            $table->unique(['ma_san_pham', 'ma_cua_hang']);

            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_phams')->onDelete('cascade');
            $table->foreign('ma_cua_hang')->references('ma_cua_hang')->on('cua_hangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham_cua_hangs');
    }
};
