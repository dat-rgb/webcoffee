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
        Schema::create('ca_lam_viecs', function (Blueprint $table) {
            $table->id();

            $table->char('ma_nhan_vien', 10);
            $table->dateTime('thoi_gian_vao');
            $table->dateTime('thoi_gian_ra')->nullable();

            $table->integer('tong_don_xac_nhan')->default(0);
            $table->float('tong_tien')->default(0); // COD + Online

            $table->float('tien_dau_ca')->default(0);
            $table->float('tien_thuc_nhan')->default(0);
            $table->float('tien_chenh_lech')->default(0);
            $table->float('tong_tien_cod')->default(0);
            $table->float('tong_tien_online')->default(0);

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('ma_nhan_vien')->references('ma_nhan_vien')->on('nhan_viens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ca_lam_viecs');
    }
};
        