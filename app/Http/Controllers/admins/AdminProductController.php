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

class AdminProductController extends Controller
{
    public function getCategory(){
        $categorys = DanhMucSanPham::where('trang_thai',1)->get();
        return $categorys;
    }
    public function getIngredient(){
        $ingredients = NguyenLieu::where('trang_thai',1)->get();
        return $ingredients;
    }
    public function getSizes(){
        $sizes = Sizes::all();
        return $sizes;
    }

    public function getProductsByStatus($status) {
        return SanPham::with('danhMuc')
            ->where('trang_thai', $status)
            ->paginate(10);
    }

    public function listProducts(Request $request) {
        $products = $this->getProductsByStatus(1);

        $viewData = [
            'title' => 'Quản lý sản phẩm || CDMT Coffee & Tea',
            'subtitle' => 'Danh sách sản phẩm',
            'products' => $products,
        ];

        return view('admins.products.index', $viewData);
    }
    public function listProductsHidden(){
        $products = $this->getProductsByStatus(2);

        $viewData = [
            'title' => 'Quản lý sản phẩm || CDMT Coffee & Tea',
            'subtitle' => 'Sản phẩm đã ẩn',
            'products' => $products,
        ];

        return view('admins.products.index', $viewData);
    }

    public function listProductsArchive(){
        $products = $this->getProductsByStatus(3);

        $viewData = [
            'title' => 'Quản lý sản phẩm || CDMT Coffee & Tea',
            'subtitle' => 'Sản phẩm đã lưu trữ',
            'products' => $products,
        ];

        return view('admins.products.index', $viewData);
    }

    public function showProductForm(){

        $categorys = $this->getCategory();
        $ingredients = $this->getIngredient();
        $sizes = $this->getSizes();

        // Lấy mã lớn nhất hiện có (giả sử dạng: NL001, NL002, ...)
        $lastItem = SanPham::orderByDesc('ma_san_pham')->first();

        if ($lastItem) {
            // Tách số phía sau
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
            'sizes' => $sizes,
            'newCode' => $newCode
        ];

        return view('admins.products.product_form', $viewData);
    }

    //Thêm sản phẩm mới
    public function productAdd(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|string|size:10|unique:san_phams,ma_san_pham',
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

        $new = $request->is_new === 'New' ? 1 : 0;
        $hot = $request->hot === 'Hot' ? 1 : 0;
        $slug = Str::slug($request->ten_san_pham);
        $phaChe = $request->san_pham_pha_che === 'DongGoi' ? 0: 1;

        // Check slug trùng
        if (SanPham::where('slug', $slug)->exists()) {
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
            'is_new' => $new,
            'san_pham_pha_che' => $phaChe,
        ]);

        toastr()->success('Thêm sản phẩm thành công.');
        return redirect()->route('admin.products.list');
    }

    //Lưu trữ
    public function productArchive($proId){
        $product = SanPham::where('ma_san_pham',$proId)->first();

        if (!$product) {
            toastr()->error('Sản phẩm không tồn tại');
            return redirect()->back();
        }

        if ($product->trang_thai == 2) {
            toastr()->error('Không thể lưu trữ sản phẩm đã ẩn');
            return redirect()->back();
        }

        if ($product->trang_thai == 1) {
            $product->update(['trang_thai' => 3]);
            toastr()->success('Sản phẩm đã được lưu trữ');
        } else {
            $product->update(['trang_thai' => 1]);
            toastr()->success('Sản phẩm đã được hiển thị');
        }

        return redirect()->back();
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
    public function productEdit($proId) {
        $product = SanPham::with('danhMuc', 'thanhPhanSanPham')->find($proId);

        if (!$product) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm!');
        }
        $viewData = [
            'title' => 'Thêm sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Thêm sản phẩm',
            'product' => $product
        ];
    }

}
