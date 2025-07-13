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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('phi_ship')->default(30000); 
            $table->integer('so_luong_toi_thieu')->default(1);
            $table->integer('so_luong_toi_da')->default(10);
            $table->float('ban_kinh_giao_hang')->default(10); 
            $table->float('ban_kinh_hien_thi_cua_hang')->default(20); 
            $table->integer('vat_mac_dinh')->default(10); // %
            $table->boolean('che_do_bao_tri')->default(false);
            $table->integer('ty_le_diem_thuong')->default(1000); // 1 diem = ? VND
            $table->integer('nguong_mien_phi_ship')->default(100000); // VND
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
