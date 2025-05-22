<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\ChucVu;
use App\Models\TaiKhoan;
use App\Models\CuaHang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminNhanvienController extends Controller
{
    public function index()
    {
        $nhanViens = NhanVien::with(['chucVu', 'taiKhoan', 'cuaHang'])
            ->where('trang_thai', 0)
            ->get();

        $ViewData = [
            'title' => 'Danh sách Nhân viên',
            'subtitle' => 'Quản lý danh sách nhân viên cửa hàng',
            'nhanViens' => $nhanViens,
        ];
        return view('admins.nhanvien.index', $ViewData);
    }
    public function create()
    {
        // Lấy mã nhân viên lớn nhất hiện tại (dựa vào thứ tự chuỗi)
        $lastMaNhanVien = NhanVien::orderByDesc('ma_nhan_vien')->value('ma_nhan_vien');

        if ($lastMaNhanVien) {
            // Lấy số phía sau mã, ví dụ từ 'NV00000025' -> 25
            $number = intval(substr($lastMaNhanVien, 2));
        } else {
            $number = 0;
        }

        // Tăng số và tạo mã mới, ví dụ: NV00000026
        $nextMaNhanVien = 'NV' . str_pad($number + 1, 8, '0', STR_PAD_LEFT);

        $ViewData = [
            'title' => 'Thêm nhân viên',
            'subtitle' => 'Thêm nhân viên',
            'chucVus' => ChucVu::all(),
            'taiKhoans' => TaiKhoan::all(),
            'cuaHangs' => CuaHang::all(),
            'nextMaNhanVien' => $nextMaNhanVien,
        ];

        return view('admins.nhanvien.create', $ViewData);
    }
    public function store(Request $request)
    {
        $request->validate([
            'ma_nhan_vien' => 'required|unique:nhan_viens,ma_nhan_vien',
            'ma_chuc_vu' => 'required|exists:chuc_vus,ma_chuc_vu',
            'ma_cua_hang' => 'required|exists:cua_hangs,ma_cua_hang',
            'ho_ten_nhan_vien' => 'required|string|max:255',
            'email' => 'required|email|unique:tai_khoans,email',
            'so_dien_thoai' => [
                'required',
                'regex:/^0\d{9}$/',
                'unique:nhan_viens,so_dien_thoai',
                //'max:10',
            ],
            'trang_thai'=>'0',
            'ngay_sinh' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthDate = Carbon::parse($value);
                    $age = $birthDate->diffInYears(Carbon::now());

                    if ($age < 18) {
                        $fail('Tuổi nhân viên phải từ 18 tuổi trở lên.');
                    } elseif ($age > 40) {
                        $fail('Tuổi nhân viên không được quá 40 tuổi.');
                    }
                },
            ],
        ], [
            'ma_nhan_vien.required' => 'Mã nhân viên không được để trống.',
            'ma_nhan_vien.unique' => 'Mã nhân viên đã tồn tại.',
            'ma_chuc_vu.required' => 'Vui lòng chọn chức vụ.',
            'ma_chuc_vu.exists' => 'Chức vụ không hợp lệ.',
            'ma_cua_hang.required' => 'Vui lòng chọn cửa hàng.',
            'ma_cua_hang.exists' => 'Cửa hàng không hợp lệ.',
            'ho_ten_nhan_vien.required' => 'Họ tên nhân viên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống.',
            'so_dien_thoai.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và gồm 10 chữ số.',
            'so_dien_thoai.unique' => 'Số điện thoại đã được sử dụng.',
            //'so_dien_thoai.max' =>'Số điện thoại chỉ được 10 số.'
        ]);
        // Tạo tài khoản mới
        $taiKhoan = TaiKhoan::create([
            'email' => $request->email,
            'password' => bcrypt('123456'), // hoặc random rồi gửi qua email nếu muốn
            'vai_tro' => 'nhanvien', // nếu có cột vai trò
            'trang_thai' => 1,
        ]);
        // Tạo nhân viên gắn với tài khoản
        NhanVien::create([
            'ma_nhan_vien' => $request->ma_nhan_vien,
            'ma_chuc_vu' => $request->ma_chuc_vu,
            'ma_tai_khoan' => $taiKhoan->ma_tai_khoan,
            'ma_cua_hang' => $request->ma_cua_hang,
            'ho_ten_nhan_vien' => $request->ho_ten_nhan_vien,
            'gioi_tinh' => $request->gioi_tinh ?? null,
            'ngay_sinh' => $request->ngay_sinh ?? null,
            'so_dien_thoai' => $request->so_dien_thoai ?? null,
            'dia_chi' => $request->dia_chi ?? null,
            //'ca_lam' => 3,
            'trang_thai' => 0,
        ]);
        toastr()->success('Thêm nhân viên và tạo tài khoản thành công!');
        return redirect()->route('admins.nhanvien.index');
    }
    public function edit($ma_nhan_vien)
    {
        $nhanVien = NhanVien::find($ma_nhan_vien);
        return view('admins.nhanvien.edit', [
            'nhanVien' => $nhanVien,
            'chucVus' => ChucVu::all(),
            'taiKhoans' => TaiKhoan::all(),
            'cuaHangs' => CuaHang::all(),
        ]);
    }
    public function update(Request $request, $ma_nhan_vien)
    {
        $request->validate([
            'ho_ten_nhan_vien' => 'required|string|max:255',
            'ma_chuc_vu' => 'required|exists:chuc_vus,ma_chuc_vu',
            'ma_cua_hang' => 'required|exists:cua_hangs,ma_cua_hang',
            'email' => 'required|email',
            'ngay_sinh' => [
                'required',
                'date',
                    function ($attribute, $value, $fail) {
                        $birthDate = Carbon::parse($value);
                        $age = $birthDate->diffInYears(Carbon::now());

                        if ($age < 18) {
                            $fail('Tuổi nhân viên phải từ 18 tuổi trở lên.');
                        } elseif ($age > 40) {
                            $fail('Tuổi nhân viên không được quá 40 tuổi.');
                        }
                    },
                ],
            'so_dien_thoai' => [
                'required',
                'regex:/^0\d{9}$/',
                'unique:nhan_viens,so_dien_thoai,' . $ma_nhan_vien . ',ma_nhan_vien',
                //'max:10',
            ],
        ], [
            'ho_ten_nhan_vien.required' => 'Họ tên nhân viên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'ma_chuc_vu.required' => 'Vui lòng chọn chức vụ.',
            'ma_cua_hang.required' => 'Vui lòng chọn cửa hàng.',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống.',
            'so_dien_thoai.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và gồm 10 chữ số.',
            'so_dien_thoai.unique' => 'Số điện thoại đã được sử dụng.',
            //'so_dien_thoai.max' =>'Số điện thoại chỉ được 10 số.'
        ]);

        $nhanVien = NhanVien::with('taiKhoan')->find($ma_nhan_vien);

        // Cập nhật nhân viên
        $nhanVien->update([
            'ho_ten_nhan_vien' => $request->ho_ten_nhan_vien,
            'ma_chuc_vu' => $request->ma_chuc_vu,
            'ma_cua_hang' => $request->ma_cua_hang,
            'so_dien_thoai' => $request->so_dien_thoai,
            'gioi_tinh' => $request->gioi_tinh,
            'ngay_sinh' => $request->ngay_sinh,
            'dia_chi' => $request->dia_chi,
            'ca_lam' => $request->ca_lam,
        ]);

        // Cập nhật tài khoản nếu có
        if ($nhanVien->taiKhoan) {
            $nhanVien->taiKhoan->update([
                'email' => $request->email,
            ]);
        }

        toastr()->success('Cập nhật nhân viên thành công!');
        return redirect()->route('admins.nhanvien.index');
    }
    public function destroy($id)
    {
        NhanVien::destroy($id);
        toastr()->success('Xóa nhân viên thành công!');
        return redirect()->route('admins.nhanvien.index');
    }
    public function archive($ma_nhan_vien)
    {
        $nhanVien = NhanVien::find($ma_nhan_vien); // Tìm theo khóa chính
        if (!$nhanVien) {
            toastr()->error('Không tìm thấy nhân viên.');
            return redirect()->route('admins.nhanvien.index');
        }

        $nhanVien->trang_thai = 1; // Ẩn nhân viên
        $nhanVien->save();
        toastr()->success('Nhân viên đã được lưu trữ thành công!');
        return redirect()->route('admins.nhanvien.index');
    }

    public function restore($ma_nhan_vien)
    {
        $nhanVien = NhanVien::find($ma_nhan_vien);
        if (!$nhanVien) {
            toastr()->error('Không tìm thấy nhân viên.');
            return redirect()->route('admins.nhanvien.index');
        }

        $nhanVien->trang_thai = 0;
        $nhanVien->save();
        toastr()->success('Khôi phục nhân viên thành công!');
        return redirect()->route('admins.nhanvien.index');
    }

}
