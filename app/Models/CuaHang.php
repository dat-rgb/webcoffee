<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuaHang extends Model
{
    protected $table = 'cua_hangs';

    protected $fillable = [
        'ma_cua_hang',
        'ten_cua_hang',
        'slug',
        'so_nha',
        'ten_duong',
        'dia_chi',
        'gio_mo_cua',
        'gio_dong_cua',
        'ma_tinh',
        'ma_quan',
        'ma_xa',
        'so_dien_thoai',
        'email',
        'latitude',
        'longitude',
        'trang_thai',
    ];

    public static function generateMaCuaHang()
    {
        $last = self::orderByDesc('id')->first();
        $number = $last ? ((int)substr($last->ma_cua_hang, 2)) + 1 : 1;
        return 'CH' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::creating(function ($cuaHang) {
            if (empty($cuaHang->ma_cua_hang)) {
                $cuaHang->ma_cua_hang = self::generateMaCuaHang();
            }
        });
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'ma_cua_hang', 'ma_cua_hang');
    }
}
