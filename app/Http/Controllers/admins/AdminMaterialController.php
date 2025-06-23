<?php

namespace App\Http\Controllers\admins;

use App\Models\NguyenLieu;
use App\Models\NhaCungCap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;

class AdminMaterialController extends Controller
{

    // Hiển thị danh sách nguyên liệu
    public function index(Request $request)
    {
        $query = NguyenLieu::with('nhaCungCap');

        // Mặc định ẩn nguyên liệu đã "Xóa tạm" (trang_thai = 3)
        $query->where('trang_thai', '!=', 3);

        // Tìm kiếm theo mã, tên nguyên liệu, tên/mã nhà cung cấp
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ma_nguyen_lieu', 'like', "%{$search}%")
                ->orWhere('ten_nguyen_lieu', 'like', "%{$search}%")
                ->orWhereHas('nhaCungCap', function ($q2) use ($search) {
                    $q2->where('ten_nha_cung_cap', 'like', "%{$search}%")
                        ->orWhere('ma_nha_cung_cap', 'like', "%{$search}%");
                });
            });
        }

        //$page = $query->paginate(10)->appends($request->query());
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $materials = $query->paginate($perPage)->appends($request->query());
        $lastPage = $materials->lastPage();

        if ($currentPage > $lastPage && $lastPage > 0) {
            // Redirect về trang cuối nếu trang yêu cầu lớn hơn trang cuối
            return redirect()->route('admins.material.index', array_merge(
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }

        return view('admins.material.index', [
            'title' => 'Danh sách nguyên liệu',
            'subtitle' => 'Danh sách nguyên liệu',
            'materials' => $materials,
        ]);
    }

    // Hiển thị form tạo mới
    public function create()
    {
        $title = 'Thêm nguyên liệu';
        $subtitle = 'Thêm mới nguyên liệu';

        // Lấy mã lớn nhất hiện có (giả sử dạng: NL001, NL002, ...)
        $lastItem = NguyenLieu::orderByDesc('ma_nguyen_lieu')->first();

        if ($lastItem) {
            // Tách số phía sau
            $lastNumber = intval(substr($lastItem->ma_nguyen_lieu, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Sinh mã mới (vd: NL001)
        $newCode = 'NL' . str_pad($newNumber, 8, '0', STR_PAD_LEFT);

        // Lấy danh sách nhà cung cấp
        $suppliers = NhaCungCap::where('trang_thai', 1)->get();
        return view('admins.material.create', compact('title', 'subtitle', 'suppliers', 'newCode'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_nguyen_lieu' => 'required|string|max:255|min:2',
            'ma_nha_cung_cap' => 'required',
            'so_luong' => 'required|numeric|min:0',
            'gia' => 'required|numeric|min:0',
            'loai_nguyen_lieu' => 'required|in:0,1',
            'don_vi' => 'required|string|max:255',
            'trang_thai' => 'required|in:1,2,3',
            'is_ban_duoc' => 'nullable|in:0,1',
        ],
            [
                'ten_nguyen_lieu.required' => 'Tên nguyên liệu không được để trống.',
                'ten_nguyen_lieu.string' => 'Tên nguyên liệu phải là một chuỗi.',
                'ten_nguyen_lieu.max' => 'Tên nguyên liệu không được vượt quá 255 ký tự.',
                'ten_nguyen_lieu.min' => 'Tên nguyên liệu phải có ít nhất 2 ký tự.',
                'ma_nha_cung_cap.required' => 'Nhà cung cấp là bắt buộc.',
                'so_luong.required' => 'Số lượng không được để trống.',
                'so_luong.numeric' => 'Số lượng phải là một số.',
                'so_luong.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
                'gia.required' => 'Giá không được để trống.',
                'gia.numeric' => 'Giá phải là một số.',
                'gia.min' => 'Giá phải lớn hơn hoặc bằng 0.',
                'loai_nguyen_lieu.required' => 'Loại nguyên liệu không được để trống.',
                'loai_nguyen_lieu.in' => 'Loại nguyên liệu không hợp lệ.',
                'don_vi.required' => 'Đơn vị không được để trống.',
                'don_vi.string' => 'Đơn vị phải là một chuỗi.',
                'don_vi.max' => 'Đơn vị không được vượt quá 255 ký tự.',
            ]
        );

        // Kiểm tra trùng tên (không phân biệt hoa thường) và nhà cung cấp
        $isDuplicate = NguyenLieu::whereRaw('LOWER(ten_nguyen_lieu) = ?', [strtolower($validated['ten_nguyen_lieu'])])
            ->where('ma_nha_cung_cap', $validated['ma_nha_cung_cap'])
            ->exists();

        if ($isDuplicate) {
            toastr()->error('Nguyên liệu này đã tồn tại cho nhà cung cấp đã chọn.');
            return redirect()->back();
        }

        // Lấy mã nguyên liệu mới từ form (readonly)
        $validated['ma_nguyen_lieu'] = $request->ma_nguyen_lieu;
        $validated['slug'] = Str::slug($validated['ten_nguyen_lieu']);

        try {
        // Nếu là loại nguyên liệu (loai_nguyen_lieu = 0) thì nhân số lượng với 1000
        //if ($validated['loai_nguyen_lieu'] == 0) {$validated['so_luong'] *= 1000;  }



        NguyenLieu::create($validated);
        toastr()->success('Thêm nguyên liệu thành công!');
        return redirect()->back();
    } catch (\Exception $e) {
        toastr()->error('Nguyên liệu này đã tồn tại cho nhà cung cấp đã chọn.');
        return redirect()->back();
    }

    }
    // Hiển thị form chỉnh sửa nguyên liệu
    public function edit($id)
    {
        $nguyenLieu = NguyenLieu::where('ma_nguyen_lieu',$id)->first();
        $nhaCungCaps = NhaCungCap::all();

        return view('admins.material.edit', [
            'title' => 'Chỉnh sửa nguyên liệu',
            'subtitle' => 'Sửa thông tin nguyên liệu',
            'nguyenLieu' => $nguyenLieu,
            'nhaCungCaps' => $nhaCungCaps,
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_nguyen_lieu' => 'required|string|max:255|min:2',
            'ma_nha_cung_cap' => 'required|exists:nha_cung_caps,ma_nha_cung_cap',
            'so_luong' => 'required|integer|min:0',
            'gia' => 'required|numeric|min:0',
            'don_vi' => 'required|string|max:50',
            'loai_nguyen_lieu' => 'required|in:0,1',
            'trang_thai' => 'required|in:1,2,3',
        ],
            [
                'ten_nguyen_lieu.required' => 'Tên nguyên liệu không được để trống.',
                'ten_nguyen_lieu.string' => 'Tên nguyên liệu phải là một chuỗi.',
                'ten_nguyen_lieu.max' => 'Tên nguyên liệu không được vượt quá 255 ký tự.',
                'ten_nguyen_lieu.min' => 'Tên nguyên liệu phải có ít nhất 2 ký tự.',
                'ma_nha_cung_cap.exists' => 'Nhà cung cấp không tồn tại.',
                'so_luong.required' => 'Số lượng không được để trống.',
                'so_luong.integer' => 'Số lượng phải là một số nguyên.',
                'so_luong.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
                'gia.required' => 'Giá không được để trống.',
                'gia.numeric' => 'Giá phải là một số.',
                'gia.min' => 'Giá phải lớn hơn hoặc bằng 0.',
                'don_vi.required' => 'Đơn vị không được để trống.',
                'don_vi.string' => 'Đơn vị phải là một chuỗi.',
                'don_vi.max' => 'Đơn vị không được vượt quá 50 ký tự.',
                'loai_nguyen_lieu.required' => 'Loại nguyên liệu không được để trống.',
                'loai_nguyen_lieu.in' => 'Loại nguyên liệu không hợp lệ.',
                'trang_thai.required' => 'Trạng thái không được để trống.',
                'trang_thai.in' => 'Trạng thái không hợp lệ.'
            ]
        );

        $nguyenLieu = NguyenLieu::where('ma_nguyen_lieu',$id)->first();
        $nguyenLieu->update([
            'ten_nguyen_lieu' => $request->ten_nguyen_lieu,
            'slug' => Str::slug($request->ten_nguyen_lieu),
            'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
            'so_luong' => $request->so_luong,
            'gia' => $request->gia,
            'don_vi' => $request->don_vi,
            'loai_nguyen_lieu' => $request->loai_nguyen_lieu,
            'trang_thai' => $request->trang_thai,
        ]);
        toastr()->success('Cập nhật nguyên liệu thành công!');
        return redirect()->route('admins.material.index');
    }
    public function toggleStatus($id)
    {
        $material = NguyenLieu::findOrFail($id);
        // Chuyển đổi trạng thái: nếu đang là 1 thì thành 2, ngược lại thành 1
        if ($material->trang_thai == 1) {
            $material->trang_thai = 2;
        } elseif ($material->trang_thai == 2) {
            $material->trang_thai = 1;
        }

        $material->save();
        $material->cuaHangNguyenLieus()->update([
            'trang_thai' => $material->trang_thai
        ]);
        toastr()->success('Đã cập nhật trạng thái thành công.');
        return redirect()->back();
    }
    public function archive($id)
    {
        $material = NguyenLieu::with(['cuaHangNguyenLieus', 'products'])->findOrFail($id);

        $productInUse = $material->products->filter(function ($product) {
            return $product->trang_thai == 1;
        })->first();

        if ($productInUse) {
            toastr()->error('Không thể xóa. Nguyên liệu đang được dùng trong sản phẩm đang bán: ' . $productInUse->ten_san_pham);
            return back();
        }

        // 2. Kiểm tra còn tồn kho không
        $tonKho = $material->cuaHangNguyenLieus->sum('so_luong_ton');
        if ($tonKho > 0) {
            toastr()->error('Không thể xóa. Nguyên liệu vẫn còn trong kho: ' . $tonKho . ' ' . $material->don_vi);
            return back();
        }

        // 3. Xóa mềm (Soft Delete)
        $material->delete();
        toastr()->success('Đã xóa nguyên liệu thành công!!!');
        return back();
    }


    public function archiveIndex(Request $request)
    {
        $query = NguyenLieu::onlyTrashed();

        if ($request->has('search') && !empty($request->search)) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('ma_nguyen_lieu', 'like', "%$keyword%")
                ->orWhere('ten_nguyen_lieu', 'like', "%$keyword%")
                ->orWhereHas('nhaCungCap', function ($q2) use ($keyword) {
                    $q2->where('ten_nha_cung_cap', 'like', "%$keyword%");
                });
            });
        }

        $viewData = [
            'title' => 'Danh sách xóa tạm nguyên liệu',
            'subtitle' => 'Nguyên Liệu xóa tạm',
            'materials' => $query->get(),
        ];

        return view('admins.material.archive', $viewData);
    }



    public function restore($id)
    {
        $material = NguyenLieu::withTrashed()->find($id);

        if (!$material) {
            toastr()->error('Không tìm thấy nguyên liệu');
            return redirect()->back();
        }

        $material->restore();
        toastr()->success('Khôi phục thành công!');
        return redirect()->route('admins.material.index');
    }
    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) {
            toastr()->warning('Vui lòng chọn ít nhất 1 nguyên liệu.');
            return redirect()->back();
        }

        if ($action === 'restore') {
            NguyenLieu::withTrashed()->whereIn('id', $ids)->restore();
            toastr()->success('Đã khôi phục các nguyên liệu được chọn.');
        } elseif ($action === 'delete') {
            NguyenLieu::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            toastr()->success('Đã xóa vĩnh viễn các nguyên liệu được chọn.');
        } else {
            toastr()->error('Hành động không hợp lệ.');
        }

        return redirect()->back();
    }


    //ổn cái xóa
    public function destroy($id)
    {
        $material = NguyenLieu::find($id);

        if (!$material) {
            toastr()->error('Không tìm thấy nguyên liệu!');
            return redirect()->back();
        }

        try {
            $material->delete();
            toastr()->success('Xóa nguyên liệu thành công!');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa thất bại: ' . $e->getMessage());
        }
    }






}


