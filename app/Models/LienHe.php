<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    use HasFactory;

    protected $table = 'lien_hes';

    protected $fillable = [
        'ma_khach_hang',
        'ma_nhan_vien',
        'ho_ten',
        'so_dien_thoai',
        'email',
        'tieu_de',
        'noi_dung',
        'ngay_gui',
        'trang_thai',
    ];
}
