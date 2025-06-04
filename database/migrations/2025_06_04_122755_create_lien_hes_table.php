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
        Schema::create('lien_hes', function (Blueprint $table) {
            $table->id();
            $table->char('ma_nhan_vien',10)->nullable();
            $table->char('ma_khach_hang',10)->nullable();
            $table->string('ho_ten',255);
            $table->char('so_dien_thoai',10);
            $table->string('email',255);
            $table->string('tieu_de',255);
            $table->text('noi_dung');
            $table->dateTime('ngay_gui');
            $table->bigInteger('trang_thai')->default(0); //0: chưa xem, 1: đã, 2: đã phản hoi6f qua email.

            $table->timestamps();

            $table->foreign('ma_khach_hang')->references('ma_khach_hang')->on('khach_hangs')->onDelete('cascade');
            $table->foreign('ma_nhan_vien')->references('ma_nhan_vien')->on('nhan_viens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_hes');
    }
};
