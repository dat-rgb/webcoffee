<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Authenticatable
{
    use Notifiable;

    protected $table = 'tai_khoans'; // Tên bảng
    protected $primaryKey = 'ma_tai_khoan'; // Khóa chính

    protected $fillable = [
        'email',
        'mat_khau',
        'qr_token',
        'loai_tai_khoan',
        'trang_thai',
        'activation_token'
    ]; 

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function status(){
        return $this->trang_thai = 0; //chờ kích hoạt
    }
}
