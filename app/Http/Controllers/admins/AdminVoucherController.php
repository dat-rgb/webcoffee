<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminVoucherController extends Controller
{
    public function listVouchers(){

        $vouchers = KhuyenMai::where('trang_thai', '!=',3)->paginate(10);

        $viewData = [
            'title' => 'Vouchers || CDMT Coffee & Tea',
            'subtitle' => 'Danh sách vouchers',
            'vouchers' => $vouchers
        ];
        return view('admins.vouchers.index', $viewData);
    }
    public function listVouchersOff(){

        $vouchers = KhuyenMai::where('trang_thai', 2)->paginate(10);

        $viewData = [
            'title' => 'Vouchers || CDMT Coffee & Tea',
            'subtitle' => 'Danh sách vouchers đóng',
            'vouchers' => $vouchers
        ];
        return view('admins.vouchers.index', $viewData);
    }
    public function listVouchersArchive(){

        $vouchers = KhuyenMai::where('trang_thai', 3)->paginate(10);

        $viewData = [
            'title' => 'Vouchers || CDMT Coffee & Tea',
            'subtitle' => 'Danh sách vouchers lưu trữ',
            'vouchers' => $vouchers
        ];
        return view('admins.vouchers.index', $viewData);
    }
    public function showVoucherForm(){
        $viewData = [
            'title' => 'Thêm Voucher || CDMT Coffee & Tea',
            'subtitle' => 'Thêm voucher'
        ];
        return view('admins.vouchers.voucher_form', $viewData);

    }
    public function addVoucher(Request $request){
        $request->validate([
            'ma_voucher' => 'required|string|max:50|min:2|unique:khuyen_mais,ma_voucher|regex:/^[a-zA-Z0-9_-]+$/',
            'ten_voucher' => 'required|string|max:255|min:2',
            'hinh_anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', 
            'gia_tri_giam' => 'required|numeric|min:0|max:10000000',
            'giam_gia_max' => 'numeric|min:0|max:10000000',
            'so_luong' => 'required|numeric|min:0|max:10000000',
            'dieu_kien_ap_dung' => 'required|numeric|min:0|max:100000000',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
        ], [
            'ma_voucher.required' => 'Mã voucher là bắt buộc.',
            'ma_voucher.min' =>  'Mã voucher ít nhất 2 ký tự.',
            'ma_voucher.max' =>  'Mã voucher không quá 50 ký tự.',
            'ma_voucher.unique' =>  'Mã voucher đã tồn tại. Vui lòng chọn mã voucher khác.',
            'ma_voucher.regex' => 'Mã voucher không được chứa khoảng trắng, dấu hoặc ký tự đặc biệt.',

            'ten_voucher.required' => 'Tên voucher là bắt buộc.',
            'ten_voucher.min' =>  'Tên voucher ít nhất 2 ký tự.',
            'ten_voucher.max' =>  'Tên voucher không quá 255 ký tự.',

            'hinh_anh.image' => 'Tệp phải là ảnh.',
            'hinh_anh.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'hinh_anh.max' => 'Ảnh không quá 2MB.',

            'gia_tri_giam.required' => 'Giá trị giảm là bắt buộc.',
            'gia_tri_giam.numeric' => 'Giá trị giảm phải là một số.',
            'gia_tri_giam.min'=>'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'gia_tri_giam.max' => 'Giá trị giảm không quá 10 triệu.',

            'giam_gia_max.numeric' => 'Giảm giá tối đa phải là một số.',
            'giam_gia_max.min'=>'Giảm giá tối đa phải lớn hơn hoặc bằng 0.',
            'giam_gia_max.max' => 'Giảm giá tối đa không quá 10 triệu.',

            'so_luong.required' => 'Số lượng là bắt buộc.',
            'so_luong.numeric' => 'Số lượng phải là một số.',
            'so_luong.min'=>'Số lượng phải lớn hơn hoặc bằng 0.',
            'so_luong.max' => 'Số lượng không quá 10 triệu.',

            'dieu_kien_ap_dung.required' => 'Điều kiện áp dụng là bắt buộc.',
            'dieu_kien_ap_dung.numeric' => 'Điều kiện áp dụng phải là một số.',
            'dieu_kien_ap_dung.min'=>'Điều kiện áp dụng phải lớn hơn hoặc bằng 0.',
            'dieu_kien_ap_dung.max' => 'Điều kiện áp dụng không quá 100 triệu.',

            'ngay_bat_dau.required' => 'Ngày bắt đầu là bắt buộc.',
            'ngay_bat_dau.date' => 'Ngày bắt đầu phải là định dạng ngày.',

            'ngay_ket_thuc.required' => 'Ngày kết thúc là bắt buộc.',
            'ngay_ket_thuc.date' => 'Ngày kết thúc phải là định dạng ngày.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        $imagePath = null;

        if($request->hasFile('hinh_anh')){
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('vouchers', $image, $imageName);

            $imagePath = 'vouchers/' . $imageName;
        }

        KhuyenMai::create([
            'ma_voucher' => $request->ma_voucher,
            'ten_voucher' => $request->ten_voucher,
            'hinh_anh' => $imagePath,
            'gia_tri_giam' => $request->gia_tri_giam,
            'giam_gia_max' =>  $request->giam_gia_max,
            'so_luong' =>  $request->so_luong,
            'dieu_kien_ap_dung' => $request->dieu_kien_ap_dung,
            'ngay_bat_dau' =>  $request->ngay_bat_dau,
            'ngay_ket_thuc' =>  $request->ngay_ket_thuc,
            'trang_thai' => $request->trang_thai
        ]);

        toastr()->success('Đã thêm voucher thành công.');
        return redirect()->route('admin.vouchers.list');
    }
    public function onOrOffVoucher($id){
        $voucher = KhuyenMai::where('ma_voucher',$id)->first();

        if(!$voucher){
            toastr()->error('Voucher không tồn tại.');
            return redirect()->back();
        }
        if($voucher->trang_thai == 3){
            toastr()->error('Không thể đóng voucher đã lưu trữ.');
            return redirect()->back();
        }
        if($voucher->trang_thai == 1){
            $voucher->update(['trang_thai'=>2]);
            toastr()->success('Voucher đã được đóng');
        }else{
            $voucher->update(['trang_thai'=>1]);
            toastr()->success('Voucher đã được mở');
        }

        return redirect()->back();
    }
    public function voucherArchive($id){
        $voucher = KhuyenMai::where('ma_voucher',$id)->first();

        if (!$voucher) {
            toastr()->error('Voucher không tồn tại');
            return redirect()->back();
        }

        if ($voucher->trang_thai == 2) {
            toastr()->error('Không thể lưu trữ Voucher đã đóng');
            return redirect()->back();
        }

        if ($voucher->trang_thai == 1) {
            $voucher->update(['trang_thai' => 3]);
            toastr()->success('Voucher đã được lưu trữ');
        } else {
            $voucher->update(['trang_thai' => 1]);
            toastr()->success('Voucher đã được mở');
        }

        return redirect()->back();
    }
    public function deleteVoucher($id)
    {
        $voucher = KhuyenMai::where('ma_voucher', $id)->first();

        if (!$voucher) {
            toastr()->error('Voucher không tồn tại');
            return redirect()->back();
        }

        if ($voucher->trang_thai != 3) {
            toastr()->error('Không thể xóa Voucher này. Hãy lưu trữ!');
            return redirect()->back();
        } else {
            $voucher->delete();
            toastr()->success('Xóa voucher thành công');
            return redirect()->back();
        }
    }
    public function editVoucherForm($id){
        $voucher = KhuyenMai::where('ma_voucher', $id)->first();

        if(!$voucher){
            toastr()->error('Voucher không tồn tại!');
            return redirect()->back();
        }
        $viewData = [
            'title' => 'Vouchers || CDMT Coffee & Tea',
            'subtitle' => 'Chỉnh sửa voucher ' . $voucher->ma_voucher,
            'voucher' => $voucher
        ];
        return view('admins.vouchers.voucher_edit', $viewData);
    }   

    public function editVoucher(Request $request, $id) {
        // Validate dữ liệu
        $request->validate([
            'ten_voucher' => 'required|string|max:255|min:2',
            'hinh_anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', 
            'gia_tri_giam' => 'required|numeric|min:0|max:10000000',
            'giam_gia_max' => 'numeric|min:0|max:10000000',
            'so_luong' => 'required|numeric|min:0|max:10000000',
            'dieu_kien_ap_dung' => 'required|numeric|min:0|max:100000000',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
        ], 
        [
            'ten_voucher.required' => 'Tên voucher là bắt buộc.',
            'ten_voucher.min' =>  'Tên voucher ít nhất 2 ký tự.',
            'ten_voucher.max' =>  'Tên voucher không quá 255 ký tự.',

            'hinh_anh.image' => 'Tệp phải là ảnh.',
            'hinh_anh.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'hinh_anh.max' => 'Ảnh không quá 2MB.',

            'gia_tri_giam.required' => 'Giá trị giảm là bắt buộc.',
            'gia_tri_giam.numeric' => 'Giá trị giảm phải là một số.',
            'gia_tri_giam.min'=>'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'gia_tri_giam.max' => 'Giá trị giảm không quá 10 triệu.',

            'giam_gia_max.numeric' => 'Giảm giá tối đa phải là một số.',
            'giam_gia_max.min'=>'Giảm giá tối đa phải lớn hơn hoặc bằng 0.',
            'giam_gia_max.max' => 'Giảm giá tối đa không quá 10 triệu.',

            'so_luong.required' => 'Số lượng là bắt buộc.',
            'so_luong.numeric' => 'Số lượng phải là một số.',
            'so_luong.min'=>'Số lượng phải lớn hơn hoặc bằng 0.',
            'so_luong.max' => 'Số lượng không quá 10 triệu.',

            'dieu_kien_ap_dung.required' => 'Điều kiện áp dụng là bắt buộc.',
            'dieu_kien_ap_dung.numeric' => 'Điều kiện áp dụng phải là một số.',
            'dieu_kien_ap_dung.min'=>'Điều kiện áp dụng phải lớn hơn hoặc bằng 0.',
            'dieu_kien_ap_dung.max' => 'Điều kiện áp dụng không quá 100 triệu.',

            'ngay_bat_dau.required' => 'Ngày bắt đầu là bắt buộc.',
            'ngay_bat_dau.date' => 'Ngày bắt đầu phải là định dạng ngày.',

            'ngay_ket_thuc.required' => 'Ngày kết thúc là bắt buộc.',
            'ngay_ket_thuc.date' => 'Ngày kết thúc phải là định dạng ngày.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        // Tìm voucher theo ma_voucher
        $voucher = KhuyenMai::where('ma_voucher',$id)->first();

        if (!$voucher) {
            return redirect()->route('admin.vouchers.list')->with('error', 'Voucher không tồn tại!');
        }

        // Cập nhật dữ liệu voucher
        $voucher->ten_voucher = $request->ten_voucher;
        $voucher->gia_tri_giam = $request->gia_tri_giam;
        $voucher->giam_gia_max = $request->giam_gia_max;
        $voucher->so_luong = $request->so_luong;
        $voucher->dieu_kien_ap_dung = $request->dieu_kien_ap_dung;
        $voucher->ngay_bat_dau = $request->ngay_bat_dau;
        $voucher->ngay_ket_thuc = $request->ngay_ket_thuc;

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có
            if ($voucher->hinh_anh && Storage::disk('public')->exists($voucher->hinh_anh)) {
                Storage::disk('public')->delete($voucher->hinh_anh);
            }

            // Lưu ảnh mới
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'vouchers/' . $imageName;

            Storage::disk('public')->putFileAs('vouchers', $image, $imageName);

            // Cập nhật đường dẫn trong DB
            $voucher->hinh_anh = $imagePath;
        }


        // Cập nhật trạng thái
        $voucher->trang_thai = $request->trang_thai;

        // Lưu lại vào cơ sở dữ liệu
        $voucher->save();

        // Trả về thông báo thành công và chuyển hướng
        toastr()->success('Cập nhật voucher thành công.');
        return redirect()->route('admin.vouchers.list');
    }
}
