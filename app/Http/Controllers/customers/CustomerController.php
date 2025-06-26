<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\SanPham;
use Illuminate\Support\Facades\DB;
use NguyenAry\VietnamAddressAPI\Address;

class CustomerController extends Controller
{
    public function getDiaChi(Request $request)
    {
        if ($request->has('province')) {
            $districts = collect(Address::getDistrictsByProvinceId($request->province))->map(function ($item, $key) {
                return [
                    'code' => $key,
                    'name' => $item['name']
                ];
            })->values();

            return response()->json(['quanHuyen' => $districts]);
        }

        if ($request->has('district')) {
            $wards = collect(Address::getWardsByDistrictId($request->district))->map(function ($item, $key) {
                return [
                    'code' => $key,
                    'name' => $item['name']
                ];
            })->values();

            return response()->json(['phuongXa' => $wards]);
        }

        $provinces = collect(Address::getProvinces())->map(function ($item, $key) {
            return [
                'code' => $key,
                'name' => $item['name']
            ];
        })->values();

        return response()->json(['tinhTP' => $provinces]);
    }
    public function index()
    {
        $user = Auth::user(); 
        $taiKhoan = TaiKhoan::with(['khachHang.diaChis']) // lấy cả địa chỉ
            ->where('ma_tai_khoan', $user->ma_tai_khoan)
            ->first();

        $viewData = [
            'title' => 'Xin chào ' . ($taiKhoan->khachHang->ho_ten_khach_hang ?? 'bạn') . ' | CMDT Coffee & Tea',
            'taiKhoan' => $taiKhoan
        ];

        return view('clients.customers.index', $viewData);
    }
    public function storeAddress(Request $request)
    {
        $taiKhoan = Auth::user();
        $khachHang = $taiKhoan->khachHang;

        $request->validate([
            'diaChi' => 'required|string|max:255',
            'phuongXa' => 'required|string|max:255',
            'quanHuyen' => 'required|string|max:255',
            'tinhThanh' => 'required|string|max:255',
            'macDinh' => 'nullable|boolean',
        ], [
            'diaChi.required' => 'Vui lòng nhập địa chỉ.',
            'phuongXa.required' => 'Vui lòng nhập phường/xã.',
            'quanHuyen.required' => 'Vui lòng nhập quận/huyện.',
            'tinhThanh.required' => 'Vui lòng nhập tỉnh/thành phố.',
        ]);

        if ($request->macDinh) {
            // Nếu chọn làm mặc định, reset tất cả địa chỉ hiện tại
            $khachHang->diaChis()->update(['mac_dinh' => 0]);
        }

        $khachHang->diaChis()->create([
            'dia_chi' => $request->diaChi,
            'phuong_xa' => $request->phuongXa,
            'quan_huyen' => $request->quanHuyen,
            'tinh_thanh' => $request->tinhThanh,
            'mac_dinh' => $request->macDinh ? 1 : 0,
        ]);
        toastr()->success('Đã thêm địa chỉ mới thành công.');
        return redirect()->back();
    }
    public function updateInfo(Request $request)
    {
        $taiKhoan = Auth::user();
        $khachHang = $taiKhoan->khachHang;
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'hoTen' => 'required|string|min:2|max:255',
            'soDienThoai' => [
                'required',
                'regex:/^0\d{9}$/',
                Rule::unique('khach_hangs', 'so_dien_thoai')->ignore($khachHang->id),
            ],
            'ngaySinh' => ['required', 'date', function ($attribute, $value, $fail) {
                $dob = Carbon::parse($value);
                if ($dob->isFuture()) {
                    return $fail('Ngày sinh không hợp lệ.');
                }
                if ($dob->diffInYears(now()) < 16) {
                    return $fail('Bạn phải đủ 16 tuổi trở lên.');
                }
                if ($dob->diffInYears(now()) > 100) {
                    return $fail('Tuổi không hợp lệ.');
                }
            }],
            'gioiTinh' => 'required|in:0,1',
        ], [
            'hoTen.required' => 'Vui lòng nhập họ và tên.',
            'hoTen.min' => 'Họ tên quá ngắn.',
            'soDienThoai.required' => 'Vui lòng nhập số điện thoại.',
            'soDienThoai.unique' => 'Số điện thoại này đã được sử dụng.',
            'soDienThoai.regex' => 'Số điện thoại không đúng định dạng (VD: 0912345678).',
            'ngaySinh.required' => 'Vui lòng chọn ngày sinh.',
            'ngaySinh.date' => 'Ngày sinh không hợp lệ.',
            'gioiTinh.required' => 'Vui lòng chọn giới tính.',
            'gioiTinh.in' => 'Giới tính không hợp lệ.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return redirect()->back()->withInput();
        }

        // Cập nhật thông tin
        $khachHang->ho_ten_khach_hang = $request->input('hoTen');
        $khachHang->so_dien_thoai = $request->input('soDienThoai');
        $khachHang->ngay_sinh = $request->input('ngaySinh');
        $khachHang->gioi_tinh = $request->input('gioiTinh');
        $khachHang->save();

        toastr()->success('Đã cập nhật thông tin thành công.');
        return redirect()->back();
    }

    public function sanPhamDaMua()
    {
        $user = Auth::user();
        $taiKhoan = TaiKhoan::with('khachHang')->where('ma_tai_khoan', $user->ma_tai_khoan)->first();
        $maKhachHang = $taiKhoan->khachHang->ma_khach_hang;

        $sanPhamDaMua = SanPham::whereIn('ma_san_pham', function ($query) use ($maKhachHang) {
            $query->select('ma_san_pham')
                ->from('hoa_dons')
                ->join('chi_tiet_hoa_dons', 'hoa_dons.ma_hoa_don', '=', 'chi_tiet_hoa_dons.ma_hoa_don')
                ->where('hoa_dons.ma_khach_hang', $maKhachHang)
                ->where('hoa_dons.trang_thai', '<', 5);
        })->get();

        $viewData = [
            'title' => 'Sản phẩm đã mua',
            'subtitle' => 'Sản phẩm đã mua',
            'taiKhoan' => $taiKhoan,
            'sanPhamDaMua' => $sanPhamDaMua
        ];

        return view('clients.customers.san_pham_da_mua', $viewData);
    }

    public function getProductToViewHistory()
    {
        $productToHistory = session()->get('viewed_products', []);

        $viewData = [
            'title' => 'Sản phẩm đã xem',
            'subtitle' => 'Sản phẩm đã xem',
            'productToHistory' => $productToHistory
        ];

        return view('clients.customers.product_history', $viewData);
    }

    public function getVoucherMember(){
        
    }
}
