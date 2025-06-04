<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\SanPhamYeuThich;
use Illuminate\Http\Request;

class CustomerFavoriteController extends Controller
{
    public function favoriteProduct($proId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này'
            ]);
        }

        $khachHang = $user->khachHang;

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng'
            ]);
        }

        $toggled = SanPhamYeuThich::addWithList($khachHang->ma_khach_hang, $proId);

        return response()->json([
            'success' => true,
            'favorited' => $toggled,
            'message' => $toggled
                ? 'Đã thêm vào danh sách yêu thích'
                : 'Đã xóa khỏi danh sách yêu thích'
        ]);
    }

    public function showFavorite(){
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này'
            ]);
        }

        $khachHang = $user->khachHang;

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng'
            ]);
        }

        $favorites = SanPhamYeuThich::with('sanPham')->where('ma_khach_hang',$khachHang->ma_khach_hang)->get();

        $viewData = [
            'title' => 'Sản phẩm yêu thích của ' . $khachHang->ho_ten_khach_hang,
            'subtitle' => 'Sản phẩm yêu thích',
            'favorites' => $favorites,
        ];

        return view('clients.customers.favorites.index', $viewData);
    }
}
