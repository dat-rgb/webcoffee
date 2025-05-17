<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguyenLieu extends Model
{
    protected $table = 'nguyen_lieus';
    protected $primarykey = 'ma_nguyen_lieu';
    protected $fillable  = [
        'ma_nguyen_lieu',
        'ten_nguyen_lieu',
        'slug',
        'ma_nha_cung_cap',
        'so_luong',
        'gia',
        'loai_nguyen_lieu',
        'don_vi',
        'trang_thai',
    ];
    public function parent()
    {
        return $this->belongsTo(NguyenLieu::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(NguyenLieu::class, 'parent_id');
    }
    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'ma_nha_cung_cap', 'ma_nha_cung_cap');
    }

}
