<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuNhapXuatNguyenLieu extends Model
{
    protected $table = 'phieu_nhap_xuat_nguyen_lieus';

    protected $primaryKey = 'ma_phieu';

    protected $fillable = [
        'ma_phieu',
        'ma_cua_hang',
        'ma_nguyen_lieu',
        'ma_nhan_vien',
        'loai_phieu',
        'so_lo',
        'ngay_san_xuat',
        'han_su_dung',
        'so_luong',
        'dinh_luong',
        'so_luong_ton_truoc',
        'don_vi',
        'gia_tien',
        'gia_nhap',
        'tong_tien',
        'ngay_tao_phieu',
        'ghi_chu',
    ];

    // Quan hệ với cửa hàng
    public function cuaHang()
    {
        return $this->belongsTo(CuaHang::class, 'ma_cua_hang', 'ma_cua_hang');
    }

    // Quan hệ với nguyên liệu
    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }

    // Quan hệ với nhân viên
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'ma_nhan_vien', 'ma_nhan_vien');
    }

    // Quan hệ với cửa hàng-nguyên liệu dựa trên ma_cua_hang và ma_nguyen_lieu
    public function cuaHangNguyenLieu()
    {
        return $this->hasOne(CuaHangNguyenLieu::class, 'ma_cua_hang', 'ma_cua_hang')
            ->whereColumn('ma_nguyen_lieu', 'phieu_nhap_xuat_nguyen_lieus.ma_nguyen_lieu');
    }
     public static function generateSoLo(): string
    {
        $prefix = 'LO';

        do {
            $datetime = now()->format('ymdHis');
            $solo = $prefix . $datetime;
        } while (self::where('so_lo', $solo)->exists());

        return $solo;
    }

}
