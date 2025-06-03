<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';
    protected $primaryKey = 'ma_blog';
    public $timestamps = true;

    protected $fillable = [
        'ma_danh_muc_blog',
        'tieu_de',
        'slug',
        'sub_tieu_de',
        'hinh_anh',
        'noi_dung',
        'trang_thai',
        'luot_xem',
        'hot',
        'is_new',
        'tac_gia',
        'ngay_dang',
        'do_uu_tien',
    ];

    public function danhMuc()
    {
        return $this->belongsTo(DanhMucBlog::class, 'ma_danh_muc_blog', 'ma_danh_muc_blog');
    }
}
