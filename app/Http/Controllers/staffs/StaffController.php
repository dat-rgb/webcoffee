<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NhanVien;
use App\Models\ChucVu;
use App\Models\TaiKhoan;
use App\Models\CuaHang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        if (!$nhanVien) {
            toastr()->error('Không tìm thấy thông tin nhân viên.');
            return redirect()->back();
        }

        if ($nhanVien->ma_chuc_vu !== 1) {
            toastr()->error('Bạn không có quyền truy cập.');
            return redirect()->back();
        }
        $query = NhanVien::with(['chucVu', 'taiKhoan', 'cuaHang'])
            ->where('trang_thai', 0)
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang);

        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('ho_ten_nhan_vien', 'LIKE', "%$search%")
                ->orWhere('dia_chi', 'LIKE', "%$search%")
                ->orWhere('ma_nhan_vien','LIKE',"%$search%")
                ->orWhere('so_dien_thoai', 'LIKE', "%$search%")
                ->orWhereHas('chucVu', function($q2) use ($search) {
                    $q2->where('ten_chuc_vu', 'LIKE', "%$search%");
                })
                ->orWhereHas('cuaHang', function($q3) use ($search) {
                    $q3->where('ten_cua_hang', 'LIKE', "%$search%");
                });
            });
        }

        $nhanViens = $query->get();

        return view('staffs.nhanviens.index', [
            'title' => 'Danh sách Nhân viên',
            'subtitle' => 'Quản lý danh sách nhân viên cửa hàng',
            'nhanViens' => $nhanViens,
            'search' => $search ?? '',
        ]);
    }
    public function edit($ma_nhan_vien)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        $nhanVien = NhanVien::where('ma_nhan_vien', $ma_nhan_vien)
        ->where('ma_cua_hang', $nhanVien->ma_cua_hang)
        ->first();

        if (!$nhanVien) {
            toastr()->error('Bạn không được quyền truy cập hay chỉnh sửa nhân viên này.');
            return redirect()->back();
        }

        return view('staffs.nhanviens.edit', [
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
        return redirect()->route('staffs.nhanviens.index');
    }
    public function archive($ma_nhan_vien)
    {
        $nhanVien = NhanVien::find($ma_nhan_vien); // Tìm theo khóa chính
        if (!$nhanVien) {
            toastr()->error('Không tìm thấy nhân viên.');
            return redirect()->route('staffs.nhanviens.index');
        }

        $nhanVien->trang_thai = 1; // Ẩn nhân viên
        $nhanVien->save();
        toastr()->success('Nhân viên đã được lưu trữ thành công!');
        return redirect()->route('staffs.nhanviens.index');
    }
    public function restore($ma_nhan_vien)
    {
        $nhanVien = NhanVien::find($ma_nhan_vien);
        if (!$nhanVien) {
            toastr()->error('Không tìm thấy nhân viên.');
            return redirect()->route('staffs.nhanviens.index');
        }

        $nhanVien->trang_thai = 0;
        $nhanVien->save();
        toastr()->success('Khôi phục nhân viên thành công!');
        return redirect()->route('staffs.nhanviens.index');
    }
    public function bulkRestore(Request $request)
    {
        $maNhanViens = $request->input('selected_nhanviens');

        if (!$maNhanViens || count($maNhanViens) == 0) {
            toastr()->error('Vui lòng chọn ít nhất một nhân viên để khôi phục.');
            return redirect()->back();
        }

        $restoredCount = 0;

        foreach ($maNhanViens as $maNhanVien) {
            $nhanVien = NhanVien::where('ma_nhan_vien', $maNhanVien)->first();
            if ($nhanVien && $nhanVien->trang_thai != 0) {
                $nhanVien->trang_thai = 0;
                $nhanVien->save();
                $restoredCount++;
            }
        }

        toastr()->success("Đã khôi phục {$restoredCount} nhân viên.");
        return redirect()->route('staffs.nhanviens.archived');
    }
    public function archiveBulk(Request $request)
    {
        $maNhanViens = $request->input('selected_nhanviens');
        if (!$maNhanViens || count($maNhanViens) === 0) {
            //return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một nhân viên.');
            toastr()->error("Vui lòng chọn ít nhất một nhân viên.");
            return redirect()->route('staffs.nhanviens.index');
        }
        // Cập nhật trạng thái = 1 (tạm nghỉ)
        NhanVien::whereIn('ma_nhan_vien', $maNhanViens)->update(['trang_thai' => 1]);
        toastr()->success("Nhân viên được chọn đã đưa vào danh sách nghỉ");
        return redirect()->route('staffs.nhanviens.index');
        //return redirect()->back()->with('success', 'Đã chuyển trạng thái nhân viên sang Tạm nghỉ.');
    }
    public function archived(Request $request)
    {
        $search = $request->input('search');
        $nhanViens = $this->getNhanVienByStatus(1,$search);

            return view('staffs.nhanviens.archive', [
            'title' => 'Danh sách nhân viên ẩn',
            'subtitle' => 'Danh sách nhân viên nghỉ ',
            'nhanViens' => $nhanViens,
        ]);
    }
    // Hàm chung để lấy nhân viên theo trạng thái + search
    public static function getNhanVienByStatus($status, $search = null) {
        $query = NhanVien::with(['chucVu', 'cuaHang']) // nhớ có quan hệ trong model
            ->where('trang_thai', $status)
            ->orderBy('ma_nhan_vien', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ma_nhan_vien', 'like', "%{$search}%")
                    ->orWhere('ho_ten_nhan_vien', 'like', "%{$search}%")
                    ->orWhere('dia_chi', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%")
                    ->orWhereHas('chucVu', function($q2) use ($search) {
                        $q2->where('ten_chuc_vu', 'like', "%{$search}%");
                    })
                    ->orWhereHas('cuaHang', function($q3) use ($search) {
                        $q3->where('ten_cua_hang', 'like', "%{$search}%");
                    });
            });
        }

        return $query->paginate(10);
    }
    // Lấy danh sách tất cả chức vụ
    public function getChucVu() {
        return ChucVu::all();
    }

    // Lấy danh sách tất cả cửa hàng
    public function getCuaHang() {
        return CuaHang::all();
    }


    // Hiển thị danh sách nhân viên hoạt động
    public function listNhanVien(Request $request) {
        $search = $request->input('search');
        $nhanviens = NhanVien::getNhanVienByStatus(1, $search);
        $chucvus = $this->getChucVu();
        $cuahangs = $this->getCuaHang();

        $viewData = [
            'title' => 'Quản lý nhân viên | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách nhân viên đang hoạt động',
            'nhanviens' => $nhanviens,
            'chucvus' => $chucvus,
            'cuahangs' => $cuahangs,
            'search' => $search
        ];

        return view('staffs.nhanviens.index', $viewData);
    }

    // Hiển thị danh sách nhân viên tạm nghỉ
    public function listNhanVienArchive(Request $request) {
        $search = $request->input('search');
        $nhanviens = NhanVien::getNhanVienByStatus(2, $search); // 2: tạm nghỉ
        $chucvus = $this->getChucVu();
        $cuahangs = $this->getCuaHang();

        $viewData = [
            'title' => 'Quản lý nhân viên | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách nhân viên tạm nghỉ',
            'nhanviens' => $nhanviens,
            'chucvus' => $chucvus,
            'cuahangs' => $cuahangs,
            'search' => $search
        ];

        return view('staffs.nhanviens.archive', $viewData);
    }
    public function profile()
    {
        $title = 'Thông tin cá nhân';
        $subtitle = 'Trang hồ sơ nhân viên';
        return view('staffs.pages.index', compact('title', 'subtitle'));
    }
    public function updateProfile(Request $request)
    {
        $today = Carbon::today();
        $minDate = $today->copy()->subYears(50); // lớn nhất 50 tuổi
        $maxDate = $today->copy()->subYears(18); // nhỏ nhất 18 tuổi

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|regex:/^0\d{9}$/',
            'gioi_tinh' => 'required|in:0,1',
            'ngay_sinh' => "required|date|after_or_equal:$minDate|before_or_equal:$maxDate",
            'dia_chi' => 'nullable|string|max:255',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ và tên.',
            'ho_ten.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và gồm 10 chữ số.',

            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',

            'ngay_sinh.required' => 'Vui lòng nhập ngày sinh.',
            'ngay_sinh.date' => 'Ngày sinh không hợp lệ.',
            'ngay_sinh.after_or_equal' => 'Nhân viên phải ít hơn 50 tuổi.',
            'ngay_sinh.before_or_equal' => 'Nhân viên phải đủ 18 tuổi.',

            'dia_chi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        $nhanVien->update([
            'ho_ten_nhan_vien' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'gioi_tinh' => $request->gioi_tinh,
            'ngay_sinh' => $request->ngay_sinh,
            'dia_chi' => $request->dia_chi,
        ]);

        toastr()->success('Cập nhật thông tin cá nhân thành công!');
        return redirect()->route('staff.profile');
    }


}



