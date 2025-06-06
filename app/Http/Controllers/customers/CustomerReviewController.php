<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'maSanPham' => 'required|exists:san_phams,ma_san_pham',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá'], 401);
            }

            $maKhachHang = $user->khachHang->ma_khach_hang ?? null;
            if (!$maKhachHang) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy thông tin khách hàng'], 400);
            }

            $review = new Review();
            $review->ma_san_pham = $request->maSanPham;
            $review->ma_khach_hang = $maKhachHang;
            $review->ma_hoa_don = $request->maHoaDon; 
            $review->rating = $request->rating;
            $review->danh_gia = $request->comment ?? '';
            $review->save();

            $avgRating = Review::where('ma_san_pham', $request->maSanPham)->avg('rating');
            SanPham::where('ma_san_pham', $request->maSanPham)->update(['rating' => $avgRating]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Trả JSON lỗi để frontend nhận biết
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
