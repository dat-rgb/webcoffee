<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
    public function hoaDon(): BelongsTo
    {
        return $this->belongsTo(HoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }
}
