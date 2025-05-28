<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanPhamYeuThich extends Model
{
    protected $table = 'san_pham_yeu_thichs';

    // Bảng không có cột id mặc định
    public $incrementing = false;
    protected $primaryKey = null;

    // hoặc nếu bảng có khóa chính là tổ hợp 2 cột
    // thì phải override 1 số method khác, hoặc dùng composite key package

    public $timestamps = true;

    protected $fillable = ['ma_khach_hang', 'ma_san_pham'];

    // Thay đổi delete để dùng điều kiện
    public function delete()
    {
        return self::where('ma_khach_hang', $this->ma_khach_hang)
            ->where('ma_san_pham', $this->ma_san_pham)
            ->delete();
    }

    public static function addWithList($maKhachHang, $maSanPham): bool
    {
        $existing = self::where('ma_khach_hang', $maKhachHang)
                        ->where('ma_san_pham', $maSanPham)
                        ->first();

        if ($existing) {
            $existing->delete();
            return false;
        } else {
            self::create([
                'ma_khach_hang' => $maKhachHang,
                'ma_san_pham' => $maSanPham,
            ]);
            return true;
        }
    }
}
