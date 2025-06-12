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
        Schema::create('thong_tin_websites', function (Blueprint $table) {
            $table->id();
            $table->string('ten_cong_ty')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('mo_ta')->nullable();
            $table->text('tu_khoa')->nullable();

            $table->string('dia_chi')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->string('email')->nullable();
            $table->text('ban_do')->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('zalo_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();


            $table->boolean('is_active')->default(true);
            $table->string('footer_text')->nullable();
            $table->text('script_header')->nullable();
            $table->text('script_footer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin_websites');
    }
};
