<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanPhamYeuThich extends Model
{
    protected $table = 'san_pham_yeu_thichs';

    public $incrementing = false;
    protected $primaryKey = null;


    public $timestamps = true;

    protected $fillable = ['ma_khach_hang', 'ma_san_pham'];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }
    
    public function delete()
    {
        return self::where('ma_khach_hang', $this->ma_khach_hang)
            ->where('ma_san_pham', $this->ma_san_pham)
            ->delete();
    }

    public static function addWithList($maKhachHang, $maSanPham): bool
    {
        $existing = self::where('ma_khach_hang', $maKhachHang)
                        ->where('ma_san_pham', $maSanPham)
                        ->first();

        if ($existing) {
            $existing->delete();
            return false;
        } else {
            self::create([
                'ma_khach_hang' => $maKhachHang,
                'ma_san_pham' => $maSanPham,
            ]);
            return true;
        }
    }
}
