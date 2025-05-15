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
        Schema::create('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->string('ma_hoa_don',50);
            $table->char('ma_san_pham',10);
            $table->string('ten_san_pham',255);
            $table->integer('so_luong');
            $table->float('don_gia');
            $table->float('thanh_tien');
            $table->string('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_dons')->onDelete('cascade');
            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_phams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hoa_dons');
    }
};
