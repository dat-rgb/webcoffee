<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NguyenLieu extends Model
{
    use SoftDeletes;
    protected $table = 'nguyen_lieus';
    protected $primarykey = 'ma_nguyen_lieu';
    public $incrementing = false;
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
    protected $dates = ['deleted_at'];
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







    public function products()
{
    return $this->belongsToMany(SanPham::class, 'thanh_phan_san_phams', 'ma_nguyen_lieu', 'ma_san_pham', 'ma_nguyen_lieu', 'ma_san_pham');
}
public function nguyenLieu()
{
    return $this->belongsTo(NguyenLieu::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
}






}
