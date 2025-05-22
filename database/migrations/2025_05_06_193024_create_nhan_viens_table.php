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
        Schema::create('nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->char('ma_nhan_vien',10)->unique(); //NV00000001
            $table->unsignedBigInteger('ma_chuc_vu');
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->char('ma_cua_hang',10); // Cửa hàng mà nhân viên làm việc
            $table->string('ho_ten_nhan_vien', 255);
            $table->date('ngay_sinh')->nullable();
            $table->integer('gioi_tinh')->nullable();
            $table->char('so_dien_thoai', 10)->nullable();
            $table->string('dia_chi', 255)->nullable();
            $table->integer('ca_lam')->nullable(); // 1: ca sáng, 0: ca tối, 2 full ca
             $table->integer('trang_thai')->default(0);// 0 là hoạt động , 1 là không hoạt động
            $table->timestamps();

            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoans')->onDelete('cascade');
            $table->foreign('ma_chuc_vu')->references('ma_chuc_vu')->on('chuc_vus')->onDelete('cascade');
            $table->foreign('ma_cua_hang')->references('ma_cua_hang')->on('cua_hangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};
