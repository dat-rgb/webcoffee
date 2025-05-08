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
        Schema::create('tai_khoans', function (Blueprint $table) {
            $table->id('ma_tai_khoan');
            $table->string('email')->nullable()->unique();
            $table->string('mat_khau', 255)->nullable();
            // Login bằng QR (lưu token hoặc mã đăng nhập)
            $table->string('qr_token')->nullable()->unique();
            // Loại tài khoản
            $table->integer('loai_tai_khoan')->default(0); // 0=Guest, 1=Admin, 2=Nhân viên, 3=Khách hàng
            // Trạng thái tài khoản
            $table->integer('trang_thai')->default(0); // 0=Không hoạt động (chờ kích hoạt), 1=Hoạt động, 2=Xóa,..
            $table->string('access_token')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tai_khoans');
    }
};
