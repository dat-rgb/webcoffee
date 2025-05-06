<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    protected $table = 'chuc_vus';

    protected $fillabe = [
        'ma_chuc_vu',
        'ten_chuc_vu',
        'luong_co_ban'    
    ];
}
