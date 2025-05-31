<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiaoHang extends Model
{
    use HasFactory;

    protected $table = 'giao_hangs';

    protected $fillable = [
        'ma_van_don',
        'ma_hoa_don',
        'ho_ten_shipper',
        'so_dien_thoai',
        'trang_thai',
        'ghi_chu',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public static function generateMaVanDon(): string
    {
        $prefix = 'VD';

        do {
            $datetime = now()->format('ymdHis'); // ymdHis = nămthángngàygiờphútdây
            $randomDigits = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT); // 4 số ngẫu nhiên
            $maVanDon = $prefix . $datetime . $randomDigits;
        } while (self::where('ma_van_don', $maVanDon)->exists());

        return $maVanDon;
    }
}

