<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\CuaHangNguyenLieu;
use App\Models\NguyenLieu;
use App\Models\CuaHang;
use App\Models\PhieuNhapXuatNguyenLieu;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
class AdminShopmaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang');
        if ($request->filled('ma_cua_hang')) {
            $query->where('ma_cua_hang', $request->ma_cua_hang);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nguyenLieu', function($q) use ($search) {
                $q->where('ma_nguyen_lieu', 'like', '%' . $search . '%')
                ->orWhere('ten_nguyen_lieu', 'like', '%' . $search . '%');
            });
        }
        $materials = $query->paginate(10);
        $stores = CuaHang::all();
        return view('admins.shopmaterial.index', [
            'materials' => $materials,
            'stores' => $stores,
            'title' => 'Quản lý cửa hàng nguyên liệu',
            'subtitle' => 'Danh sách nguyên liệu và quản lý'
        ]);
    }
    public function create()
    {
        return view('admins.shopmaterial.create', [
            'title' => 'Thêm nguyên liệu mới',
            'subtitle' => 'Điền thông tin nguyên liệu'
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'na me' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        NguyenLieu::create($validated);

        toastr()->success('Nguyên liệu đã được thêm.');

        return redirect()->route('admins.shopmaterial.index');
    }
    public function edit($id)
    {
        $material = NguyenLieu::findOrFail($id);

        return view('admins.shopmaterial.edit', [
            'material' => $material,
            'title' => 'Chỉnh sửa nguyên liệu',
            'subtitle' => 'Cập nhật thông tin nguyên liệu'
        ]);
    }
    public function update(Request $request, $id)
    {
        $material = NguyenLieu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $material->update($validated);

        toastr()->success('Nguyên liệu đã được cập nhật.');

        return redirect()->route('admins.shopmaterial.index');
    }
    public function destroy($id)
    {
        $material = NguyenLieu::findOrFail($id);
        $material->delete();

        toastr()->success('Nguyên liệu đã được xóa.');

        return redirect()->route('admins.shopmaterial.index');
    }
    public function showImportPage(Request $request)
    {
        $today = Carbon::now()->format('d/m/Y');
        $materialKeys = $request->input('materials', []);
        $materials = collect();

        foreach ($materialKeys as $key) {
            [$maCuaHang, $maNguyenLieu] = explode('|', $key) + [null, null];

            if (!$maCuaHang || !$maNguyenLieu) continue;

            $material = CuaHangNguyenLieu::with('nguyenLieu')
                ->where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->first();

            if ($material) {
                $materials->push($material);
            }
        }
        if ($materials->isEmpty()) {
            toastr()->error('Không còn nguyên liệu để thêm vào!');
            return redirect()->route('admins.shopmaterial.index');
        }

        // Đếm số lượng lô nhập hôm nay
        $countToday = CuaHangNguyenLieu::whereDate('updated_at', now())->count();
        $soThuTu = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
        $soLo = 'LO_' . now()->format('Ymd') . '_' . $soThuTu;

        return view('admins.shopmaterial.import', [
            'materials' => $materials,
            'subtitle' => 'Nhập nguyên liệu vào cửa hàng',
            'title' => 'Nhập nguyên liệu | CDMT & tea and coffee',
            'soLo' => $soLo,
            'today'=>$today,
        ]);
    }
    public function import(Request $request)
    {
        $importData = $request->input('import');

        if (!$importData || empty($importData)) {
            return redirect()->back()->withErrors(['import' => 'Không có dữ liệu nhập.'])->withInput();
        }

        try {
          DB::transaction(function () use ($importData, $request) {
                $updateData = [];

                foreach ($importData as $maCuaHang => $nguyenLieus) {
                    $materials = CuaHangNguyenLieu::with('nguyenLieu')
                        ->where('ma_cua_hang', $maCuaHang)
                        ->whereIn('ma_nguyen_lieu', array_keys($nguyenLieus))
                        ->get()
                        ->keyBy('ma_nguyen_lieu');

                    foreach ($nguyenLieus as $maNguyenLieu => $soLuongNhap) {
                        if (!is_numeric($soLuongNhap) || $soLuongNhap < 1) {
                            throw new \Exception("Số lượng nhập cho nguyên liệu $maNguyenLieu không hợp lệ.");
                        }

                        $material = $materials->get($maNguyenLieu);
                        if (!$material) {
                            throw new \Exception("Nguyên liệu $maNguyenLieu không tồn tại trong cửa hàng $maCuaHang.");
                        }

                        $dinhluong = $soLuongNhap * $material->nguyenLieu->so_luong;

                        $maxImport = $material->so_luong_ton_max - $material->so_luong_ton;
                        if ($dinhluong > $maxImport) {
                            throw new \Exception("Định lượng nhập vượt quá mức tối đa cho nguyên liệu $maNguyenLieu (tối đa có thể nhập: $maxImport).");
                        }

                        $updateKey = $maCuaHang . '_' . $maNguyenLieu;
                        if (!isset($updateData[$updateKey])) {
                            $updateData[$updateKey] = [
                                'ma_cua_hang'     => $maCuaHang,
                                'ma_nguyen_lieu'  => $maNguyenLieu,
                                'so_luong_ton'    => $material->so_luong_ton,
                            ];
                        }

                        $updateData[$updateKey]['so_luong_ton'] += $dinhluong;
                        $solo = PhieuNhapXuatNguyenLieu::generateSoLo();
                        PhieuNhapXuatNguyenLieu::create([
                            'ma_cua_hang'     => $maCuaHang,
                            'ma_nguyen_lieu'  => $maNguyenLieu,
                            'loai_phieu'      => 0, //Phiếu nhập
                            'so_lo'           => $solo,
                            'ngay_san_xuat'   => now(),
                            'han_su_dung'     => now()->addMonths(6),
                            'so_luong'        => $soLuongNhap,
                            'dinh_luong'      => $dinhluong,
                            'don_vi'          => $material->don_vi,
                            'gia_tien'        => $material->nguyenLieu->gia ?? 0,
                            'tong_tien'       => ($material->nguyenLieu->gia ?? 0) * $soLuongNhap,
                            'ngay_tao_phieu'  => now(),
                            'ghi_chu'         => null,
                        ]);
                    }
                }

                // Cập nhật tồn kho sau khi đã gom hết dữ liệu
                foreach ($updateData as $data) {
                    CuaHangNguyenLieu::where('ma_cua_hang', $data['ma_cua_hang'])
                        ->where('ma_nguyen_lieu', $data['ma_nguyen_lieu'])
                        ->update(['so_luong_ton' => $data['so_luong_ton']]);
                }
            });



            toastr()->success('Nhập nguyên liệu thành công!');
            return redirect()->route('admins.shopmaterial.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'import' => 'Có lỗi xảy ra khi nhập nguyên liệu: ' . $e->getMessage()
            ])->withInput();
        }
    }

}

