<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguyenLieu extends Model
{
    protected $table = 'nguyen_lieus';
    protected $primarykey = 'ma_nguyen_lieu';
    protected $fillabe = [
        'ma_nguyen_lieu',
        'ten_nguyen_lieu',
        'slug',
        'ma_nha_cung_cap',
        'dinh_luong',
        'gia',
        'loai_nguyen_lieu',
        'don_vi',
        'trang_thai',
    ];
}
