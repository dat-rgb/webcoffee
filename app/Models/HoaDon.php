<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HoaDon extends Model
{
    protected $table = 'hoa_dons';

    protected $keyType = 'string';

    protected $fillable = [
        'ma_hoa_don',
        'ma_nhan_vien',
        'ma_khach_hang',
        'ma_voucher',
        'ma_cua_hang',
        'ten_khach_hang',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'phuong_thuc_thanh_toan',
        'phuong_thuc_nhan_hang',
        'ghi_chu',
        'tam_tinh',
        'tien_ship',
        'khuyen_mai',
        'giam_gia',
        'tong_tien',
        'trang_thai_thanh_toan',
        'trang_thai',
    ];
    
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function chiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'ma_khuyen_mai', 'ma_khuyen_mai');
    }

    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public function giaoHang()
    {
        return $this->hasOne(GiaoHang::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public function lichSuHuyDonHang()
    {
        return $this->hasMany(LichSuHuyDonHang::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public static function generateMaHoaDon(): string
    {
        $prefix = 'HD';

        do {
            $datetime = now()->format('ymdHis');
            $randomDigits = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $maHoaDon = $prefix . $datetime . $randomDigits;
        } while (self::where('ma_hoa_don', $maHoaDon)->exists());

        return $maHoaDon;
    }
}
