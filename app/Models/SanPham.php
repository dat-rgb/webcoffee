<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'san_phams'; 
    public $timestamps = true;

    protected $fillabe = [
        'id',
        'ma_san_pham',
        'ten_san_pham',
        'gia',
        'slug',
        'thu_tu',
        'hot',
        'is_new',
        'mo_ta',
        'hinh_anh',
        'luot_xem',
        'rating',
        'ma_danh_muc',
        'trang_thai'    
    ];

    public function danhMuc()
    {
        return $this->belongsTo(DanhMucSanPham::class, 'ma_danh_muc'); 
    }
}
