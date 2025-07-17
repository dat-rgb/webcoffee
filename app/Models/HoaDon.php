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
        'token_bao_mat'
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
        return $this->belongsTo(KhuyenMai::class, 'ma_voucher', 'ma_voucher');
    }
    
    public function cuaHang()
    {
        return $this->belongsTo(CuaHang::class, 'ma_cua_hang', 'ma_cua_hang');
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

    public function reviews()
    {
        return $this->hasMany(Review::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public function getRouteKeyName()
    {
        return 'ma_hoa_don';
    }


    public static function countDonHangMoi($maCuaHang)
    {
        return self::where(function ($query) use ($maCuaHang) {
                $query->where('ma_cua_hang', $maCuaHang)
                    ->where(function ($q) { 
                        $q->where(function ($qq) {
                            $qq->where('trang_thai', 0)
                                ->where('phuong_thuc_thanh_toan', 'COD');
                        })
                        ->orWhere(function ($qq) {
                            $qq->where('phuong_thuc_thanh_toan', '!=', 'COD')
                                ->where('trang_thai', 0)
                                ->where('trang_thai_thanh_toan', 1);
                        });
                    });
            })
            ->count();
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
