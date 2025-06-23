<?php

namespace App\Http\Controllers\admins;

use App\Models\NguyenLieu;
use App\Models\NhaCungCap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminSupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = NhaCungCap::query();

        // Chỉ lấy những nhà cung cấp có trạng thái 1 hoặc 2
        $query->whereIn('trang_thai', [1, 2]);

        // Nếu có tìm kiếm tên
        if ($request->filled('search')) {
            $query->where('ten_nha_cung_cap', 'like', '%' . $request->search . '%');
        }

        //$suppliers = $query->paginate(10)->withQueryString();
        $perPage = 10;
        $currentPage = $request->input('page', 1);

        // Lấy phân trang ban đầu
        $suppliers = $query->paginate($perPage)->withQueryString();
        $lastPage = $suppliers->lastPage();

        // Nếu truy cập vượt quá trang cuối, redirect về trang cuối
        if ($currentPage > $lastPage && $lastPage > 0) {
            return redirect()->route('admins.supplier.index', array_merge(
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }


        return view('admins.supplier.index', [
            'title' => 'Danh Sách Nhà Cung Cấp',
            'subtitle' => 'Danh Sách Nhà Cung Cấp',
            'suppliers' => $suppliers,
        ]);
    }


    public function create() {
        $ViewData=[
            'title' => 'Thêm Nhà Cung Cấp',
            'subtitle' => 'Thêm Nhà Cung Cấp',
        ];
        return view('admins.supplier.create', $ViewData);
    }
    public function store(Request $request){
        $request->validate([
            'ten_nha_cung_cap' => 'required|string|max:255',
            'dia_chi' => 'nullable|string|max:255',
            'so_dien_thoai' => [
                'nullable',
                'regex:/^0\d{9}$/',
            ],
            'mail' => 'nullable|email|max:100',
        ], [
            'so_dien_thoai.regex' => 'Số điện thoại phải có đúng 10 chữ số và bắt đầu bằng số 0.',
            'mail.email' => 'tên email@example.com.',
        ]);

        // Kiểm tra trùng dữ liệu
        $exists = NhaCungCap::where('ten_nha_cung_cap', $request->ten_nha_cung_cap)
            ->orWhere('so_dien_thoai', $request->so_dien_thoai)
            ->orWhere('mail', $request->mail)
            ->orWhere('dia_chi', $request->dia_chi)
            ->exists();

        if ($exists) {
            toastr()->error('Tên, số điện thoại, địa chỉ hoặc email đã tồn tại!');
            return redirect()->back()->withInput();
        }

        try {
            NhaCungCap::create([
                'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
                'dia_chi' => $request->dia_chi,
                'so_dien_thoai' => $request->so_dien_thoai,
                'mail' => $request->mail,
                'trang_thai' => 1,
            ]);

            toastr()->success('Thêm nhà cung cấp thành công!');
            return redirect()->route('admins.supplier.index');
        } catch (\Exception $e) {
            Log::error('Lỗi thêm nhà cung cấp: ' . $e->getMessage());
            toastr()->error('Thêm nhà cung cấp thất bại. Vui lòng thử lại.');
            return redirect()->back()->withInput();
        }
    }
    public function edit($id)
    {
        $ncc = NhaCungCap::findOrFail($id);
        return view('admins.supplier.edit', [
            'title' => 'Chỉnh sửa nhà cung cấp',
            'subtitle' => 'Cập nhật thông tin',
            'ncc' => $ncc,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_nha_cung_cap' => 'required|string|max:255',
            'dia_chi' => 'required|string|max:255',
            'so_dien_thoai' => [
                'required',
                'regex:/^0\d{9}$/',
            ],
            'mail' => 'required|email|max:100',
        ], [
            'so_dien_thoai.regex' => 'Số điện thoại phải có đúng 10 chữ số và bắt đầu bằng số 0.',
            'mail.email' => 'Email không đúng định dạng example@domain.com.',
        ]);

        $ncc = NhaCungCap::findOrFail($id);

        $errors = [];

        // Kiểm tra từng trường trùng riêng biệt (ngoại trừ bản thân nó)
        if (NhaCungCap::where('ten_nha_cung_cap', $request->ten_nha_cung_cap)->where('ma_nha_cung_cap', '!=', $id)->exists()) {
            $errors['ten_nha_cung_cap'] = 'Tên đã tồn tại.';
        }
        if (NhaCungCap::where('so_dien_thoai', $request->so_dien_thoai)->where('ma_nha_cung_cap', '!=', $id)->exists()) {
            $errors['so_dien_thoai'] = 'Số điện thoại đã tồn tại.';
        }
        if (NhaCungCap::where('mail', $request->mail)->where('ma_nha_cung_cap', '!=', $id)->exists()) {
            $errors['mail'] = 'Email đã tồn tại.';
        }
        if (NhaCungCap::where('dia_chi', $request->dia_chi)->where('ma_nha_cung_cap', '!=', $id)->exists()) {
            $errors['dia_chi'] = 'Địa chỉ đã tồn tại.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try {
            $ncc->update([
                'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
                'dia_chi' => $request->dia_chi,
                'so_dien_thoai' => $request->so_dien_thoai,
                'mail' => $request->mail,
            ]);

            toastr()->success('Cập nhật nhà cung cấp thành công!');
            return redirect()->route('admins.supplier.index');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật nhà cung cấp: ' . $e->getMessage());
            toastr()->error('Cập nhật thất bại. Vui lòng thử lại!');
            return redirect()->back()->withInput();
        }
    }



    public function destroy($id)
    {
        $supplier = NhaCungCap::findOrFail($id);
        try {
            $supplier->delete();
            toastr()->success('Xóa nhà cung cấp thành công!');
        } catch (\Exception $e) {
            toastr()->error('Xóa nhà cung cấp thất bại. Vui lòng thử lại.');
        }

        return redirect()->route('admins.supplier.index');
    }
   public function archive($id)
    {
        $supplier = NhaCungCap::find($id);

        if (!$supplier) {
            toastr()->error('Không tìm thấy nhà cung cấp!');
            return redirect()->route('admins.supplier.index');
        }

        // Chỉ cho phép tạm xóa nếu đang ở trạng thái ngưng hoạt động (2)
        if ($supplier->trang_thai != 2) {
            toastr()->error('Chỉ có thể tạm xóa nhà cung cấp đang ở trạng thái "Ngưng hoạt động"!');
            return redirect()->route('admins.supplier.index');
        }

        // Kiểm tra nếu nhà cung cấp vẫn còn nguyên liệu đang hoạt động
        $hasActiveMaterials = NguyenLieu::where('ma_nha_cung_cap', $supplier->ma_nha_cung_cap)
            ->where('trang_thai', 1)
            ->exists();

        if ($hasActiveMaterials) {
            toastr()->error('Không thể tạm xóa nhà cung cấp vì vẫn còn nguyên liệu đang được cung cấp!');
            return redirect()->route('admins.supplier.index');
        }

        // Thực hiện lưu trữ
        $supplier->trang_thai = 3; // Trạng thái lưu trữ
        $supplier->save();

        toastr()->success('Tạm xóa nhà cung cấp thành công!');
        return redirect()->route('admins.supplier.index');
    }


    public function restore($id)
    {
        $supplier = NhaCungCap::findOrFail($id);

        if ($supplier->trang_thai != 3) {
            toastr()->info('Nhà cung cấp không ở trạng thái lưu trữ.');
            return redirect()->route('admins.supplier.archived');
        }
        $supplier->trang_thai = 1; // Phục hồi về trạng thái "hoạt động"
        $supplier->save();
        toastr()->success('Đã khôi phục nhà cung cấp thành công!');
        return redirect()->route('admins.supplier.archived');
    }

    public function archived(Request $request)
    {
        $query = NhaCungCap::where('trang_thai', 3);

        if ($request->filled('search')) {
            $query->where('ten_nha_cung_cap', 'like', '%' . $request->search . '%');
        }

        $suppliers = $query->get();

        return view('admins.supplier.archive', [
            'title' => 'Nhà cung cấp đã xóa',
            'subtitle' => 'Danh sách nhà cung cấp đã xóa',
            'suppliers' => $suppliers,
        ]);
    }

    public function toggleStatus($id)
    {
        $supplier = NhaCungCap::find($id);

        if (!$supplier) {
            toastr()->error('Không tìm thấy nhà cung cấp!');
            return redirect()->route('admins.supplier.index');
        }

        // Nếu đang bật và muốn tắt, kiểm tra nguyên liệu
        if ($supplier->trang_thai == 1) {
            $hasActiveMaterials = NguyenLieu::where('ma_nha_cung_cap', $supplier->ma_nha_cung_cap)
                ->where('trang_thai', 1)
                ->exists();

            if ($hasActiveMaterials) {
                toastr()->error('Không thể tắt nhà cung cấp vì vẫn còn nguyên liệu đang được cung cấp!');
                return redirect()->route('admins.supplier.index');
            }

            $supplier->trang_thai = 2; // Tắt hoạt động
        } elseif ($supplier->trang_thai == 2) {
            $supplier->trang_thai = 1; // Bật hoạt động
        }

        $supplier->save();
        toastr()->success('Cập nhật trạng thái thành công!');
        return redirect()->route('admins.supplier.index');
    }
    public function bulkRestore(Request $request)
    {
        $ids = $request->selected_ids;

        if (!$ids || !is_array($ids)) {
            return back()->with('info', 'Vui lòng chọn ít nhất một nhà cung cấp để khôi phục!');
        }

        NhaCungCap::whereIn('ma_nha_cung_cap', $ids)
            ->where('trang_thai', 3) // chỉ khôi phục những cái đã lưu trữ
            ->update(['trang_thai' => 2]); // đưa về trạng thái "ngưng hoạt động"
        toastr()->success('Khôi phục nhà cung cấp đã chọn thành công!');
        return back();
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->selected_ids;

        if (!$ids || !is_array($ids)) {
            return back()->with('info', 'Vui lòng chọn ít nhất một nhà cung cấp để xóa!');
        }

        NhaCungCap::whereIn('ma_nha_cung_cap', $ids)
            ->where('trang_thai', 3) // chỉ xóa nếu đang ở trạng thái lưu trữ
            ->delete();

        toastr()->success('Xóa nhà cung cấp đã chọn thành công!');
        return back();
    }
}
