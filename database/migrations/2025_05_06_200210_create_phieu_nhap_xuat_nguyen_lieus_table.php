<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('phieu_nhap_xuat_nguyen_lieus', function (Blueprint $table) {
            $table->id('ma_phieu'); // Khóa chính tự tăng
            $table->char('ma_cua_hang', 10);
            $table->char('ma_nguyen_lieu', 10);
            $table->char('ma_nhan_vien', 10)->nullable();
            $table->string('so_lo')->nullable();
            $table->integer('loai_phieu'); // 0: nhập, 1: xuất
            $table->dateTime('ngay_san_xuat')->nullable();
            $table->dateTime('han_su_dung')->nullable();
            $table->float('so_luong');
            $table->float('dinh_luong');
            $table->string('don_vi', 50);
            $table->float('gia_tien')->default(0)->nullable();
            $table->float('tong_tien')->default(0)->nullable();
            $table->dateTime('ngay_tao_phieu');
            $table->text('ghi_chu')->nullable();
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
