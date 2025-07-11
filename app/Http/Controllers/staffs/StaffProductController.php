<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Models\SanPhamCuaHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SanPham;

class StaffProductController extends Controller
{
    public function getProductForStaff($search = '', $status = null, $storeId = null)
    {
        // Nếu không truyền storeId thì thử lấy từ Auth staff
        if (!$storeId) {
            $nhanVien = Auth::guard('staff')->user()->nhanVien;
            $storeId = $nhanVien?->ma_cua_hang;
        }

        // Nếu vẫn không có storeId thì trả về collection rỗng
        if (!$storeId) {
            return collect();
        }

        $query = SanPham::with('danhMuc')
            ->where('trang_thai', 1) // Sản phẩm đang được Admin cho hiển thị
            ->whereHas('sanPhamCuaHang', function ($q) use ($storeId, $status) {
                $q->where('ma_cua_hang', $storeId);

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

    public function tinhTrangNguyenLieu($storeId, $productId)
    {
        $thanhPhan = \DB::table('thanh_phan_san_phams')
            ->where('ma_san_pham', $productId)
            ->get();

        if ($thanhPhan->isEmpty()) return ['alerts' => [], 'nguyen_lieu_co_trong_cua_hang' => 0, 'tong_nguyen_lieu' => 0];

        $nguyenLieuCanhBao = [];
        $coTrongCuaHang = 0;

        foreach ($thanhPhan as $tp) {
            $ngl = \DB::table('cua_hang_nguyen_lieus')
                ->where('ma_cua_hang', $storeId)
                ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                ->first();

            if (!$ngl) continue;

            $coTrongCuaHang++;

            $soLuongTaoDuoc = $tp->dinh_luong > 0 ? floor($ngl->so_luong_ton / $tp->dinh_luong) : 0;

            if ($ngl->so_luong_ton <= $ngl->so_luong_ton_min || $soLuongTaoDuoc <= 1) {
                $nguyenLieuCanhBao[] = [
                    'ma_nguyen_lieu' => $tp->ma_nguyen_lieu,
                    'ten_nguyen_lieu' => optional(\DB::table('nguyen_lieus')->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)->first())->ten_nguyen_lieu,
                    'so_luong_ton' => $ngl->so_luong_ton,
                    'so_luong_min' => $ngl->so_luong_ton_min,
                    'so_ly_tao_duoc' => $soLuongTaoDuoc,
                    'dinh_luong' => $tp->dinh_luong,
                    'don_vi' => $tp->don_vi,
                ];
            }
        }

        return [
            'alerts' => $nguyenLieuCanhBao,
            'nguyen_lieu_co_trong_cua_hang' => $coTrongCuaHang,
            'tong_nguyen_lieu' => $thanhPhan->count()
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status');           // 1: đang bán, 0: ngừng bán, null: all
        $nv     = Auth::guard('staff')->user()->nhanVien;


        // Guard
        if (!$nv || !$nv->ma_cua_hang) {
            toastr()->error('Không tìm thấy thông tin cửa hàng.');
            return back();
        }
        if (!in_array($nv->ma_chuc_vu, [1, 3])) {
            toastr()->error('Bạn không có quyền truy cập.');
            return back();
        }

        $products = $this->getProductForStaff($search, $status, $nv->ma_cua_hang);
        foreach ($products as $p) {
            $check = $this->tinhTrangNguyenLieu($nv->ma_cua_hang, $p->ma_san_pham);
            $p->ingredientAlerts = $check['alerts'];
            $p->hasAlert         = !empty($check['alerts']);
            $p->nglCount         = $check['nguyen_lieu_co_trong_cua_hang'];
            $p->nglTotal         = $check['tong_nguyen_lieu'];
        }

        return view('staffs.pages.product_store', [
            'title'    => "Quản lý sản phẩm cửa hàng {$nv->ma_cua_hang} | CMDT Coffee & Tea",
            'subtitle' => "Sản phẩm tại cửa hàng {$nv->ma_cua_hang}",
            'products' => $products,
            'search'   => $search,
            'status'   => $status,
        ]);
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
