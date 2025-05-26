<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_dons';

    // Nếu bảng không có khóa chính hoặc khóa chính không phải id kiểu int auto increment
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'ma_hoa_don',
        'ma_san_pham',
        'ten_san_pham',
        'ten_size',
        'gia_size',
        'so_luong',
        'don_gia',
        'thanh_tien',
        'ghi_chu',
    ];

    // Quan hệ với HoaDon
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    // Quan hệ với SanPham
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }
}
