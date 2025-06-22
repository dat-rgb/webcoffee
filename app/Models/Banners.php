<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'hinh_anh',
        'link_dich',
        'trang_hien_thi',
        'vi_tri',
        'thu_tu',
        'trang_thai',
    ];
}
