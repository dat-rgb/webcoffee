<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions'; 

    protected $fillable = [
        'ma_hoa_don',
        'tong_tien',
        'ten_khach_hang',
        'email',
        'so_dien_thoai',
        'dia_chi',
        'items_json',
        'payment_link',
        'trang_thai',
    ];
}
