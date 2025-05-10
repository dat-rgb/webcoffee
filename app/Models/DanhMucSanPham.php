<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucSanPham extends Model
{
    protected $table = 'danh_muc_san_phams';

    protected $primaryKey = 'ma_danh_muc';

    protected $fillabe = [
        'ma_danh_muc',
        'ten_danh_muc',
        'slug',
        'anh_dai_dien',
        'danh_muc_cha_id',
        'trang_thai',
        'thu_tu',
        'mo_ta'
    ];

    public function children()
    {
        return $this->hasMany(DanhMucSanPham::class, 'danh_muc_cha_id', 'ma_danh_muc');
    }

    public function sanPhams()
    {
        return $this->hasMany(SanPham::class);
    }
}
