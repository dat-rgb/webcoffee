<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanPham extends Model
{
    use SoftDeletes;

    protected $table = 'san_phams'; 
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    
    protected $fillable  = [
        'ma_san_pham',
        'ten_san_pham',
        'gia',
        'slug',
        'thu_tu',
        'hot',
        'is_new',
        'san_pham_dong_goi',
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

   // SanPham.php
    public function sizes()
    {
        return $this->belongsToMany(Sizes::class, 'thanh_phan_san_phams', 'ma_san_pham', 'ma_size')
            ->withPivot('dinh_luong', 'don_vi'); // Đảm bảo lấy thêm các trường cần thiết từ bảng trung gian
    }

}
