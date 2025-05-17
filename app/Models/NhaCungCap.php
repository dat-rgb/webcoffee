<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $table = 'nha_cung_caps';

    protected $primaryKey = 'ma_nha_cung_cap'; // Khóa chính là cột này

    public $incrementing = true; // Tự động tăng

    protected $keyType = 'int'; // Kiểu số nguyên

    protected $fillable = [
        'ten_nha_cung_cap',
        'dia_chi',
        'so_dien_thoai',
        'mail',
        'trang_thai'
    ];
}
