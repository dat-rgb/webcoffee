<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuaHangNguyenLieu extends Model
{
    protected $table = 'cua_hang_nguyen_lieus';

    protected $primaryKey = ['ma_cua_hang', 'ma_nguyen_lieu'];
    public $incrementing = false;
    public $timestamps = false; // nếu bảng không có cột created_at và updated_at

    protected $fillable = [
        'ma_cua_hang',
        'ma_nguyen_lieu',
        'so_luong_ton',
        'so_luong_ton_min',
        'so_luong_ton_max',
        'don_vi',
        'so_lo',
    ];

    /**
     * Quan hệ (nếu có) ví dụ:
     * Một nguyên liệu thuộc về cửa hàng.
     */
    public function cuaHang()
    {
        return $this->belongsTo(CuaHang::class, 'ma_cua_hang', 'ma_cua_hang');
    }

    /**
     * Một nguyên liệu có thể liên kết với bảng NguyenLieu nếu có.
     */
    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }
    public function phieuNhapXuat()
    {
        return $this->hasMany(PhieuNhapXuatNguyenLieu::class, 'ma_cua_hang', 'ma_cua_hang')
                    ->whereColumn('ma_nguyen_lieu', 'ma_nguyen_lieu');
    }

}
