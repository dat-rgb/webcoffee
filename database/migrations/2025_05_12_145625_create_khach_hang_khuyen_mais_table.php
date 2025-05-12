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
        Schema::create('khach_hang_khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->char('ma_khach_hang',10);
            $table->char('ma_voucher',50);
            $table->timestamps();

            $table->foreign('ma_khach_hang')->references('ma_khach_hang')->on('khach_hangs');
            $table->foreign('ma_voucher')->references('ma_voucher')->on('khuyen_mais');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hang_khuyen_mais');
    }
};
