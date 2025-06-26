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
        Schema::create('cua_hang_nguyen_lieus', function (Blueprint $table) {
            $table->char('ma_cua_hang',10);
            $table->char('ma_nguyen_lieu',10);
            $table->float('so_luong_ton')->default(0); // Số lượng còn lại || tính theo g,ml,cái,...
            $table->float('so_luong_ton_min')->default(0); // Số lượng tối thiểu trong kho
            $table->float('so_luong_ton_max')->default(0); // Số lượng tối đa trong kho
            $table->string('don_vi', 50); // 500ml/chai || 100ly/thùng
            $table->integer('trang_thai')->default(1);
            $table->decimal('gia_nhap', 15, 2)->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('ma_cua_hang')->references('ma_cua_hang')->on('cua_hangs')->onDelete('cascade');
            $table->foreign('ma_nguyen_lieu')->references('ma_nguyen_lieu')->on('nguyen_lieus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cua_hang_nguyen_lieus');
    }
};
