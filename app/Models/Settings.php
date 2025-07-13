<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'phi_ship',
        'so_luong_toi_thieu',
        'so_luong_toi_da',
        'ban_kinh_giao_hang',
        'ban_kinh_hien_thi_cua_hang',
        'vat_mac_dinh',
        'che_do_bao_tri',
        'ty_le_diem_thuong',
        'nguong_mien_phi_ship',
    ];
}
