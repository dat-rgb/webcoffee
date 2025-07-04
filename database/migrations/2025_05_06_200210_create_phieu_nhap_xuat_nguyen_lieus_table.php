<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phieu_nhap_xuat_nguyen_lieus', function (Blueprint $table) {
            $table->id('ma_phieu'); // Khóa chính tự tăng
            $table->char('ma_cua_hang', 10);
            $table->char('ma_nguyen_lieu', 10);
            $table->char('ma_nhan_vien', 10)->nullable();
            $table->string('so_lo')->nullable();
            $table->integer('loai_phieu'); // 0: nhập, 1: xuất
            $table->dateTime('ngay_san_xuat')->nullable();
            $table->dateTime('han_su_dung')->nullable();
            $table->float('so_luong'); // Số lượng nhập,xuất,..
            $table->float('dinh_luong'); //Tổng định lượng nhập = [Số lượng nhập,xuất] * [nguyen_lieus.so_luong]
            $table->float(column: 'so_luong_ton_truoc'); // Tồn tại cửa hàng nguyên liệu trước khi nhập [cua_hang_nguyen_lieus.so_luong_ton]
            $table->string('don_vi', 50); //Đơn vị tính theo số lượng nhập,xuất,..
            $table->float('gia_tien')->default(0)->nullable();// giá từ [nguyen_lieus.gia]
            $table->float('gia_nhap')->default(0)->nullable();//giá nhập từ phiếu nhập khi import[phieunhapnguyenLieu.gia_nhap]
            $table->float('tong_tien')->default(0)->nullable(); //Tổng tiền = [Số lượng nhập,xuất] * [nguyen_lieus.gia]
            $table->dateTime('ngay_tao_phieu');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('ma_cua_hang')->references('ma_cua_hang')->on('cua_hangs')->onDelete('cascade');
            $table->foreign('ma_nguyen_lieu')->references('ma_nguyen_lieu')->on('nguyen_lieus')->onDelete('cascade');
            $table->foreign('ma_nhan_vien')->references('ma_nhan_vien')->on('nhan_viens')->onDelete('cascade');
        });

    }
    //kiểm tra lại số lượng đã dùng của lô = tồn kho - tồn trước
    //if số lượng đã dùng nhỏ hơn số lượng nhập thì đag dùng thì chx dùng  //hủy, xuát
    //if số lượng đã dùng bằng số lượng nhập thì đã dùng hết
    //if số lượng đã dùng lớn hơn số lượng nhập thì đã dùng qua lô khác

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap_xuat_nguyen_lieus');
    }
};
