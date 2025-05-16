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
        Schema::create('khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->char('ma_voucher',50)->unique();
            $table->string('ten_voucher',255);
            $table->string('hinh_anh',255)->nullable();
            $table->integer('so_luong')->default(0);
            $table->dateTime('ngay_bat_dau')->nullable();
            $table->dateTime('ngay_ket_thuc')->nullable();
            $table->float('dieu_kien_ap_dung')->default(0); // 
            $table->float('gia_tri_giam')->default(0); // nếu GTG > 100: [thành tiền - GTG], nếu GTG < 100: [thành tiền - thành tiền*(GTG/100)]
            $table->float('giam_gia_max')->default(0); // Giảm tối đa
            $table->integer('trang_thai')->default(0); // 1: hoạt động, 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mais');
    }
};
