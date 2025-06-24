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
            $table->char('ma_cua_hang',10)->unique(); 
            $table->string('ten_cua_hang', 255);
            $table->string('slug',255)->unique();
            $table->string('so_nha',  100)->nullable();
            $table->string('ten_duong', 100)->nullable();
            $table->string('dia_chi', 255)->nullable();
            $table->time('gio_mo_cua');
            $table->time('gio_dong_cua');
            $table->char('ma_tinh', 2)->nullable();   
            $table->char('ma_quan', 3)->nullable();    
            $table->char('ma_xa', 5)->nullable();       
            $table->string('so_dien_thoai', 20)->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->integer('trang_thai')->default(1);
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
