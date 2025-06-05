<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Models\SanPhamCuaHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SanPham;

class StaffProductController extends Controller
{
    public function getProductForStaff($search = '', $status = null)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanVien;

        if (!$nhanVien || !$nhanVien->ma_cua_hang) {
            return collect(); // Không có cửa hàng -> return rỗng
        }

        $query = SanPham::with('danhMuc')
            ->where('trang_thai', 1) // Sản phẩm đang được Admin cho hiển thị
            ->whereHas('sanPhamCuaHang', function ($q) use ($nhanVien, $status) {
                $q->where('ma_cua_hang', $nhanVien->ma_cua_hang);

                // Nếu status có truyền vào thì lọc theo trạng thái hiển thị tại cửa hàng
                if (!is_null($status)) {
                    $q->where('trang_thai', $status);
                } else {
                    $q->where('trang_thai', 1); // Mặc định: sản phẩm đang bán
                }
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ten_san_pham', 'like', "%{$search}%")
                ->orWhere('ma_san_pham', 'like', "%{$search}%")
                ->orWhere('mo_ta', 'like', "%{$search}%")
                ->orWhereHas('danhMuc', function ($q2) use ($search) {
                    $q2->where('ten_danh_muc', 'like', "%{$search}%");
                });
            });
        }

        return $query->paginate(10);
    }
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status'); // 1: đang bán, 0: ngừng bán, null: all

        $nhanVien = Auth::guard('staff')->user()->nhanVien;

        if (!$nhanVien || !$nhanVien->ma_cua_hang) {
            toastr()->error('Không tìm thấy thông tin cửa hàng.');
            return redirect()->back();
        }
        if (!in_array($nhanVien->ma_chuc_vu, [1, 3])) {
            toastr()->error('Bạn không có quyền truy cập.');
            return redirect()->back();
        }

        $products = $this->getProductForStaff($search, $status);

        $viewData = [
            'title' => 'Quản lý sản phẩm cửa hàng ' . $nhanVien->ma_cua_hang . ' | CMDT Coffee & Tea',
            'subtitle' => 'Sản phẩm tại cửa hàng ' . $nhanVien->ma_cua_hang,
            'products' => $products,
            'search' => $search,
            'status' => $status,    
        ];

        return view('staffs.pages.product_store', $viewData);
    }
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'status' => 'required|in:0,1'
        ]);

        foreach ($validated['products'] as $maSanPham) {
            SanPhamCuaHang::where('ma_san_pham', $maSanPham)
                ->where('ma_cua_hang', Auth::guard('staff')->user()->nhanVien->ma_cua_hang)
                ->update(['trang_thai' => $validated['status']]);
        }

        return response()->json(['message' => 'Cập nhật trạng thái thành công!']);
    }

}
