<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_pham_yeu_thichs', function (Blueprint $table) {
            $table->char('ma_khach_hang', 10);
            $table->char('ma_san_pham', 10);

            $table->timestamps();

            // Khóa chính hỗn hợp
            $table->primary(['ma_khach_hang', 'ma_san_pham']);

            // Khóa ngoại thủ công
            $table->foreign('ma_khach_hang')->references('ma_khach_hang')->on('khach_hangs')->onDelete('cascade');
            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_phams')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('san_pham_yeu_thichs');
    }
};
