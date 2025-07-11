<?php

namespace App\Http\Controllers\admins;

use App\Models\DanhMucSanPham;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{

    public function index(Request $request)
    {
        $query = DanhMucSanPham::with('parent'); // eager loading parent danh mục

        if ($request->has('trang_thai') && in_array($request->trang_thai, [1, 2, 3])) {
            $query->where('trang_thai', $request->trang_thai);
        } else {
            // Mặc định không lấy danh mục lưu trữ (3)
            $query->where('trang_thai', '!=', 3);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_danh_muc', 'like', "%{$search}%")
                ->orWhere('ma_danh_muc', 'like', "%{$search}%");
            });
        }

        $categories = $query->paginate(10);
        if ($categories->isEmpty() && $request->has('search') && $request->search != '') {
            toastr()->warning('Không tìm thấy danh mục với từ khóa "' . $request->search . '".');
        }

        return view('admins.category.index', [
            'categories' => $categories,
            'title' => 'Danh mục',
            'subtitle' => 'Danh Sách Danh Mục'
        ]);
    }
    // Hiển thị form tạo mới
    public function create()
    {
        $title = 'Tạo danh mục';
        $subtitle = 'Tạo danh mục mới';
        $categories = DanhMucSanPham::where('trang_thai', 1)->get();

        $viewData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'categories' => $categories,
        ];

        return view('admins.category.create', $viewData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required|string|max:255|unique:danh_muc_san_phams,ten_danh_muc',
            'mo_ta' => 'nullable|string',
            'danh_muc_cha_id' => 'nullable|exists:danh_muc_san_phams,ma_danh_muc,trang_thai,1',
            'trang_thai' => 'required|in:1,2',
        ], [
            'ten_danh_muc.required' => 'Tên danh mục không được để trống.',
            'ten_danh_muc.unique' => 'Tên danh mục đã tồn tại.',
            'danh_muc_cha_id.exists' => 'Danh mục cha không hợp lệ hoặc đang bị ẩn.',
            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ]);

        DanhMucSanPham::create([
            'ten_danh_muc' => $request->ten_danh_muc,
            'slug' => Str::slug($request->ten_danh_muc),
            'anh_dai_dien' => null,
            'danh_muc_cha_id' => $request->danh_muc_cha_id,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => $request->trang_thai,
        ]);

        toastr()->success('Tạo danh mục thành công!');
        return redirect()->route('admins.category.index');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $category = DanhMucSanPham::findOrFail($id);
        $categories = DanhMucSanPham::all();
        $descendantIds = $category->getAllDescendants()->pluck('ma_danh_muc')->toArray();

        $viewData = [
            'category' => $category,
            'categories' => $categories,
            'descendantIds' => $descendantIds,
            'title' => 'Chỉnh sửa danh mục',
            'subtitle' => 'Chỉnh sửa danh mục',
        ];

        return view('admins.category.edit', $viewData);
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
        ], [
            'ten_danh_muc.required' => 'Tên danh mục không được để trống.',
            'ten_danh_muc.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'ten_danh_muc.unique' => 'Tên danh mục đã tồn tại.',
            'mo_ta.string' => 'Mô tả phải là chuỗi.',
            'danh_muc_cha_id.exists' => 'Danh mục cha không hợp lệ.',
            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ]);
        $newParentId = $request->danh_muc_cha_id;
        //  Không thể là chính mình
        if ($newParentId == $id) {
            return back()->withErrors(['danh_muc_cha_id' => 'Danh mục không thể là cha của chính nó!'])->withInput();
        }
        //  Không thể chọn cha là một danh mục con/cháu/chắt của chính mình
        if ($newParentId && $this->isDescendant($newParentId, $id)) {
            return back()->withErrors(['danh_muc_cha_id' => 'Không thể gán danh mục con/cháu làm cha của mình!'])->withInput();
        }

        $category->update([
            'ten_danh_muc' => $request->ten_danh_muc,
            'slug' => Str::slug($request->ten_danh_muc),
            'danh_muc_cha_id' => $newParentId,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => $request->trang_thai,
        ]);

        if ($request->trang_thai == 2) {
            $category->deactivateChildren();
        }
        if ($request->trang_thai == 2) {
            $category->sanPhams()->update(['trang_thai' => 2]);
            foreach ($category->getAllDescendants() as $child) {
                $child->sanPhams()->update(['trang_thai' => 2]);
            }
        }
        elseif ($request->trang_thai == 1) {
            $category->sanPhams()->where('trang_thai', 2)->update(['trang_thai' => 1]);
            foreach ($category->getAllDescendants() as $child) {
                $child->sanPhams()->where('trang_thai', 2)->update(['trang_thai' => 1]);
            }
        }

        toastr()->success('Cập nhật danh mục thành công');
        return redirect()->route('admins.category.index');
    }

    // Hàm kiểm tra: $potentialParentId có phải là con/cháu/chắt của $categoryId không?
    private function isDescendant($potentialParentId, $categoryId)
    {
        $children = DanhMucSanPham::where('danh_muc_cha_id', $categoryId)->get();

        foreach ($children as $child) {
            if ($child->ma_danh_muc == $potentialParentId) {
                return true; // chính là nó
            }
            if ($this->isDescendant($potentialParentId, $child->ma_danh_muc)) {
                return true; // nằm sâu hơn
            }
        }
        return false;
    }
    // Xóa danh mục
    public function destroy($id)
    {
        $category = DanhMucSanPham::findOrFail($id);
        $category->delete();
        toastr()->success('Xóa danh mục thành công !');
        return redirect()->route('admins.category.index');
    }
    public function archive($id)
{
    $category = DanhMucSanPham::findOrFail($id);

    // Lấy toàn bộ con/cháu
    $descendants = $category->getAllDescendants();
    $all = collect([$category])->merge($descendants);

    foreach ($all as $dm) {
        if ($dm->sanPhams()->exists()) {
            toastr()->error("Không thể lưu trữ danh mục '{$dm->ten_danh_muc}' vì đang chứa sản phẩm.");
            return redirect()->route('admins.category.index');
        }
    }

    // Nếu không có sản phẩm liên quan
    $category->archiveWithChildren();
    toastr()->success('Đã chuyển danh mục vào thùng rác.');
    return redirect()->route('admins.category.index');
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
            toastr()->error('Danh mục không ở trạng thái lưu trữ.');
            return redirect()->route('admins.category.index');
        }
        // Kiểm tra danh mục cha
        if ($category->danh_muc_cha_id) {
            $parent = DanhMucSanPham::find($category->danh_muc_cha_id);
            if ($parent) {
                if ($parent->trang_thai == 3) {
                    toastr()->error('Vui lòng khôi phục danh mục cha trước khi khôi phục danh mục con này.');
                    return redirect()->route('admins.category.index');
                }
                if ($parent->trang_thai != 1) {
                    $category->trang_thai = 2; // không hoạt động
                    $category->save();
                    toastr()->warning('Danh mục đã được khôi phục nhưng ở trạng thái "Không hoạt động" vì danh mục cha không hoạt động.');
                    return redirect()->route('admins.category.index');
                }
            }
        }
        // Nếu cha hoạt động hoặc không có cha
        $category->trang_thai = 1; // hoạt động
        $category->save();
        toastr()->success('Danh mục đã được khôi phục thành công!');
        return redirect()->route('admins.category.index');
    }
    public function bulkArchive(Request $request)
{
    $ids = $request->input('selected_ids', []);
    if (empty($ids)) {
        toastr()->warning('Bạn cần chọn ít nhất 1 danh mục để thực hiện chức năng.');
        return back();
    }

    $errors = [];

    foreach ($ids as $id) {
        $category = DanhMucSanPham::find($id);
        if (!$category) continue;

        $descendants = $category->getAllDescendants();
        $all = collect([$category])->merge($descendants);

        $hasSanPham = false;

        foreach ($all as $dm) {
            if ($dm->sanPhams()->exists()) {
                $errors[] = "Không thể tạm xóa danh mục '{$dm->ten_danh_muc}' vì đang chứa sản phẩm.";
                $hasSanPham = true;
                break;
            }
        }

        if (!$hasSanPham) {
            $category->archiveWithChildren();
        }
    }

    if (!empty($errors)) {
        toastr()->error(implode('<br>', $errors));
    } else {
        toastr()->success('Đã tạm xóa các danh mục được chọn.');
    }

    return back();
}

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            toastr()->warning('Bạn cần chọn ít nhất 1 danh mục để thực hiện chức năng khôi phục.');
            return back();
        }

        $selectedIdsSet = collect($ids)->map(fn($i) => (int)$i)->toArray();
        $hasError = false;
        $errorMessages = [];

        foreach ($ids as $id) {
            $category = DanhMucSanPham::find($id);
            if ($category && $category->trang_thai == 3) {
                if ($category->danh_muc_cha_id) {
                    $parentId = $category->danh_muc_cha_id;
                    $parent = DanhMucSanPham::find($parentId);

                    if ($parent && $parent->trang_thai == 3) {
                        // Nếu cha chưa được chọn trong danh sách thì báo lỗi
                        if (!in_array($parentId, $selectedIdsSet)) {
                            $hasError = true;
                            $errorMessages[] = "Vui lòng khôi phục danh mục cha '{$parent->ten_danh_muc}' trước khi khôi phục danh mục con '{$category->ten_danh_muc}'.";
                        }
                    }
                }
            }
        }
        if ($hasError) {
            toastr()->error(implode('<br>', $errorMessages));
            return back();
        }
        // Bắt đầu khôi phục
        foreach ($ids as $id) {
            $category = DanhMucSanPham::find($id);
            if ($category && $category->trang_thai == 3) {
                $category->trang_thai = 1;
                $category->save();
            }
        }
        toastr()->success('Đã khôi phục các danh mục đã chọn thành công.');
        return back();
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        DanhMucSanPham::whereIn('ma_danh_muc', $ids)->delete();
        toastr()->success('Đã xóa các danh mục đã chọn.');
        return back();
    }



   public function bulkToggleStatus(Request $request)
{
    $ids = $request->input('selected_ids', []);
    if (empty($ids)) {
        toastr()->warning('Vui lòng chọn ít nhất một danh mục.');
        return back();
    }

    $errors = [];

    foreach ($ids as $id) {
        $category = DanhMucSanPham::find($id);
        if (!$category) continue;

        $currentStatus = $category->trang_thai;
        $newStatus = ($currentStatus == 1) ? 2 : 1;

        // Nếu bật lại (1), kiểm tra cha có đang hoạt động không
        if ($newStatus == 1 && $category->danh_muc_cha_id) {
            $parent = DanhMucSanPham::find($category->danh_muc_cha_id);
            if ($parent && $parent->trang_thai != 1) {
                $errors[] = "Không thể bật danh mục '{$category->ten_danh_muc}' vì danh mục cha đang không hoạt động.";
                continue;
            }
        }

        if ($newStatus == 2) {
            // Nếu là tắt → tắt cả cây con/cháu
            $descendants = $this->getAllDescendants($category->ma_danh_muc);
            $allIds = collect([$category->ma_danh_muc])->merge($descendants)->unique();

            DanhMucSanPham::whereIn('ma_danh_muc', $allIds)->update(['trang_thai' => $newStatus]);
            foreach ($allIds as $catId) {
                $dm = DanhMucSanPham::find($catId);
                if ($dm) {
                    $dm->sanPhams()->update(['trang_thai' => $newStatus]);
                }
            }
        } else {
            // Nếu là bật → chỉ bật chính danh mục đó
            $category->update(['trang_thai' => 1]);
            $category->sanPhams()->update(['trang_thai' => 1]);
        }
    }

    if (count($errors)) {
        toastr()->error(implode('<br>', $errors));
    } else {
        toastr()->success('Đã chuyển đổi trạng thái các danh mục đã chọn.');
    }

    return back();
}


// Hàm đệ quy để lấy tất cả ID con/cháu của danh mục
private function getAllDescendants($parentId)
{
    $children = DanhMucSanPham::where('danh_muc_cha_id', $parentId)->get();
    $ids = collect();

    foreach ($children as $child) {
        $ids->push($child->ma_danh_muc);
        $ids = $ids->merge($this->getAllDescendants($child->ma_danh_muc)); // gọi đệ quy tiếp
    }

    return $ids;
}



}
