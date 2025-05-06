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
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->char('ma_hoa_don', 20)->unique();
            $table->char('ma_nhan_vien', 10);  
            $table->char('ma_voucher',50)->nullable();
            $table->char('ma_khach_hang', 10); 
            $table->string('ten_khach_hang', 255)->nullable();
            $table->char('so_dien_thoai', 10)->nullable();
            $table->string('dia_chi')->nullable();
            $table->float('tien_ship')->default(0);
            $table->float('khuyen_mai')->default(0);
            $table->decimal('tong_tien', 15, 2); // Sử dụng decimal thay vì float để tránh mất độ chính xác
            $table->integer('phuong_thuc_thanh_toan')->default(0); // 0: cash, 1: VNPAY,...
            $table->string('ghi_chu')->nullable();
            $table->integer('trang_thai')->default(0); // 0: đã gửi, 1: đã thanh toán || 2: chuẩn bị món, 3: giao món, 4: đã giao thành công || 5: hủy đơn,...
            $table->timestamp('ngay_lap_hoa_don')->default(DB::raw('CURRENT_TIMESTAMP')); // Sử dụng timestamp thay cho datetime
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('ma_voucher')->references('ma_voucher')->on('khuyen_mais')->onDelete('cascade');
            $table->foreign('ma_khach_hang')->references('ma_khach_hang')->on('khach_hangs')->onDelete('cascade');
            $table->foreign('ma_nhan_vien')->references('ma_nhan_vien')->on('nhan_viens')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
