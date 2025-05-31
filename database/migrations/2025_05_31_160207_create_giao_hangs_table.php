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
        Schema::create('giao_hangs', function (Blueprint $table) {
            $table->id();
            $table->string('ma_van_don',50)->unique();
            $table->string('ma_hoa_don',50);
            $table->string('ho_ten_shipper',255);
            $table->char('so_dien_thoai',10);
            $table->bigInteger('trang_thai')->default(0); //0:đang giao hàng, 1:đã nhận hàng thành công, 2: nhận hàng không thành công
            $table->string('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_dons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giao_hangs');
    }
};
