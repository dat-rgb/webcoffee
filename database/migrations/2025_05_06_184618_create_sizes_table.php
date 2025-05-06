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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id('ma_size');
            $table->string('ten_size', 50)->unique(); // VD: Nhỏ, Vừa, Lớn
            $table->float('gia_size')->default(0);
            $table->float('the_tich')->nullable();
            $table->integer('trang_thai')->default(1);
            $table->text('mo_ta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
