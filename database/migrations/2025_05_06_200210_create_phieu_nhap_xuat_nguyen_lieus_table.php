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
        Schema::create('phieu_nhap_xuat_nguyen_lieus', function (Blueprint $table) {
            $table->id('ma_phieu');
            $table->char('ma_cua_hang',10);
            $table->char('ma_nguyen_lieu',10);
            $table->char('ma_nhan_vien',10); // Nhân viên thực hiện nhập/xuất
            $table->integer('loai_phieu');  // 0: Phiếu nhập, 1: phiếu xuất
            $table->string('so_lo')->nullable(); // Số lô nhập kho (nếu có)
            $table->date('ngay_san_xuat')->nullable(); // Ngày sản xuất của nguyên liệu
            $table->date('han_su_dung')->nullable(); // Ngày hết hạn
            $table->float('so_luong'); // Số lượng nhập hoặc xuất
            $table->string('don_vi', 50);
            $table->float('gia_tien')->default(0)->nullable(); // Giá nhập hoặc xuất trên mỗi đơn vị
            $table->float('tong_tien')->default(0)->nullable(); // Tổng tiền (gia_tien * so_luong)
            $table->timestamp('ngay_giao_dich')->default(DB::raw('CURRENT_TIMESTAMP')); // Ngày nhập/xuất
            $table->text('ghi_chu')->nullable(); // Ghi chú nếu có
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('ma_cua_hang')->references('ma_cua_hang')->on('cua_hangs')->onDelete('cascade');
            $table->foreign('ma_nguyen_lieu')->references('ma_nguyen_lieu')->on('nguyen_lieus')->onDelete('cascade');
            $table->foreign('ma_nhan_vien')->references('ma_nhan_vien')->on('nhan_viens')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap_xuat_nguyen_lieus');
    }
};
