<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuaHang extends Model
{
    protected $table = 'cua_hangs';

    protected $fillable = [
        'ma_cua_hang',
        'ten_cua_hang',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'trang_thai',
        'logo',
        'chi_nhanh',
    ];
    
    // Hàm tạo mã cửa hàng tự động: CH00000001
    public static function generateMaCuaHang()
    {
        $last = self::orderByDesc('id')->first();
        $number = $last ? ((int)substr($last->ma_cua_hang, 2)) + 1 : 1;
        return 'CH' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }

    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }


    // Auto tạo mã khi tạo mới
    protected static function booted()
    {
        static::creating(function ($cuaHang) {
            if (empty($cuaHang->ma_cua_hang)) {
                $cuaHang->ma_cua_hang = self::generateMaCuaHang();
            }
        });
    }
}
