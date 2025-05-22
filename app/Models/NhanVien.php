<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhan_viens';

    protected $primaryKey = 'ma_nhan_vien';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'ma_nhan_vien',
        'ma_chuc_vu',
        'ma_tai_khoan',
        'ma_cua_hang',
        'ho_ten_nhan_vien',
        'ngay_sinh',
        'gioi_tinh',
        'so_dien_thoai',
        'dia_chi',
        'ca_lam',
    ];

    /**
     * Quan hệ với tài khoản
     */
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }

    /**
     * Quan hệ với chức vụ
     */
    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'ma_chuc_vu', 'ma_chuc_vu');
    }

    /**
     * Quan hệ với cửa hàng
     */
    public function cuaHang()
    {
        return $this->belongsTo(CuaHang::class, 'ma_cua_hang', 'ma_cua_hang');
    }
}
