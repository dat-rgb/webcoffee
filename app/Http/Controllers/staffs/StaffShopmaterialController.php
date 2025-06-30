<?php

namespace App\Http\Controllers\staffs;

use App\Http\Controllers\Controller;
use App\Models\CuaHang;
use App\Models\CuaHangNguyenLieu;
use App\Models\NguyenLieu;
use App\Models\PhieuNhapXuatNguyenLieu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffShopmaterialController extends Controller
{
    public function index(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;

        if (!$nhanVien || $nhanVien->ma_chuc_vu !== 1) {
            toastr()->error('Bạn không có quyền truy cập.');
            return redirect()->back();
        }

        $query = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang')
            ->where('ma_cua_hang', $nhanVien->ma_cua_hang);

        if ($request->filled('search')) {
            $query->whereHas('nguyenLieu', function ($q) use ($request) {
                $q->where('ma_nguyen_lieu', 'like', "%{$request->search}%")
                  ->orWhere('ten_nguyen_lieu', 'like', "%{$request->search}%");
            });
        }

        $materials = $query->paginate(10);

        return view('staffs.shop_materials.index', [
            'materials' => $materials,
            'title' => 'Nguyên liệu cửa hàng ' . $nhanVien->ma_cua_hang,
            'subtitle' => 'Quản lý danh sách nguyên vật liệu của cửa hàng ' . $nhanVien->ma_cua_hang,
        ]);
    }
    public function create(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $nhanVien->ma_cua_hang;

        $nguyenLieuDaCo = CuaHangNguyenLieu::where('ma_cua_hang', $maCuaHang)
            ->pluck('ma_nguyen_lieu')->toArray();

        $materials = NguyenLieu::where('trang_thai', 1)
            ->whereNotIn('ma_nguyen_lieu', $nguyenLieuDaCo)
            ->get();

        return view('staffs.shop_materials.create', [
            'title' => 'Thêm nguyên liệu',
            'subtitle' => 'Thêm nguyên liệu vào kho',
            'materials' => $materials,
            'ma_cua_hang' => $maCuaHang,
            'ten_cua_hang' => CuaHang::where('ma_cua_hang', $maCuaHang)->value('ten_cua_hang'),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'ma_cua_hang' => 'required|exists:cua_hangs,ma_cua_hang',
            'ma_nguyen_lieu' => 'required|array|min:1',
            'ma_nguyen_lieu.*' => 'required|exists:nguyen_lieus,ma_nguyen_lieu',
            'so_luong_ton_min' => 'required|array',
            'don_vi' => 'required|array',
            'gia_nhap' => 'required|array',
        ]);

        foreach ($request->ma_nguyen_lieu as $maNL) {
            $daTonTai = CuaHangNguyenLieu::where([
                ['ma_cua_hang', $request->ma_cua_hang],
                ['ma_nguyen_lieu', $maNL]
            ])->exists();

            if (!$daTonTai) {
                $nguyenLieu = NguyenLieu::where('ma_nguyen_lieu', $maNL)->first();
                $dinhLuong = $nguyenLieu->so_luong ?? 1;
                $giaNhap = $request->gia_nhap[$maNL] ?? 0;

                CuaHangNguyenLieu::create([
                    'ma_cua_hang' => $request->ma_cua_hang,
                    'ma_nguyen_lieu' => $maNL,
                    'so_luong_ton' => 0,
                    'so_luong_ton_min' => (float)$request->so_luong_ton_min[$maNL] * (float)$dinhLuong,
                    'don_vi' => $request->don_vi[$maNL] ?? $nguyenLieu->don_vi,
                    'gia_nhap' => $giaNhap,
                ]);
            }
        }

        toastr()->success('Đã thêm nguyên liệu vào kho.');
        return redirect()->route('staffs.shop_materials.index');
    }
    public function showImportPage(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $nhanVien->ma_cua_hang;
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
            return redirect()->route('staffs.shop_materials.index');
        }

        // Đếm số lượng lô nhập hôm nay
        $countToday = CuaHangNguyenLieu::whereDate('updated_at', now())->count();
        $soThuTu = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
        $soLo = PhieuNhapXuatNguyenLieu::generateSoLo();

        return view('staffs.shop_materials.import', [
            'materials' => $materials,
            'subtitle' => 'Nhập nguyên liệu vào cửa hàng',
            'title' => 'Nhập nguyên liệu | CDMT & tea and coffee',
            'soLo' => $soLo,
            'today'=>$today,
        ]);
    }
    public function import(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $nhanVien->ma_cua_hang;

        $importData = $request->input('import');
        $noteData = $request->input('note');
        $datensxuatData = $request->input('nsx');
        $datehsData = $request->input('hsd');

        if (!$importData || empty($importData)) {
            return redirect()->back()->withErrors(['import' => 'Không có dữ liệu nhập.'])->withInput();
        }

        $now = now()->startOfDay();

        try {
            DB::transaction(function () use ($importData, $noteData, $datensxuatData, $datehsData, $maCuaHang, $now, $request, $nhanVien) {
                $updateData = [];

                $materials = CuaHangNguyenLieu::with('nguyenLieu')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->whereIn('ma_nguyen_lieu', array_keys($importData[$maCuaHang] ?? []))
                    ->get()
                    ->keyBy('ma_nguyen_lieu');

                foreach ($importData[$maCuaHang] ?? [] as $maNguyenLieu => $soLuongNhap) {
                    // Kiểm tra NSX và HSD
                    $nsx = $datensxuatData[$maCuaHang][$maNguyenLieu] ?? null;
                    $hsd = $datehsData[$maCuaHang][$maNguyenLieu] ?? null;

                    if (!$nsx) {
                        throw new \Exception("Ngày sản xuất (NSX) cho nguyên liệu $maNguyenLieu không được để trống.");
                    }
                    if (!$hsd) {
                        throw new \Exception("Hạn sử dụng (HSD) cho nguyên liệu $maNguyenLieu không được để trống.");
                    }

                    $nsxDate = Carbon::parse($nsx)->startOfDay();
                    $hsdDate = Carbon::parse($hsd)->startOfDay();

                    if ($nsxDate->gt($now)) {
                        throw new \Exception("Ngày sản xuất (NSX) cho nguyên liệu $maNguyenLieu không được sau ngày hiện tại.");
                    }

                    if ($hsdDate->lt($now)) {
                        throw new \Exception("Hạn sử dụng (HSD) cho nguyên liệu $maNguyenLieu không được trước ngày hiện tại.");
                    }

                    // Kiểm tra số lượng nhập
                    if (!is_numeric($soLuongNhap) || $soLuongNhap <= 0) {
                        throw new \Exception("Số lượng nhập cho nguyên liệu $maNguyenLieu không hợp lệ.");
                    }

                    $material = $materials->get($maNguyenLieu);
                    if (!$material) {
                        throw new \Exception("Không tìm thấy nguyên liệu $maNguyenLieu trong cửa hàng.");
                    }

                    // Cập nhật giá nhập nếu có giá mới
                    $giaNhapMoi = $request->input("gia_nhap.$maCuaHang.$maNguyenLieu");
                    if (is_numeric($giaNhapMoi) && $giaNhapMoi > 0) {
                        CuaHangNguyenLieu::where('ma_cua_hang', $maCuaHang)
                            ->where('ma_nguyen_lieu', $maNguyenLieu)
                            ->update(['gia_nhap' => $giaNhapMoi]);
                        $material->gia_nhap = $giaNhapMoi;
                    }

                    $dinhLuong = $soLuongNhap * $material->nguyenLieu->so_luong;

                    $updateKey = $maCuaHang . '_' . $maNguyenLieu;
                    if (!isset($updateData[$updateKey])) {
                        $updateData[$updateKey] = [
                            'ma_cua_hang'    => $maCuaHang,
                            'ma_nguyen_lieu' => $maNguyenLieu,
                            'so_luong_ton'   => $material->so_luong_ton,
                        ];
                    }

                    $updateData[$updateKey]['so_luong_ton'] += $dinhLuong;

                    PhieuNhapXuatNguyenLieu::create([
                        'ma_cua_hang'    => $maCuaHang,
                        'ma_nguyen_lieu' => $maNguyenLieu,
                        'ma_nhan_vien'   => $nhanVien->ma_nhan_vien,
                        'loai_phieu'     => 0,
                        'so_lo'          => $request->soLo,
                        'ngay_san_xuat'  => $nsxDate,
                        'han_su_dung'    => $hsdDate,
                        'so_luong'       => $soLuongNhap,
                        'dinh_luong'     => $dinhLuong,
                        'so_luong_ton_truoc' => $material->so_luong_ton,
                        'don_vi'         => $material->don_vi,
                        'gia_nhap'       => $material->gia_nhap ?? ($material->nguyenLieu->gia ?? 0),
                        'tong_tien'      => ($material->gia_nhap ?? ($material->nguyenLieu->gia ?? 0)) * $soLuongNhap,
                        'ngay_tao_phieu' => now(),
                        'ghi_chu'        => $noteData[$maCuaHang][$maNguyenLieu] ?? null,
                    ]);
                }

                foreach ($updateData as $data) {
                    CuaHangNguyenLieu::where('ma_cua_hang', $data['ma_cua_hang'])
                        ->where('ma_nguyen_lieu', $data['ma_nguyen_lieu'])
                        ->update(['so_luong_ton' => $data['so_luong_ton']]);
                }
            });

            toastr()->success('Nhập nguyên liệu thành công!');
            return redirect()->route('staffs.shop_materials.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'import' => 'Có lỗi xảy ra khi nhập nguyên liệu: ' . $e->getMessage()
            ])->withInput();
        }
    }
    public function showExportPage(Request $request)
{
    $nhanVien = Auth::guard('staff')->user()->nhanvien;
    $maCuaHang = $nhanVien->ma_cua_hang;
    $today = Carbon::now()->format('d/m/Y');
    $materialKeys = $request->input('materials', []);
    $materials = collect();

    $now = Carbon::now()->startOfDay();

    foreach ($materialKeys as $key) {
        [$_, $maNguyenLieu] = explode('|', $key) + [null, null];

        if (!$maNguyenLieu) continue;

        $material = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang')
            ->where('ma_cua_hang', $maCuaHang)
            ->where('ma_nguyen_lieu', $maNguyenLieu)
            ->first();

        if ($material) {
            $loList = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->where('loai_phieu', 0)
                ->where('han_su_dung', '>=', $now)
                ->orderBy('han_su_dung', 'asc')
                ->orderBy('ngay_tao_phieu', 'asc')
                ->get();

            //$fromHD = DB::table('chi_tiet_hoa_dons as cthd')
                //->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                //->join('thanh_phan_san_phams as tp', 'cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                //->where('hd.ma_cua_hang', $maCuaHang)
                //->where('tp.ma_nguyen_lieu', $maNguyenLieu)
                //->select('hd.ngay_lap_hoa_don as ngay_phat_sinh', DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong'))
                //->get();
            $fromHD = DB::table('chi_tiet_hoa_dons as cthd')
                ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                ->join('sizes as s', DB::raw('LOWER(cthd.ten_size)'), '=', DB::raw('LOWER(s.ten_size)'))
                ->join('thanh_phan_san_phams as tp', function ($join) {
                    $join->on('cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                        ->on('s.ma_size', '=', 'tp.ma_size');
                })
                ->where('hd.ma_cua_hang', $maCuaHang)
                ->where('tp.ma_nguyen_lieu', $maNguyenLieu)
                ->select(
                    'hd.ngay_lap_hoa_don as ngay_phat_sinh',
                    DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong')
                )
                ->get();







            $fromPX = DB::table('phieu_nhap_xuat_nguyen_lieus')
                ->where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->where('loai_phieu', 1)
                ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                ->get();

            $fromHuy = DB::table('phieu_nhap_xuat_nguyen_lieus')
                ->where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->where('loai_phieu', 2)
                ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                ->get();

            $transactions = $fromHD->merge($fromPX)->merge($fromHuy)->sortBy('ngay_phat_sinh')->values();

            $availableBatches = collect();

            foreach ($loList as $lo) {
                $left = $lo->dinh_luong;

                foreach ($transactions as $tx) {
                    if (Carbon::parse($tx->ngay_phat_sinh)->lt(Carbon::parse($lo->ngay_tao_phieu))) {
                        continue;
                    }

                    if ($tx->dinh_luong <= 0) continue;

                    $used = min($left, $tx->dinh_luong);
                    $left -= $used;
                    $tx->dinh_luong -= $used;

                    if ($left <= 0) break;
                }

                if ($left > 0) {
                    $availableBatches->push([
                        'so_lo'       => $lo->so_lo,
                        'con_lai'     => $left,
                        'han_su_dung' => $lo->han_su_dung,
                        'don_vi'      => $material->don_vi,
                    ]);
                }
            }

            $material->available_batches = $availableBatches;
            $materials->push($material);
        }
    }

    if ($materials->isEmpty()) {
        toastr()->error('Không còn nguyên liệu để xuất!');
        return redirect()->route('staffs.shop_materials.index');
    }

    $soLo = PhieuNhapXuatNguyenLieu::generateSoLo();

    return view('staffs.shop_materials.export', [
        'materials' => $materials,
        'subtitle' => 'Xuất nguyên liệu khỏi cửa hàng',
        'title' => 'Xuất nguyên liệu | CDMT & tea and coffee',
        'soLo' => $soLo,
        'today' => $today,
    ]);
}

    public function export(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maNhanVien = $nhanVien->ma_nhan_vien;
        $exportData = $request->input('export');
        $noteData = $request->input('note');

        if (!$exportData || empty($exportData)) {
            return redirect()->back()->withErrors(['export' => 'Không có dữ liệu xuất.'])->withInput();
        }

        $now = now()->startOfDay();

        try {
            DB::transaction(function () use ($exportData, $noteData, $now, $maNhanVien) {
                $updateData = [];

                foreach ($exportData as $maCuaHang => $nguyenLieus) {
                    foreach ($nguyenLieus as $maNguyenLieu => $soLuongXuat) {
                        if (!is_numeric($soLuongXuat) || $soLuongXuat <= 0) {
                            throw new \Exception("Số lượng xuất cho nguyên liệu $maNguyenLieu phải là số dương.");
                        }

                        $material = CuaHangNguyenLieu::with('nguyenLieu')->where('ma_cua_hang', $maCuaHang)
                            ->where('ma_nguyen_lieu', $maNguyenLieu)->first();

                        if (!$material || $material->so_luong_ton <= 0) {
                            throw new \Exception("Không tìm thấy nguyên liệu hoặc đã hết tồn kho.");
                        }

                        $dinhLuongCan = $soLuongXuat * $material->nguyenLieu->so_luong;

                        if ($dinhLuongCan > $material->so_luong_ton) {
                            throw new \Exception("Số lượng xuất vượt tồn kho hiện tại cho nguyên liệu $maNguyenLieu.");
                        }

                        $tonSauKhiXuat = $material->so_luong_ton - $dinhLuongCan;
                        $tonToiThieu = $material->nguyenLieu->so_luong_ton_toi_thieu ?? 0;

                        if ($tonSauKhiXuat < $tonToiThieu) {
                            throw new \Exception("Không thể xuất nguyên liệu $maNguyenLieu vì tồn sau khi xuất nhỏ hơn tồn tối thiểu.");
                        }

                        $dinhLuongConLai = $dinhLuongCan;
                        $tongXuatThucTe = 0;

                        $phieuNhapList = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                            ->where('ma_nguyen_lieu', $maNguyenLieu)
                            ->where('loai_phieu', 0)
                            ->where('han_su_dung', '>=', $now)
                            ->orderBy('han_su_dung', 'asc')
                            ->orderBy('ngay_tao_phieu', 'asc')
                            ->get();

                        foreach ($phieuNhapList as $lo) {
                            $dinhLuongDaXuat = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                                ->where('ma_nguyen_lieu', $maNguyenLieu)
                                ->where('loai_phieu', 1)
                                ->where('so_lo', $lo->so_lo)
                                ->sum('dinh_luong');

                            $tonLo = $lo->dinh_luong - $dinhLuongDaXuat;

                            if ($tonLo <= 0) continue;

                            $xuatTuLo = min($dinhLuongConLai, $tonLo);

                            PhieuNhapXuatNguyenLieu::create([
                                'ma_cua_hang'         => $maCuaHang,
                                'ma_nguyen_lieu'      => $maNguyenLieu,
                                'ma_nhan_vien'        => $maNhanVien,
                                'loai_phieu'          => 1,
                                'so_lo'               => $lo->so_lo,
                                'ngay_san_xuat'       => $lo->ngay_san_xuat,
                                'han_su_dung'         => $lo->han_su_dung,
                                'so_luong'            => $xuatTuLo / $material->nguyenLieu->so_luong,
                                'dinh_luong'          => $xuatTuLo,
                                'so_luong_ton_truoc'  => $material->so_luong_ton,
                                'don_vi'              => $material->don_vi,
                                'gia_tien'            => $material->nguyenLieu->gia ?? 0,
                                'tong_tien'           => ($material->nguyenLieu->gia ?? 0) * ($xuatTuLo / $material->nguyenLieu->so_luong),
                                'ngay_tao_phieu'      => now(),
                                'ghi_chu'             => ($noteData[$maCuaHang][$maNguyenLieu] ?? '') . ' | Xuất theo FIFO từ lô ' . $lo->so_lo,
                            ]);

                            $tongXuatThucTe += $xuatTuLo;
                            $dinhLuongConLai -= $xuatTuLo;

                            if ($dinhLuongConLai <= 0) break;
                        }

                        if ($dinhLuongConLai > 0) {
                            throw new \Exception("Không đủ nguyên liệu trong kho theo FIFO để xuất nguyên liệu $maNguyenLieu.");
                        }

                        $key = $maCuaHang . '_' . $maNguyenLieu;
                        if (!isset($updateData[$key])) {
                            $updateData[$key] = [
                                'ma_cua_hang' => $maCuaHang,
                                'ma_nguyen_lieu' => $maNguyenLieu,
                                'so_luong_ton' => $material->so_luong_ton,
                            ];
                        }

                        $updateData[$key]['so_luong_ton'] -= $tongXuatThucTe;
                    }
                }

                foreach ($updateData as $data) {
                    CuaHangNguyenLieu::where('ma_cua_hang', $data['ma_cua_hang'])
                        ->where('ma_nguyen_lieu', $data['ma_nguyen_lieu'])
                        ->update(['so_luong_ton' => $data['so_luong_ton']]);
                }
            });

            toastr()->success('Xuất nguyên liệu thành công!');
            return redirect()->route('staffs.shop_materials.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'export' => 'Có lỗi khi xuất nguyên liệu: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function showDestroyPage(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $nhanVien->ma_cua_hang;
        $materialKeys = $request->input('materials', []);
        $materials = collect();
        $now = Carbon::now()->startOfDay();

        foreach ($materialKeys as $key) {
            [$maCuaHang, $maNguyenLieu] = explode('|', $key) + [null, null];
            if (!$maCuaHang || !$maNguyenLieu) continue;

            $material = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang')
                ->where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->first();

            if ($material) {
                $loList = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 0)
                    ->where('han_su_dung', '>=', $now)
                    ->orderBy('han_su_dung', 'asc')
                    ->orderBy('ngay_tao_phieu', 'asc')
                    ->get();

                $fromHD = DB::table('chi_tiet_hoa_dons as cthd')
                    ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                    ->join('sizes as s', DB::raw('LOWER(cthd.ten_size)'), '=', DB::raw('LOWER(s.ten_size)'))
                    ->join('thanh_phan_san_phams as tp', function ($join) {
                        $join->on('cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                             ->on('s.ma_size', '=', 'tp.ma_size');
                    })
                    ->where('hd.ma_cua_hang', $maCuaHang)
                    ->where('tp.ma_nguyen_lieu', $maNguyenLieu)
                    ->select('hd.ngay_lap_hoa_don as ngay_phat_sinh', DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong'))
                    ->get();

                $fromPX = DB::table('phieu_nhap_xuat_nguyen_lieus')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 1)
                    ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                    ->get();

                $fromHuy = DB::table('phieu_nhap_xuat_nguyen_lieus')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 2)
                    ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                    ->get();

                $transactions = $fromHD->merge($fromPX)->merge($fromHuy)->sortBy('ngay_phat_sinh')->values();

                $availableBatches = collect();

                foreach ($loList as $lo) {
                    $left = $lo->dinh_luong;

                    foreach ($transactions as $tx) {
                        if (Carbon::parse($tx->ngay_phat_sinh)->lt(Carbon::parse($lo->ngay_tao_phieu))) {
                            continue;
                        }

                        if ($tx->dinh_luong <= 0) continue;

                        $used = min($left, $tx->dinh_luong);
                        $left -= $used;
                        $tx->dinh_luong -= $used;

                        if ($left <= 0) break;
                    }

                    if ($left > 0) {
                        $availableBatches->push([
                            'so_lo'       => $lo->so_lo,
                            'con_lai'     => $left,
                            'han_su_dung' => $lo->han_su_dung,
                            'don_vi'      => $material->don_vi,
                        ]);
                    }
                }

                $material->available_batches = $availableBatches;
                $materials->push($material);
            }
        }

        if ($materials->isEmpty()) {
            toastr()->error('Không còn nguyên liệu để hủy!');
            return redirect()->route('staffs.shop_materials.index');
        }

        return view('staffs.shop_materials.destroy', [
            'materials' => $materials,
            'title' => 'Hủy nguyên liệu | CDMT & tea and coffee',
            'subtitle' => 'Hủy nguyên liệu theo lô',
            'today' => Carbon::now()->format('d/m/Y'),
            'ma_cua_hang' => $maCuaHang,
        ]);
    }
    public function destroy(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $data = $request->input();
        $now = now();

        try {
            if (!isset($data['batch']) || !is_array($data['batch'])) {
                throw new \Exception("Dữ liệu không hợp lệ: thiếu thông tin lô nguyên liệu.");
            }

            DB::transaction(function () use ($data, $nhanVien, $now) {
                $updateStock = [];

                foreach ($data['batch'] as $ma_cua_hang => $materials) {
                    if (!is_scalar($ma_cua_hang)) {
                        throw new \Exception("Mã cửa hàng không hợp lệ.");
                    }
                    if (!is_array($materials)) continue;

                    foreach ($materials as $ma_nguyen_lieu => $soLo) {
                        if (!is_scalar($ma_nguyen_lieu)) {
                            throw new \Exception("Mã nguyên liệu không hợp lệ.");
                        }
                        if (!is_scalar($soLo)) {
                            throw new \Exception("Số lô không hợp lệ.");
                        }

                        $ma_cua_hang_key = (string) $ma_cua_hang;
                        $ma_nguyen_lieu_key = (string) $ma_nguyen_lieu;

                        $quantity = isset($data['quantity'][$ma_cua_hang_key][$ma_nguyen_lieu_key])
                            ? (float) $data['quantity'][$ma_cua_hang_key][$ma_nguyen_lieu_key]
                            : 0;

                        if ($quantity <= 0) {
                            throw new \Exception("Số lượng hủy cho nguyên liệu $ma_nguyen_lieu phải là số dương.");
                        }

                        $note = $data['note'][$ma_cua_hang_key][$ma_nguyen_lieu_key] ?? null;

                        $batchRecord = PhieuNhapXuatNguyenLieu::where('so_lo', $soLo)
                            ->where('ma_cua_hang', $ma_cua_hang)
                            ->where('ma_nguyen_lieu', $ma_nguyen_lieu)
                            ->where('loai_phieu', 0)
                            ->first();

                        if (!$batchRecord) {
                            throw new \Exception("Không tìm thấy lô $soLo cho cửa hàng $ma_cua_hang / nguyên liệu $ma_nguyen_lieu.");
                        }

                        $daXuat = PhieuNhapXuatNguyenLieu::where('so_lo', $soLo)
                            ->where('ma_cua_hang', $ma_cua_hang)
                            ->where('ma_nguyen_lieu', $ma_nguyen_lieu)
                            ->where('loai_phieu', 1)
                            ->sum('dinh_luong');

                        $soLuongNhapLot = $batchRecord->dinh_luong;
                        $conLaiTrongLo = $soLuongNhapLot - $daXuat;

                        if ($conLaiTrongLo < $quantity) {
                            throw new \Exception("Lô $soLo chỉ còn $conLaiTrongLo, không đủ để hủy $quantity.");
                        }

                        $materialStock = CuaHangNguyenLieu::where('ma_cua_hang', $ma_cua_hang)
                            ->where('ma_nguyen_lieu', $ma_nguyen_lieu)
                            ->with('nguyenLieu') // để đảm bảo có định_lượng
                            ->lockForUpdate()
                            ->first();

                        if (!$materialStock) {
                            throw new \Exception("Không tìm thấy nguyên liệu $ma_nguyen_lieu tại cửa hàng $ma_cua_hang.");
                        }

                        // Tính định lượng thực tế cần hủy
                        $dinhLuong1DonVi = $materialStock->nguyenLieu->so_luong  ?? 1;
                        $dinhLuongHuy = $quantity * $dinhLuong1DonVi;

                        PhieuNhapXuatNguyenLieu::create([
                            'ma_cua_hang'        => $ma_cua_hang,
                            'ma_nguyen_lieu'     => $ma_nguyen_lieu,
                            'ma_nhan_vien'       => $nhanVien->ma_nhan_vien,
                            'loai_phieu'         => 2, // phiếu hủy
                            'so_lo'              => $soLo,
                            'ngay_san_xuat'      => $batchRecord->ngay_san_xuat,
                            'han_su_dung'        => $batchRecord->han_su_dung,
                            'so_luong'           => $quantity,
                            'dinh_luong'         => $dinhLuongHuy,
                            'so_luong_ton_truoc' => $materialStock->so_luong_ton,
                            'don_vi'             => $materialStock->don_vi,
                            'gia_tien'           => optional($materialStock->nguyenLieu)->gia ?? 0,
                            'tong_tien'          => (optional($materialStock->nguyenLieu)->gia ?? 0) * $quantity,
                            'ngay_tao_phieu'     => $now,
                            'ghi_chu'            => $note ?? 'Hủy từ lô ' . $soLo,
                        ]);

                        $updateStock[$ma_cua_hang_key][$ma_nguyen_lieu_key] =
                            ($updateStock[$ma_cua_hang_key][$ma_nguyen_lieu_key] ?? 0) + $quantity;
                    }
                }

                // Cập nhật tồn kho bằng query builder
                foreach ($updateStock as $ma_cua_hang => $materials) {
                    foreach ($materials as $ma_nguyen_lieu => $soLuongHuy) {

                        // Khóa bản ghi để tránh race condition
                        $cuahangNgl = CuaHangNguyenLieu::where('ma_cua_hang', $ma_cua_hang)
                            ->where('ma_nguyen_lieu', $ma_nguyen_lieu)
                            ->lockForUpdate()
                            ->first();

                        if (!$cuahangNgl) {
                            throw new \Exception("Không tìm thấy kho cửa hàng hoặc nguyên liệu cần cập nhật.");
                        }

                        $so_luong_moi = $cuahangNgl->so_luong_ton - ($soLuongHuy * $cuahangNgl->nguyenLieu->so_luong);

                        if ($so_luong_moi < 0) {
                            throw new \Exception("Số lượng tồn kho âm sau khi hủy nguyên liệu $ma_nguyen_lieu tại cửa hàng $ma_cua_hang.");
                        }

                        // Cập nhật trực tiếp trong DB, không gọi save() trên model
                        CuaHangNguyenLieu::where('ma_cua_hang', $ma_cua_hang)
                            ->where('ma_nguyen_lieu', $ma_nguyen_lieu)
                            ->update(['so_luong_ton' => $so_luong_moi]);
                    }
                }

            });

            toastr()->success('Hủy nguyên liệu thành công!');
            return redirect()->route('staffs.shop_materials.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Lỗi khi hủy nguyên liệu: ' . $e->getMessage());
        }
    }
    public function showAllPhieu(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $maCuaHang = $nhanVien->ma_cua_hang;

        $query = PhieuNhapXuatNguyenLieu::query();
        $search = null;

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                if (strtoupper($search) === 'ADMIN') {
                    $q->whereNull('ma_nhan_vien');
                } else {
                    $q->where('ma_nhan_vien', 'like', "%$search%");
                }
            });
        }

        // Chỉ lọc cửa hàng của nhân viên đang đăng nhập
        $query->where('ma_cua_hang', $maCuaHang);

        if ($request->filled('loai_phieu')) {
            $query->where('loai_phieu', $request->loai_phieu);
        }

        // 👉 Gộp các phiếu có cùng so_lo, loai_phieu, ngay_tao_phieu
        $danhSachLo = $query
            ->select('loai_phieu', 'ma_cua_hang', 'ma_nhan_vien', 'ngay_tao_phieu')
            ->groupBy('loai_phieu', 'ma_cua_hang', 'ma_nhan_vien', 'ngay_tao_phieu')
            ->orderByDesc('ngay_tao_phieu')
            ->orderBy('loai_phieu')
            ->orderBy('ma_cua_hang')
            ->orderBy('ma_nhan_vien')
            ->get();
        //tổng tiền
        $danhSachLo = $danhSachLo->map(function ($phieu) {
            $chiTiet = PhieuNhapXuatNguyenLieu::where('loai_phieu', $phieu->loai_phieu)
                ->where('ma_cua_hang', $phieu->ma_cua_hang)
                ->where('ngay_tao_phieu', $phieu->ngay_tao_phieu)
                ->where(function ($q) use ($phieu) {
                    if ($phieu->ma_nhan_vien === null) {
                        $q->whereNull('ma_nhan_vien');
                    } else {
                        $q->where('ma_nhan_vien', $phieu->ma_nhan_vien);
                    }
                })
                ->get();
            $phieu->tong_tien = $chiTiet->sum(function ($item) {
                return ($item->tong_tien ?? 0);
            });

            return $phieu;
        });


        return view('staffs.shop_materials.list', [
            'title' => 'Danh sách phiếu',
            'subtitle' => 'Danh sách Phiếu Nhập - Xuất - Hủy',
            'danhSachPhieu' => $danhSachLo,
            'search'=>$search,
        ]);
    }
    public function layChiTietPhieu($ngay_tao, $loai_phieu, $ma_nv)
    {
        $chiTiet = PhieuNhapXuatNguyenLieu::with('nguyenLieu')
            ->whereRaw('DATE_FORMAT(ngay_tao_phieu, "%Y-%m-%d %H:%i:%s") = ?', [$ngay_tao])
            ->where('loai_phieu', $loai_phieu)
            ->where(function($q) use ($ma_nv) {
                if ($ma_nv === 'ADMIN') {
                    $q->whereNull('ma_nhan_vien');
                } else {
                    $q->where('ma_nhan_vien', $ma_nv);
                }
            })
            ->orderBy('ngay_tao_phieu')
            ->get();

        if ($chiTiet->isEmpty()) {
            return response()->json(['error' => 'Không tìm thấy phiếu'], 404);
        }

        $first = $chiTiet->first();

        $data = [
            'meta' => [
            'loai_phieu' => $first->loai_phieu,
            'ngay_tao_phieu' => $first->ngay_tao_phieu,
            'ma_nhan_vien' => $first->ma_nhan_vien,
            'so_lo' => $first->so_lo ?? 'Không có',
        ],


            'chi_tiet' => $chiTiet->map(function ($item) {
                return [
                    'ma_nguyen_lieu' => $item->nguyenLieu->ma_nguyen_lieu ?? 'N/A',
                    'ten_nguyen_lieu' => $item->nguyenLieu->ten_nguyen_lieu ?? 'N/A',
                    'so_luong' => $item->so_luong ?? 0,
                    'so_lo' => $item->so_lo ?? 'N/A',
                    'gia_tien' => $item->gia_nhap ?? 0,  // giá từ bảng nguyên liệu nếu có
                    'tong_tien' => $item->tong_tien ?? 0,
                    'ghi_chu' => $item->ghi_chu ?? '',
                ];
            }),
        ];

        return response()->json($data);
    }




}
