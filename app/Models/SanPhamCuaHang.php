<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamCuaHang extends Model
{
    protected $table = 'san_pham_cua_hangs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ma_san_pham',
        'ma_cua_hang',
        'trang_thai',
    ];

    // Quan hệ với SanPham
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    // Quan hệ với CuaHang
    public function sanPhamCuaHang()
    {
        return $this->belongsTo(CuaHang::class, 'ma_cua_hang', 'ma_cua_hang');
    }
}
