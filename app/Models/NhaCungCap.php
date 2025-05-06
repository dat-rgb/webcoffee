<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $table = 'nha_cung_caps';

    protected $primarykey = 'ma_nha_cung_cap';

    protected $fillabe = [
        'ma_nha_cung_cap',
        'ten_nha_cung_cap',
        'dia_chi',
        'so_dien_thoai',
        'mail',
        'trang_thai'
    ];
}
