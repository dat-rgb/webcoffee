<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhancongcalamTable extends Migration
{
    public function up()
    {
        Schema::create('lich_phan_congs', function (Blueprint $table) {
            $table->id('ma_phan_cong'); // tự động tăng
            $table->char('ma_nhan_vien',10);
            $table->date('ngay_lam');
            $table->integer('ca_lam')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('ma_nhan_vien')
                ->references('ma_nhan_vien')
                ->on('nhan_viens')
                ->onDelete('cascade');

            $table->unique(['ma_nhan_vien', 'ngay_lam']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_phan_congs');
    }
}
