<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhPhanSanPham extends Model
{
    public $incrementing = false;
    protected $table = 'thanh_phan_san_phams';
    protected $primaryKey = null;

    protected $fillable = [
        'ma_san_pham',
        'ma_nguyen_lieu',
        'ma_size',
        'dinh_luong',
        'don_vi'
    ];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham');
    }

    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'ma_nguyen_lieu');
    }

    public function size()
    {
        return $this->belongsTo(Sizes::class, 'ma_size');
    }
}
