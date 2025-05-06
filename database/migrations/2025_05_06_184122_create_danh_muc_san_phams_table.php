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
        Schema::create('danh_muc_san_phams', function (Blueprint $table) {
            $table->id('ma_danh_muc'); 
            $table->string('ten_danh_muc', 100)->unique(); 
            $table->string('slug', 150)->unique(); 
            $table->string('anh_dai_dien', 255)->nullable(); 
            $table->unsignedBigInteger('danh_muc_cha_id')->nullable(); 
            $table->tinyInteger('trang_thai')->default(1); 
            $table->integer('thu_tu')->default(0); 
            $table->text('mo_ta')->nullable(); 
            $table->timestamps();

            //Khóa ngoại
            $table->foreign('danh_muc_cha_id')
                  ->references('ma_danh_muc')
                  ->on('danh_muc_san_phams')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_muc_san_phams');
    }
};
