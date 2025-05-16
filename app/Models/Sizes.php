<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    use HasFactory;

    protected $table = 'sizes';
    
    protected $primaryKey = 'ma_size';

    protected $fillabe = [
        'ma_size',
        'ten_size',
        'gia_size',
        'the_tich',
        'trang_thai',
        'mo_ta'    
    ];
    // Size.php
    public function products()
    {
        return $this->belongsToMany(SanPham::class, 'thanh_phan_san_phams', 'ma_size', 'ma_san_pham');
    }

}
