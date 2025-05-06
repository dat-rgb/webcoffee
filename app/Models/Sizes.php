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
}
