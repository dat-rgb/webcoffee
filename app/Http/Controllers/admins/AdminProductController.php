<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\DanhMucSanPham;
use App\Models\NguyenLieu;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function listProducts(){

        $products = SanPham::with('danhMuc')
                    ->where('trang_thai',1)
                    ->paginate(10);

        $viewData = [
            'title' => 'Sản Phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Quản lý sản phẩm',
            'products' => $products,
        ];

        return view('admins.products.index', $viewData);
    }

    public function showProductForm(){

        $categorys = $this->getCategory();
        $ingredients = $this->getIngredient();

        $viewData = [
            'title' => 'Thêm sản phẩm | CMDT Coffee & Tea',
            'subtitle' => 'Thêm sản phẩm',
            'categorys' => $categorys,
            'ingredients' =>$ingredients
        ];

        return view('admins.products.product_form', $viewData);
    }

    public function productAdd(Request $request){
        $request->validate(
            [
                'ma_san_pham' => 'required|string|size:10|unique:san_phams,ma_san_pham', 
                'ten_san_pham' => 'required|string|max:255|min:2',
                'ma_danh_muc' => 'required',
                'hinh_anh' => 'nullable|string|max:255', 
                'gia' => 'required|numeric|min:0|max:10000000',
                'mo_ta' => 'nullable|string|max:1000'
            ],
            [
                'ma_san_pham.required' => 'Mã sản phẩm là bắt buộc.',
                'ma_san_pham.size' => 'Mã sản phẩm phải có đúng 10 ký tự.',
                'ma_san_pham.unique' => 'Mã sản phẩm đã được sử dụng.',
                'ten_san_pham.required' => 'Tên sản phẩm là bắt buộc.',
                'ten_san_pham.min' => 'Tên sản phẩm phải ít nhất 2 ký tự.',
                'ten_san_pham.max' => 'Tên sản phẩm không quá 255 ký tự.',
                'ma_danh_muc.required' => 'Danh mục sản phẩm là bắt buộc.',
                'hinh_anh.max' => 'Tên file ảnh không quá 255 ký tự.',
                'gia.required' => 'Giá sản phẩm là bắt buộc.',
                'gia.numeric' => 'Giá sản phẩm phải là một số.',
                'gia.min'=>'Giá sản phẩm phải lớn hơn 0.',
                'gia.max' => 'Giá sản phẩm không quá 10 triệu.',
                'mo_ta.max' => 'Mô tả không quá 1000 ký tự.'
            ]
        );
        $new = 0;
        $hot = 0;
        $slug = null;
        if($request->hot == 'Hot'){
            $hot = 1;
        }
        if($request->is_new == 'New'){
            $new = 1;
        }
        $slug = Str::slug($request->ten_san_pham);
        $checkSlugExit = SanPham::where('slug',$slug)->first();
        if($checkSlugExit){
            toastr()->error('Tên sản phẩm đã trùng vui lòng chọn tên sản phẩm khác');
            return redirect()->back();
        }

        $product = SanPham::create([
            'ma_san_pham' => $request->ma_san_pham,
            'ten_san_pham' => $request->ten_san_pham,
            'ma_danh_muc' => $request->ma_danh_muc,
            'slug' =>$slug,
            'gia' => $request->gia,
            'trang_thai' => $request->trang_thai,
            'mo_ta' => $request->mo_ta,
            'hot' => $hot,
            'is_new' => $new,
        ]);

        toastr()->success('Thêm sản phẩm thành công.');
        return redirect()->route('admin.products.list');
    }
    //Lưu trữ
    public function productArchive($proId){
        $product = SanPham::where('ma_san_pham', $proId)->first();

        if(!$product){
            toastr()->error('Sản phẩm không tồn tại.');
            return redirect()->back();
        }
        if($product->trang_thai == 3){
            toastr()->error('Sản phẩm đã được lưu trữ.');
            return redirect()->back();
        }

        $product->update([
            'trang_thai' => 3
        ]);

        toastr()->success('Lưu trữ sản phẩm thành công.');
        return redirect()->back();
    }

}
