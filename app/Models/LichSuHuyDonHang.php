<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LichSuHuyDonHang extends Model
{
    use HasFactory;

    protected $table = 'lich_su_huy_don_hangs';

    protected $fillable = [
        'ma_hoa_don',
        'ma_nhan_vien',
        'ma_khach_hang',
        'ly_do_huy',
        'thoi_gian_huy',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'ma_nhan_vien', 'ma_nhan_vien');
    }
}
