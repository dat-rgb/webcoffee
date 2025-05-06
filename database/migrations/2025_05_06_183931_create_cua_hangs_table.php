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
        Schema::create('cua_hangs', function (Blueprint $table) {
            $table->id();
            $table->char('ma_cua_hang',10)->unique(); //CH00000001
            $table->string('ten_cua_hang', 255);
            $table->string('dia_chi', 255)->nullable();
            $table->string('so_dien_thoai', 20)->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->integer('trang_thai')->default(1);
            $table->string('logo')->nullable();
            $table->integer('chi_nhanh')->default(1); //0 cửa hàng chính, 1 chi nhánh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cua_hangs');
    }
};
