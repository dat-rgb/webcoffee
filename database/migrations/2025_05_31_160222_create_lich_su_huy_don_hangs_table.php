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
            $table->string('nguoi_huy',255); 
            $table->string('ly_do_huy',255);
            $table->dateTime('thoi_gian_huy');
            $table->timestamps();

            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_dons')->onDelete('cascade');
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
