<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DanhMucSanPham extends Model
{
    use HasFactory;


    protected $table = 'danh_muc_san_phams';  // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'ma_danh_muc';    // Khóa chính của bảng
    //protected $dates = ['deleted_at'];

    protected $fillable = [
        'ten_danh_muc',
        'slug',
        'anh_dai_dien',
        'danh_muc_cha_id',
        'trang_thai',
        'thu_tu',
        'mo_ta'
    ];
    public function sanPhams()
    {
        return $this->hasMany(SanPham::class);
    }
    public function parent()
    {
        return $this->belongsTo(DanhMucSanPham::class, 'danh_muc_cha_id');
    }
    public function children()
    {
        return $this->hasMany(DanhMucSanPham::class, 'danh_muc_cha_id');
    }
    public function deactivateChildren()
    {
        foreach ($this->children as $child) {
            $child->update(['trang_thai' => 2]);
            $child->deactivateChildren(); // gọi đệ quy tiếp
        }
    }


    public function subCategories()
    {
        return $this->hasMany(DanhMucSanPham::class, 'danh_muc_cha_id');
    }


    // Trong model DanhMucSanPham
    public function archiveWithChildren()
    {
        $this->trang_thai = 3;
        $this->save();

        $children = DanhMucSanPham::where('danh_muc_cha_id', $this->ma_danh_muc)->get();

        foreach ($children as $child) {
            $child->archiveWithChildren(); // đệ quy
        }
    }

}
