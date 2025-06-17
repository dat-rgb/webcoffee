<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\DanhMucSanPham;
use App\Models\NguyenLieu;
use App\Models\SanPham;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\List_;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class AdminProductController extends Controller
{
    //lấy ds danh mục sản phẩm
    public function getCategory(){
        $categorys = DanhMucSanPham::where('trang_thai',1)->get();
        return $categorys;
    }
    //lấy danh sách size
    public function getSizeProduct($proId){
        $sizes = DB::table('thanh_phan_san_phams')
            ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
            ->where('thanh_phan_san_phams.ma_san_pham', $proId)
            ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
            ->distinct()
            ->get();
        return $sizes;
    }
    //lấy danh sách nguyên liệu
    public function getIngredient(){
        $ingredients = NguyenLieu::where('trang_thai',1)->get();
        return $ingredients;
    }
    //lấy ds sản phẩm theo trạng thái
    public function getProductsByStatus($status, $search = null, $maDanhMuc = null) {
        $query = SanPham::with('danhMuc')
            ->where('trang_thai', $status)
            ->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ten_san_pham', 'like', "%{$search}%")
                ->orWhere('ma_san_pham', 'like', "%{$search}%")
                ->orWhereHas('danhMuc', function($q2) use ($search) {
                    $q2->where('ten_danh_muc', 'like', "%{$search}%");
                })
                ->orWhere('mo_ta', 'like', "%{$search}%"); 
            });
        }

        if ($maDanhMuc) {
            $query->where('ma_danh_muc', $maDanhMuc);
        }

        return $query->paginate(15);
    }
    // Hiển thị danh sách sản phẩm (trạng thái 1)
    public function listProducts(Request $request) {
        $search = $request->input('search');
        
        //$products = $this->getProductsByStatus(1, $search);
        $products = $this->getProductsByStatus(
            status: 1,
            search: $request->search,
            maDanhMuc: $request->ma_danh_muc
        );

        $categories = $this->getCategory();
        $sizesMap = [];
        foreach ($products as $pro) {
            $sizesMap[$pro->ma_san_pham] = $this->getSizeProduct($pro->ma_san_pham);
        }

        $viewData = [
            'title' => 'Quản lý sản phẩm | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách sản phẩm',
            'categories' => $categories,
            'products' => $products,
            'sizesMap' => $sizesMap,
            'search' => $search 
        ];
        return view('admins.products.index', $viewData);
    }
    // Hiển thị danh sách sản phẩm ẩn (trạng thái 2)
    public function listProductsHidden(Request $request){
        $search = $request->input('search');
        $products = $this->getProductsByStatus(2, $search);
        $categories = $this->getCategory();
        $sizesMap = [];
        foreach ($products as $pro) {
            $sizesMap[$pro->ma_san_pham] = $this->getSizeProduct($pro->ma_san_pham);
        }

        $viewData = [
            'title' => 'Quản lý sản phẩm | CDMT Coffee & Tea',
            'subtitle' => 'Sản phẩm đã ẩn',
            'categories' => $categories,
            'products' => $products,
            'sizesMap' => $sizesMap,
            'search' => $search
        ];

        return view('admins.products.index', $viewData);
    }
    //show form 
    public function showProductForm(){

        $categorys = $this->getCategory();
        $ingredients = NguyenLieu::where('is_ban_duoc',1)->where('trang_thai',1)->get();
        $lastItem = SanPham::withTrashed()->orderByDesc('ma_san_pham')->first();

        if ($lastItem) {
            $lastNumber = intval(substr($lastItem->ma_san_pham, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newCode = 'SP' . str_pad($newNumber, 8, '0', STR_PAD_LEFT);

        $viewData = [
            'title' => 'Thêm sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Thêm sản phẩm',
            'categorys' => $categorys,
            'ingredients' =>$ingredients,
            'newCode' => $newCode
        ];

        return view('admins.products.product_form', $viewData);
    }
    //Thêm sản phẩm mới
    public function productAdd(Request $request)
    {
        //dd($request);
        $request->validate([
            'ma_san_pham' => 'required|string|size:10|unique:san_phams,ma_san_pham',
            'ten_san_pham' => 'required|string|max:255|min:2',
            'ma_danh_muc' => 'required',
            'hinh_anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gia' => 'required|numeric|min:0|max:10000000',
            'mo_ta' => 'nullable|string|max:1000',
            'loai_san_pham' => 'required|in:0,1',
            'ma_nguyen_lieu' => $request->loai_san_pham == 1 ? 'required|exists:nguyen_lieus,ma_nguyen_lieu' : 'nullable',
        ], [
            'ma_san_pham.required' => 'Mã sản phẩm là bắt buộc.',
            'ma_san_pham.size' => 'Mã sản phẩm phải có đúng 10 ký tự.',
            'ma_san_pham.unique' => 'Mã sản phẩm đã được sử dụng.',
            'ten_san_pham.required' => 'Tên sản phẩm là bắt buộc.',
            'ten_san_pham.min' => 'Tên sản phẩm phải ít nhất 2 ký tự.',
            'ten_san_pham.max' => 'Tên sản phẩm không quá 255 ký tự.',
            'ma_danh_muc.required' => 'Danh mục sản phẩm là bắt buộc.',
            'hinh_anh.image' => 'Tệp phải là ảnh.',
            'hinh_anh.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'hinh_anh.max' => 'Ảnh không quá 2MB.',
            'gia.required' => 'Giá sản phẩm là bắt buộc.',
            'gia.numeric' => 'Giá sản phẩm phải là một số.',
            'gia.min' => 'Giá sản phẩm phải lớn hơn 0.',
            'gia.max' => 'Giá sản phẩm không quá 10 triệu.',
            'mo_ta.max' => 'Mô tả không quá 1000 ký tự.',
            'loai_san_pham.required' => 'Vui lòng chọn loại sản phẩm.',
            'ma_nguyen_lieu.required' => 'Vui lòng chọn nguyên liệu cho sản phẩm đóng gói.',
            'ma_nguyen_lieu.exists' => 'Nguyên liệu không tồn tại.',
        ]);

        $hot = $request->hot;
        $new = $request->is_new;
        $loaiSP = $request->loai_san_pham;
        
        $slug = Str::slug($request->ten_san_pham);

        if (SanPham::withTrashed()->where('slug', $slug)->exists()) {
            toastr()->error('Tên sản phẩm đã trùng, vui lòng chọn tên khác');
            return redirect()->back()->withInput();
        }

        // Xử lý ảnh
        $imagePath = null;
        if ($request->hasFile('hinh_anh')) {
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products', $image, $imageName);
            $imagePath = 'products/' . $imageName;
        }

        // Tạo sản phẩm
        $sanPham = SanPham::create([
            'ma_san_pham' => $request->ma_san_pham,
            'ten_san_pham' => $request->ten_san_pham,
            'ma_danh_muc' => $request->ma_danh_muc,
            'slug' => $slug,
            'gia' => $request->gia,
            'trang_thai' => $request->trang_thai,
            'mo_ta' => $request->mo_ta,
            'hinh_anh' => $imagePath,
            'hot' => $hot,
            'loai_san_pham' => $loaiSP,
            'is_new' => $new,
        ]);

         // Nếu là sản phẩm đóng gói
         if ($loaiSP == 1) {
            ThanhPhanSanPham::create([
                'ma_san_pham'     => $request->ma_san_pham,
                'ma_nguyen_lieu'  => $request->ma_nguyen_lieu,
                'ma_size'         => null,
                'dinh_luong'      => 1, // 1 đơn vị nguyên liệu
                'don_vi'          => null,
            ]);
        }

        toastr()->success('Thêm sản phẩm thành công.');
        return redirect()->route('admin.products.list');
    }
    //show thành phần
    public function showProductAddIngredients($slug){
        $product = SanPham::where('slug', $slug)->first();
        $sizes = Sizes::where('trang_thai',1)->get();
        $ingredients = $this->getIngredient();

        if(!$product){
            toastr()->error('Sản phẩm không tồn tại');
            return redirect()->back();
        }

        $viewData = [
            'title' => 'Thêm thành phần sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Thêm thành phần sản phẩm',
            'product' => $product,
            'ingredients' =>$ingredients,
            'sizes' => $sizes
        ];

        return view('admins.products.product_ingredients', $viewData);
    }
    //Thêm thành phần sản phẩm
    public function productAddIngredients(Request $request) {
        $request->validate([
            'ma_san_pham' => 'required|string',
            'sizes' => 'required|array|min:1',
            'ingredients' => 'required|array',
            'dinh_luongs' => 'required|array',
            'don_vis' => 'required|array',
        ], [
            'ma_san_pham.required' => 'Mã sản phẩm là bắt buộc.',
            'ma_san_pham.string' => 'Mã sản phẩm phải là chuỗi.',
            'sizes.required' => 'Phải chọn ít nhất 1 size.',
            'sizes.array' => 'Size phải là một mảng.',
            'ingredients.required' => 'Nguyên liệu là bắt buộc.',
            'ingredients.array' => 'Nguyên liệu phải là một mảng.',
            'dinh_luongs.required' => 'Định lượng là bắt buộc.',
            'dinh_luongs.array' => 'Định lượng phải là một mảng.',
            'don_vis.required' => 'Đơn vị là bắt buộc.',
            'don_vis.array' => 'Đơn vị phải là một mảng.',
        ]);

        $productId = $request->input('ma_san_pham');
        $sizes = $request->input('sizes');

        $insertedCount = 0;
        $duplicateCount = 0;

        try {
            foreach ($sizes as $size) {
                $ings = $request->input("ingredients.$size", []);
                $dls = $request->input("dinh_luongs.$size", []);
                $dvs = $request->input("don_vis.$size", []);

                foreach ($ings as $index => $ingredientId) {
                    $quantity = $dls[$index] ?? null;
                    $unit = $dvs[$index] ?? null;

                    if ($ingredientId && $quantity && $unit) {
                        // Kiểm tra trùng
                        $exists = ThanhPhanSanPham::where([
                            ['ma_san_pham', '=', $productId],
                            ['ma_size', '=', $size],
                            ['ma_nguyen_lieu', '=', $ingredientId],
                        ])->exists();

                        if ($exists) {
                            $duplicateCount++;
                            continue;
                        }

                        // Thêm mới
                        ThanhPhanSanPham::create([
                            'ma_san_pham' => $productId,
                            'ma_size' => $size,
                            'ma_nguyen_lieu' => $ingredientId,
                            'dinh_luong' => $quantity,
                            'don_vi' => $unit,
                        ]);
                        $insertedCount++;
                    }
                }
            }

            if ($insertedCount > 0) {
                toastr()->success("Đã thêm $insertedCount nguyên liệu thành công!");
            }

            if ($duplicateCount > 0) {
                toastr()->warning("$duplicateCount nguyên liệu đã tồn tại và không được thêm lại.");
            }

            if ($insertedCount == 0 && $duplicateCount == 0) {
                toastr()->error('Không có nguyên liệu nào được thêm. Vui lòng kiểm tra lại!');
            }

        } catch (\Exception $e) {
            toastr()->error('Đã xảy ra lỗi: ' . $e->getMessage());
            // Có thể log thêm $e nếu cần
        }

        return redirect()->route('admin.products.list');
    }
    //show form thành phần sản phẩm
    public function showProductIngredients(Request $request, $proId)
    {
        $product = SanPham::where('ma_san_pham', $proId)->first();
        if (!$product) {
            toastr()->error('Không tìm thấy sản phẩm.');
            return redirect()->back();
        }

        $sizes = Sizes::all();
        $ingredients = $this->getIngredient();

        // Lấy các thành phần đã tồn tại của sản phẩm theo từng size
        $existingIngredients = ThanhPhanSanPham::where('ma_san_pham', $proId)
            ->get()
            ->groupBy('ma_size');

      
        $selectedSizes = $existingIngredients->keys()->toArray();

        return view('admins.products.product_ingredients_edit', [
            'title' => 'Thành phần sản phẩm',
            'subtitle' => 'Thêm thành phần nguyên liệu cho sản phẩm',
            'product' => $product,
            'sizes' => $sizes,
            'ingredients' => $ingredients,
            'existingIngredients' => $existingIngredients,
            'selectedSizes' => $selectedSizes 
        ]);
    }
    //cập nhật thành phần sản phẩm
    public function productUpdateIngredients(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|string',
            'sizes' => 'required|array|min:1',
            'ingredients' => 'required|array',
            'dinh_luongs' => 'required|array',
            'don_vis' => 'required|array',
        ]);

        $productId = $request->input('ma_san_pham');
        $sizes = $request->input('sizes');

        $updatedCount = 0;
        $insertedCount = 0;

        try {
            foreach ($sizes as $size) {
                $ings = $request->input("ingredients.$size", []);
                $dls = $request->input("dinh_luongs.$size", []);
                $dvs = $request->input("don_vis.$size", []);

                foreach ($ings as $index => $ingredientId) {
                    $quantity = $dls[$index] ?? null;
                    $unit = $dvs[$index] ?? null;

                    if ($ingredientId && $quantity && $unit) {
                        // Kiểm tra nếu đã tồn tại thì cập nhật
                        $existing = ThanhPhanSanPham::where([
                            ['ma_san_pham', '=', $productId],
                            ['ma_size', '=', $size],
                            ['ma_nguyen_lieu', '=', $ingredientId],
                        ])->first();

                        if ($existing) {
                            $existing->update([
                                'dinh_luong' => $quantity,
                                'don_vi' => $unit,
                            ]);
                            $updatedCount++;
                        } else {
                            // Chưa có thì thêm mới
                            ThanhPhanSanPham::create([
                                'ma_san_pham' => $productId,
                                'ma_size' => $size,
                                'ma_nguyen_lieu' => $ingredientId,
                                'dinh_luong' => $quantity,
                                'don_vi' => $unit,
                            ]);
                            $insertedCount++;
                        }
                    }
                }
            }

            // Gửi thông báo
            if ($updatedCount > 0) {
                toastr()->success("Đã cập nhật $updatedCount nguyên liệu.");
            }

            if ($insertedCount > 0) {
                toastr()->info("Đã thêm mới $insertedCount nguyên liệu.");
            }

            if ($updatedCount == 0 && $insertedCount == 0) {
                toastr()->warning('Không có dữ liệu nào được thay đổi.');
            }

        } catch (\Exception $e) {
            toastr()->error('Đã xảy ra lỗi: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.list');
    }
    //Ẩn/hiện 
    public function productHiddenOrAcctive($proId) {
        $product = SanPham::where('ma_san_pham',$proId)->first();

        if (!$product) {
            toastr()->error('Sản phẩm không tồn tại');
            return redirect()->back();
        }

        if ($product->trang_thai == 3) {
            toastr()->error('Không thể ẩn sản phẩm đã lưu trữ');
            return redirect()->back();
        }

        if ($product->trang_thai == 1) {
            $product->update(['trang_thai' => 2]);
            toastr()->success('Sản phẩm đã được ẩn');
        } else {
            $product->update(['trang_thai' => 1]);
            toastr()->success('Sản phẩm đã được hiển thị');
        }

        return redirect()->back();
    }
    //thao tác nhanh
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $productIds = $request->input('selected_products', []);

        if (empty($productIds)) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng chọn ít nhất một sản phẩm']);
        }

        if ($action === 'restore') {
            $products = SanPham::onlyTrashed()->whereIn('ma_san_pham', $productIds)->get();
            foreach ($products as $product) {
                $product->restore();
            }
        } elseif ($action === 'force-delete') {
            $products = SanPham::onlyTrashed()->whereIn('ma_san_pham', $productIds)->get();
            foreach ($products as $product) {
                $product->forceDelete();
            }
        } else {
            $products = SanPham::whereIn('ma_san_pham', $productIds)->get();
            foreach ($products as $product) {
                if ($action === 'hide') {
                    $product->update(['trang_thai' => 2]);
                } elseif ($action === 'show') {
                    $product->update(['trang_thai' => 1]);
                } elseif ($action === 'delete') {
                    $product->delete(); // Xoá mềm
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Đã thực hiện thao tác thành công']);
    }
    //show form cập nhật
    public function showProductEdit($proId) {
        $product = SanPham::where('ma_san_pham', $proId)->first();
        if (!$product) {
            toastr()->error('Không tìm thấy sản phẩm.');
            return redirect()->back();
        }

        // Lấy danh mục để select trong form
        $categorys = $this->getCategory();

        $viewData = [
            'title' => 'Chỉnh sửa sản phẩm ' . $product->ma_san_pham . ' | CMDT Coffee & Tea',
            'subtitle' => 'Chỉnh sửa sản phẩm ' . $product->ma_san_pham,
            'product' => $product,
            'categorys' => $categorys
        ];

        return view('admins.products.product_edit', $viewData);
    }
    //Cập nhật sản phẩm
    public function updateProduct(Request $request, $proId)
    {
        $product = SanPham::where('ma_san_pham', $proId)->first();
        if (!$product) {
            toastr()->error('Không tìm thấy sản phẩm!');
            return redirect()->back();
        }

        $request->validate([
            // Bỏ qua id hiện tại khi check unique ma_san_pham
            'ma_san_pham' => 'required|string|size:10|unique:san_phams,ma_san_pham,' . $product->id,
            'ten_san_pham' => 'required|string|max:255|min:2',
            'ma_danh_muc' => 'required',
            'hinh_anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gia' => 'required|numeric|min:0|max:10000000',
            'mo_ta' => 'nullable|string|max:1000',
        ], [
            'ma_san_pham.required' => 'Mã sản phẩm là bắt buộc.',
            'ma_san_pham.size' => 'Mã sản phẩm phải có đúng 10 ký tự.',
            'ma_san_pham.unique' => 'Mã sản phẩm đã được sử dụng.',
            'ten_san_pham.required' => 'Tên sản phẩm là bắt buộc.',
            'ten_san_pham.min' => 'Tên sản phẩm phải ít nhất 2 ký tự.',
            'ten_san_pham.max' => 'Tên sản phẩm không quá 255 ký tự.',
            'ma_danh_muc.required' => 'Danh mục sản phẩm là bắt buộc.',
            'hinh_anh.image' => 'Tệp phải là ảnh.',
            'hinh_anh.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'hinh_anh.max' => 'Ảnh không quá 2MB.',
            'gia.required' => 'Giá sản phẩm là bắt buộc.',
            'gia.numeric' => 'Giá sản phẩm phải là một số.',
            'gia.min' => 'Giá sản phẩm phải lớn hơn 0.',
            'gia.max' => 'Giá sản phẩm không quá 10 triệu.',
            'mo_ta.max' => 'Mô tả không quá 1000 ký tự.',
        ]);

        // Kiểm tra slug mới có trùng không (nếu tên sản phẩm đổi)
        $newSlug = Str::slug($request->ten_san_pham);
        if ($newSlug !== $product->slug && SanPham::where('slug', $newSlug)->exists()) {
            toastr()->error('Tên sản phẩm đã trùng, vui lòng chọn tên khác');
            return redirect()->back()->withInput();
        }

        // Xử lý ảnh mới nếu có
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có (nếu muốn)
            if ($product->hinh_anh) {
                Storage::disk('public')->delete($product->hinh_anh);
            }

            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products', $image, $imageName);
            $product->hinh_anh = 'products/' . $imageName;
        }

        // Cập nhật các trường
        $product->ma_san_pham = $request->ma_san_pham;
        $product->ten_san_pham = $request->ten_san_pham;
        $product->ma_danh_muc = $request->ma_danh_muc;
        $product->slug = $newSlug;
        $product->gia = $request->gia;
        $product->trang_thai = $request->trang_thai;
        $product->mo_ta = $request->mo_ta;
        $product->hot = $request->hot;
        $product->is_new = $request->is_new;
        $product->san_pham_dong_goi = $request->san_pham_dong_goi;

        $product->save();

        toastr()->success('Cập nhật sản phẩm thành công.');
        return redirect()->route('admin.products.list');
    }
    //sort delete
    public function sortDelete($slug) {
        $product = SanPham::where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại!'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Xóa sản phẩm thành công!']);
    }
    //show list products sort delete
    public function listProductSortDelete(Request $request)
    {
        $search = $request->input('search');

        $deletedProductsQuery = SanPham::onlyTrashed()
            ->with('danhMuc')
            ->orderByDesc('deleted_at');

        if ($search) {
            $deletedProductsQuery->where(function($q) use ($search) {
                $q->where('ten_san_pham', 'like', "%{$search}%")
                ->orWhere('ma_san_pham', 'like', "%{$search}%");
            });
        }

        $deletedProducts = $deletedProductsQuery->paginate(10);

        $viewData = [
            'title' => 'Quản lý sản phẩm | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách sản phẩm đã xóa',
            'products' => $deletedProducts,
            'search' => $search, // Để giữ giá trị input search trên view nếu cần
        ];

        return view('admins.products.product_delete', $viewData);
    }
}
