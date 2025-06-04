<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguyenLieu extends Model
{
    protected $table = 'nguyen_lieus';
    protected $primarykey = 'ma_nguyen_lieu';
    public $incrementing = false;             // ✅ nếu mã là chuỗi (không tự tăng)
    protected $keyType = 'string';
    protected $fillable  = [
        'ma_nguyen_lieu',
        'ten_nguyen_lieu',
        'slug',
        'ma_nha_cung_cap',
        'so_luong',//đay la dinh luong
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
    public function cuaHangs()
    {
        return $this->belongsToMany(CuaHang::class, 'cua_hang_nguyen_lieus', 'ma_nguyen_lieu', 'ma_cua_hang')
                    ->withPivot('so_luong_ton', 'so_luong_ton_min', 'so_luong_ton_max', 'don_vi') // nếu có các cột này trong bảng trung gian
                    ->withTimestamps();
    }
    public function cuaHangNguyenLieus()
    {
        return $this->hasMany(CuaHangNguyenLieu::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }



}
    