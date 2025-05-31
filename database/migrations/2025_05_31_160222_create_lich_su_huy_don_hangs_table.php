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
        Schema::create('lich_su_huy_don_hangs', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hoa_don',50);
            $table->char('ma_nhan_vien',10)->nullable();
            $table->char('ma_khach_hang',10)->nullable();
            $table->string('ly_do_huy',255);
            $table->dateTime('thoi_gian_huy');
            $table->timestamps();

            $table->foreign('ma_khach_hang')->references('ma_khach_hang')->on('khach_hangs')->onDelete('cascade');
            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_dons')->onDelete('cascade');
            $table->foreign('ma_nhan_vien')->references('ma_nhan_vien')->on('nhan_viens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_huy_don_hangs');
    }
};
