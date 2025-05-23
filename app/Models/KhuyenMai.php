<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhuyenMai extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'khuyen_mais'; // Tên bảng
    //protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'ma_voucher',
        'ten_voucher',
        'hinh_anh',
        'so_luong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'dieu_kien_ap_dung',
        'gia_tri_giam',
        'giam_gia_max',
        'trang_thai',
    ];
}
