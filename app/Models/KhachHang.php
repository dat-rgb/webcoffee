<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class KhachHang extends Model
{
    use HasFactory;
    
    // Tên bảng nếu không theo chuẩn Laravel (tự động số nhiều hóa tên model)
    protected $table = 'khach_hangs';

    protected $fillable = [
        'ma_khach_hang',
        'ma_tai_khoan',
        'ho_ten_khach_hang',
        'ngay_sinh',
        'gioi_tinh',
        'so_dien_thoai',
        'diem_thanh_vien',
        'hang_thanh_vien',
    ];

    // Quan hệ với bảng tai_khoans
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }
    
    public function yeuThichSanPhams()
    {
        return $this->hasMany(SanPhamYeuThich::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function diaChis()
    {
        return $this->hasMany(DiaChi::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public static function generateMaKhachHang()
    {
        $nextId = static::max('id') + 1;
        return 'KH' . str_pad($nextId, 8, '0', STR_PAD_LEFT);
    }

}
