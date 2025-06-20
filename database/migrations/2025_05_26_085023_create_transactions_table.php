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
        Schema::create('transactions', function (Blueprint $table) {
             $table->id();
            $table->string('ma_hoa_don')->unique();
            $table->bigInteger('tong_tien');
            $table->string('ten_khach_hang');
            $table->string('email');
            $table->string('so_dien_thoai');
            $table->string('dia_chi')->nullable();
            $table->text('items_json'); // lưu json items
            $table->string('payment_link')->nullable();
            $table->enum('trang_thai', ['PENDING', 'SUCCESS', 'FAILED', 'CANCELLED', 'REFUNDING', 'REFUNDED'])->default('PENDING');
            $table->string('counter_account_bank_id')->nullable();
            $table->string('counter_account_bank_name')->nullable();
            $table->string('counter_account_name')->nullable();
            $table->string('counter_account_number')->nullable();
            $table->string('virtual_account_name')->nullable();
            $table->string('virtual_account_number')->nullable();
            $table->timestamps();
            
            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_dons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
