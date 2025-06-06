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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->char('ma_san_pham', 10);
            $table->char('ma_khach_hang', 10);
            $table->char('ma_hoa_don', 50); 
            $table->float('rating')->check('rating >= 1 and rating <= 5');
            $table->string('danh_gia', 255)->nullable();
            $table->timestamps();

            $table->foreign('ma_khach_hang')->references('ma_khach_hang')->on('khach_hangs')->onDelete('cascade');
            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_phams')->onDelete('cascade');
            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_dons')->onDelete('cascade');

            // Optional: ràng buộc 1 sản phẩm chỉ được đánh giá 1 lần trong mỗi hóa đơn
            $table->unique(['ma_san_pham', 'ma_khach_hang', 'ma_hoa_don']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
