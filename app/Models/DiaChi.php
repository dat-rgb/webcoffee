<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaChi extends Model
{
    protected $table = 'dia_chis';

    protected $fillable = [
        'ma_khach_hang',
        'dia_chi',
        'tinh_thanh',
        'quan_huyen',
        'phuong_xa',
        'mac_dinh',
    ];

    // Quan hệ với KhachHang
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }
}
