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
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->id();
            $table->char('ma_khach_hang', 10)->unique(); //chuỗi số nguyên tạo tự động.
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->string('ho_ten_khach_hang', 255);
            $table->date('ngay_sinh')->nullable();
            $table->integer('gioi_tinh')->nullable();
            $table->char('so_dien_thoai', 10)->nullable();
            $table->integer('diem_thanh_vien')->nullable()->default(0);
            $table->enum('hang_thanh_vien', ['Vàng', 'Bạc', 'Đồng'])->nullable()->default('Đồng');
            $table->timestamps();

            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hangs');
    }
};
