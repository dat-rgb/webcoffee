<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaLamViec extends Model
{
    protected $table = 'ca_lam_viecs'; 

    protected $fillable = [
        'ma_nhan_vien',
        'thoi_gian_vao',
        'thoi_gian_ra',
        'tong_don_xac_nhan',
        'tong_tien',
        'tien_dau_ca',
        'tien_thuc_nhan',
        'tien_chenh_lech',
        'tong_tien_cod',
        'tong_tien_online',
        'ghi_chu',
    ];

    protected $casts = [
        'thoi_gian_vao' => 'datetime',
        'thoi_gian_ra' => 'datetime',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'ma_nhan_vien', 'ma_nhan_vien');
    }
}
