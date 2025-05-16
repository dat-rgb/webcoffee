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
    public function index() {
        $ViewData = [
            'title' => 'Danh Sách Nhà Cung Cấp',
            'subtitle' => 'Danh Sách Nhà Cung Cấp',
            'suppliers' => NhaCungCap::all(),
        ];

        return view('admins.supplier.index', $ViewData);
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
            toastr()->error('Nhà cung cấp không tồn tại.');
            return redirect()->route('admins.supplier.index');
        }

        $supplier->trang_thai = 3;
        $supplier->save();

        toastr()->success('Nhà cung cấp đã được lưu trữ.');
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

    public function archived()
    {
        $suppliers = NhaCungCap::where('trang_thai', 3)->get();

        return view('admins.supplier.archive', [
            'title' => 'Danh sách Nhà cung cấp đã lưu trữ',
            'subtitle' => 'Danh sách nhà cung cấp trạng thái lưu trữ',
            'suppliers' => $suppliers,
        ]);
    }

    public function toggleStatus($id)
    {
        $supplier = NhaCungCap::find($id);

        if ($supplier->trang_thai == 1) {
            $supplier->trang_thai = 2; // Tắt hoạt động
        } elseif ($supplier->trang_thai == 2) {
            $supplier->trang_thai = 1; // Bật hoạt động
        }

        $supplier->save();

        return redirect()->route('admins.supplier.index')->with('success', 'Cập nhật trạng thái thành công');
    }


}
