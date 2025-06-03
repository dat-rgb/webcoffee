<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DanhMucBlog extends Model
{
    use HasFactory;

    protected $table = 'danh_muc_blogs';
    protected $primaryKey = 'ma_danh_muc_blog';
    public $timestamps = true;

    protected $fillable = [
        'ten_danh_muc_blog',
        'slug',
        'mo_ta',
        'trang_thai',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'ma_danh_muc_blog', 'ma_danh_muc_blog');
    }
}
