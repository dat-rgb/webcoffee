<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lichphancong extends Model
{
    use HasFactory;

    protected $table = 'lich_phan_congs';

    protected $primaryKey = 'ma_phan_cong';

    public $timestamps = true;

    protected $fillable = [
        'ma_nhan_vien',
        'ngay_lam',
        'ca_lam',
        'ghi_chu',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'ma_nhan_vien', 'ma_nhan_vien');
    }
}
