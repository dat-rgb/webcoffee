<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhPhanSanPham extends Model
{
    protected $table = 'thanh_phan_san_phams';
    
    protected $fillabe = [
        'ma_san_pham',
        'ma_nguyen_lieu',
        'ma_size',
        'dinh_luong',
        'don_vi'
    ];
}
