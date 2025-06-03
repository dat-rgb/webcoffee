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
        Schema::create('danh_muc_blogs', function (Blueprint $table) {
            $table->id('ma_danh_muc_blog');
            $table->string('ten_danh_muc_blog',255);
            $table->string('slug',255)->unique();
            $table->text('mo_ta')->nullable();
            $table->tinyInteger('trang_thai')->default(0);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_muc_blogs');
    }
};
