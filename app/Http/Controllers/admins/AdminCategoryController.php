<?php

namespace App\Http\Controllers\admins;

use App\Models\DanhMucSanPham;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index(Request $request)
    {
        $query = DanhMucSanPham::with('parent'); // thêm eager loading ở đây

        // Kiểm tra xem có tham số trạng thái không
        if ($request->has('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        } else {
            // Nếu không có trạng thái, chỉ lấy danh mục có trạng thái khác 3 (Lưu trữ)
            $query->where('trang_thai', '!=', 3);
        }

        // Phân trang 7 danh mục mỗi trang
        $categories = $query->paginate(7);

        return view('admins.category.index', compact('categories'));
    }
    // Hiển thị form tạo mới
    public function create()
    {
        $categories = DanhMucSanPham::all();
        return view('admins.category.create', compact('categories'));
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required|string|max:255|unique:danh_muc_san_phams,ten_danh_muc',
            'mo_ta' => 'nullable|string',
            'danh_muc_cha_id' => 'nullable|exists:danh_muc_san_phams,ma_danh_muc',
            'trang_thai' => 'required|in:1,2',
        ]);

        DanhMucSanPham::create([
            'ten_danh_muc' => $request->ten_danh_muc,
            'slug' => Str::slug($request->ten_danh_muc),
            'anh_dai_dien' => null,
            'danh_muc_cha_id' => $request->danh_muc_cha_id,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admins.category.index')->with('success', 'Tạo danh mục thành công');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $category = DanhMucSanPham::findOrFail($id);
        $categories = DanhMucSanPham::all();
        return view('admins.category.edit', compact('category', 'categories'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $category = DanhMucSanPham::findOrFail($id);

        $request->validate([
            'ten_danh_muc' => 'required|string|max:255|unique:danh_muc_san_phams,ten_danh_muc,' . $id . ',ma_danh_muc',
            'mo_ta' => 'nullable|string',
            'danh_muc_cha_id' => 'nullable|exists:danh_muc_san_phams,ma_danh_muc',
            'trang_thai' => 'required|in:1,2',
        ]);

        $category->update([
            'ten_danh_muc' => $request->ten_danh_muc,
            'slug' => Str::slug($request->ten_danh_muc),
            'danh_muc_cha_id' => $request->danh_muc_cha_id,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => $request->trang_thai,
        ]);

        // Nếu bị tắt hoạt động, thì tắt luôn danh mục con
        if ($request->trang_thai == 2) {
            $category->deactivateChildren();
        }

        return redirect()->route('admins.category.index')->with('success', 'Cập nhật danh mục thành công');
    }


    // Xóa danh mục
    public function destroy($id)
    {
        $category = DanhMucSanPham::findOrFail($id);
        $category->delete();

        return redirect()->route('admins.category.index')->with('success', 'Xóa danh mục thành công');
    }
    public function archive($id)
    {
        $category = DanhMucSanPham::findOrFail($id);
        $category->archiveWithChildren();
        $category->trang_thai = 3; // Chuyển vào lưu trữ
        $category->save();

        DanhMucSanPham::where('danh_muc_cha_id', $category->ma_danh_muc)->update(['trang_thai' => 3]);


        return redirect()->route('admins.category.index')->with('success', 'Đã chuyển danh mục vào lưu trữ.');
    }
    public function archiveIndex()
    {
        // Lấy danh sách các danh mục có trạng thái 'Lưu trữ' (trang_thai = 3)
        $categories = DanhMucSanPham::where('trang_thai', 3)->paginate(7);

        return view('admins.category.archive', compact('categories'));
    }

    public function restore($id)
    {
        $category = DanhMucSanPham::findOrFail($id);

        if ($category->trang_thai != 3) {
            return redirect()->route('admins.category.index')->with('error', 'Danh mục không ở trạng thái lưu trữ.');
        }

        // Kiểm tra danh mục cha
        if ($category->danh_muc_cha_id) {
            $parent = DanhMucSanPham::find($category->danh_muc_cha_id);

            if ($parent) {
                if ($parent->trang_thai == 3) {
                    return redirect()->route('admins.category.index')
                        ->with('error', 'Vui lòng khôi phục danh mục cha trước khi khôi phục danh mục con này.');
                }

                if ($parent->trang_thai != 1) {
                    $category->trang_thai = 2; // không hoạt động
                    $category->save();

                    return redirect()->route('admins.category.index')
                        ->with('warning', 'Danh mục đã được khôi phục nhưng ở trạng thái "Không hoạt động" vì danh mục cha không hoạt động.');
                }
            }
        }

        // Nếu cha hoạt động hoặc không có cha
        $category->trang_thai = 1; // hoạt động
        $category->save();

        return redirect()->route('admins.category.index')->with('success', 'Danh mục đã được khôi phục thành công!');
    }



}
