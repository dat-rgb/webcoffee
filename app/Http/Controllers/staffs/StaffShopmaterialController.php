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

        if (!$nhanVien) {
            toastr()->error('Không tìm thấy thông tin nhân viên.');
            return redirect()->back();
        }

        if ($nhanVien->ma_chuc_vu !== 1) {
            toastr()->error('Bạn không có quyền truy cập.');
            return redirect()->back();
        }

        $query = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang');
        // Luôn lọc theo mã cửa hàng của nhân viên đang đăng nhập
        $query->where('ma_cua_hang', $nhanVien->ma_cua_hang);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nguyenLieu', function($q) use ($search) {
                $q->where('ma_nguyen_lieu', 'like', '%' . $search . '%')
                ->orWhere('ten_nguyen_lieu', 'like', '%' . $search . '%');
            });
        }
        $materials = $query->paginate(10);
        return view('staffs.shop_materials.index', [
            'materials' => $materials,
            'title' => 'Nguyên liệu cửa hàng '.$nhanVien->ma_cua_hang,
            'subtitle' => 'Quản lý danh sách nguyên vật liệu của cửa hàng '.$nhanVien->ma_cua_hang
        ]);
    }
    public function create(Request $request)
    {
        $nhanVien = Auth::guard('staff')->user()->nhanvien;
        $title = 'Thêm nguyên liệu';
        $subtitle = 'Thêm nguyên liệu vào kho';

        $maCuaHang = $nhanVien->ma_cua_hang;

        // Lấy danh sách mã nguyên liệu đã có trong kho cửa hàng này
        $nguyenLieuDaCo = CuaHangNguyenLieu::where('ma_cua_hang', $maCuaHang)
            ->pluck('ma_nguyen_lieu')
            ->toArray();

        // Lấy danh sách nguyên liệu CHƯA có trong cửa hàng và còn hoạt động
        $materials = NguyenLieu::where('trang_thai', 1)
            ->whereNotIn('ma_nguyen_lieu', $nguyenLieuDaCo)
            ->get();

        $tenCuaHang = CuaHang::where('ma_cua_hang', $maCuaHang)->value('ten_cua_hang');
        $viewData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'materials' => $materials,
            'ma_cua_hang' => $maCuaHang,
            'ten_cua_hang' => $tenCuaHang,
        ];

        return view('staffs.shop_materials.create', $viewData);
    }
    public function store(Request $request)
    {
        //$nhanVien = Auth::guard('staff')->user()->nhanvien;
        $request->validate([
            'ma_cua_hang' => 'required|exists:cua_hangs,ma_cua_hang',
            'ma_nguyen_lieu' => 'required|exists:nguyen_lieus,ma_nguyen_lieu',
            'so_luong_ton_min' => 'required|numeric|min:0',
            'so_luong_ton_max' => 'required|numeric|min:0',
        ], [
            'ma_nguyen_lieu.required' => 'Vui lòng chọn nguyên liệu.',
            'ma_nguyen_lieu.exists' => 'Nguyên liệu không tồn tại.',
            'ma_cua_hang.exists' => 'Cửa hàng không hợp lệ.',
            'so_luong_ton_min.required' => 'Vui lòng nhập số lượng tồn tối thiểu.',
            'so_luong_ton_max.required' => 'Vui lòng nhập số lượng tồn tối đa.',
        ]);

        // Kiểm tra nếu đã có nguyên liệu đó trong kho


        $nguyenLieu = NguyenLieu::where('ma_nguyen_lieu',$request->ma_nguyen_lieu)->first();
        if (!$nguyenLieu ) {
            return back()->withErrors(['Nguyên liệu không tồn tại.']);
        }
        //dd($nguyenLieu);

        CuaHangNguyenLieu::create([
            'ma_cua_hang' => $request->ma_cua_hang,
            'ma_nguyen_lieu' => $request->ma_nguyen_lieu,
            'so_luong_ton' => 0,
            'so_luong_ton_min' => $request->so_luong_ton_min,
            'so_luong_ton_max' => $request->so_luong_ton_max,
            'don_vi' => 'g',
        ]);
        toastr()->success('Đã thêm nguyên liệu vào kho.');
        return redirect()->route('staffs.shop_materials.index', ['ma_cua_hang' => $request->ma_cua_hang]);

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
        $importData = $request->input('import');
        $noteData = $request->input('note');
        $datensxuatData = $request->input('nsx');
        $datehsData = $request->input('hsd');

        if (!$importData || empty($importData)) {
            return redirect()->back()->withErrors(['import' => 'Không có dữ liệu nhập.'])->withInput();
        }

        $now = now()->startOfDay(); // lấy ngày hiện tại (không tính giờ phút)

        try {
            DB::transaction(function () use ($importData, $noteData, $datensxuatData, $datehsData, $nhanVien, $now, $request) {
                $updateData = [];

                foreach ($importData as $maCuaHang => $nguyenLieus) {
                    $materials = CuaHangNguyenLieu::with('nguyenLieu')
                        ->where('ma_cua_hang', $maCuaHang)
                        ->whereIn('ma_nguyen_lieu', array_keys($nguyenLieus))
                        ->get()
                        ->keyBy('ma_nguyen_lieu');

                    foreach ($nguyenLieus as $maNguyenLieu => $soLuongNhap) {
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
                        if (!is_numeric($soLuongNhap)) {
                            throw new \Exception("Số lượng nhập cho nguyên liệu $maNguyenLieu không hợp lệ (phải là số thực > 0).");
                        }

                        if ($soLuongNhap == 0) {
                            throw new \Exception("Số lượng nhập cho nguyên liệu $maNguyenLieu phải lớn hơn 0.");
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
                                'ma_cua_hang'    => $maCuaHang,
                                'ma_nguyen_lieu' => $maNguyenLieu,
                                'so_luong_ton'   => $material->so_luong_ton,
                            ];
                        }

                        $updateData[$updateKey]['so_luong_ton'] += $dinhluong;

                        PhieuNhapXuatNguyenLieu::create([
                            'ma_cua_hang'    => $maCuaHang,
                            'ma_nguyen_lieu' => $maNguyenLieu,
                            'ma_nhan_vien'   => $nhanVien->ma_nhan_vien,
                            'loai_phieu'     => 0, // Phiếu nhập
                            'so_lo'          => $request->soLo,
                            'ngay_san_xuat'  => $nsxDate,
                            'han_su_dung'    => $hsdDate,
                            'so_luong'       => $soLuongNhap,
                            'dinh_luong'     => $dinhluong,
                            'so_luong_ton_truoc' => $material->so_luong_ton ,
                            'don_vi'         => $material->don_vi,
                            'gia_tien'       => $material->nguyenLieu->gia ?? 0,
                            'tong_tien'      => ($material->nguyenLieu->gia ?? 0) * $soLuongNhap,
                            'ngay_tao_phieu' => now(),
                            'ghi_chu'        => $noteData[$maCuaHang][$maNguyenLieu] ?? null,
                        ]);
                    }
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
        $maCuaHang = $nhanVien -> ma_cua_hang;
        $today = Carbon::now()->format('d/m/Y');
        $materialKeys = $request->input('materials', []);
        $materials = collect();

        foreach ($materialKeys as $key) {
            [$maCuaHang, $maNguyenLieu] = explode('|', $key) + [null, null];

            if (!$maCuaHang || !$maNguyenLieu) continue;

            $material = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang')
                ->where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->first();

            if ($material) {
                $phieuNhaps = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 0)
                    ->orderBy('han_su_dung', 'asc')
                    ->get();

                $xuathuyData = PhieuNhapXuatNguyenLieu::select('so_lo', DB::raw('SUM(dinh_luong) as total'))
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->whereIn('loai_phieu', [1, 2]) // 1: Xuất, 2: Hủy
                    ->groupBy('so_lo')
                    ->pluck('total', 'so_lo'); // ['LOxxx' => tổng đã xuất/hủy]

                $availableBatches = collect();

                foreach ($phieuNhaps as $lo) {
                    $tongXuatHuy = $xuathuyData->get($lo->so_lo, 0);

                    $conLai = $lo->dinh_luong - $tongXuatHuy;

                    if ($conLai > 0) {
                        $availableBatches->push([
                            'so_lo' => $lo->so_lo,
                            'con_lai' => $conLai,
                            'han_su_dung' => $lo->han_su_dung,
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
        $exportData = $request->input('export');
        $noteData = $request->input('note');

        if (!$exportData || empty($exportData)) {
            return redirect()->back()->withErrors(['export' => 'Không có dữ liệu xuất.'])->withInput();
        }

        $now = now()->startOfDay();

        try {
            DB::transaction(function () use ($exportData,$nhanVien , $noteData, $now) {
                $updateData = [];

                foreach ($exportData as $maCuaHang => $nguyenLieus) {
                    foreach ($nguyenLieus as $maNguyenLieu => $soLuongXuat) {
                        if (!is_numeric($soLuongXuat) || $soLuongXuat <= 0) {
                            throw new \Exception("Số lượng xuất cho nguyên liệu $maNguyenLieu phải là số dương.");
                        }
                        $ghiChu = $noteData[$maCuaHang][$maNguyenLieu] ?? '';

                        $phieuNhapList = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                            ->where('ma_nguyen_lieu', $maNguyenLieu)
                            ->where('loai_phieu', 0) // phiếu nhập
                            ->where('han_su_dung', '>=', $now)
                            ->orderBy('han_su_dung', 'asc')       // ưu tiên hạn sử dụng gần nhất trước
                            ->orderBy('ngay_tao_phieu', 'asc')    // ưu tiên phiếu nhập cũ hơn
                            ->get();

                        $tongTon = 0;
                        foreach ($phieuNhapList as $lo) {
                            $dinhLuongDaXuat = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                                ->where('ma_nguyen_lieu', $maNguyenLieu)
                                ->where('loai_phieu', 1)
                                ->where('so_lo', $lo->so_lo)
                                ->sum('dinh_luong');

                            $tonLo = $lo->dinh_luong - $dinhLuongDaXuat;
                            $tongTon += max($tonLo, 0);
                        }

                        if ($tongTon <= 0) {
                            throw new \Exception("Nguyên liệu $maNguyenLieu trong cửa hàng $maCuaHang đã hết.");
                        }

                        $material = CuaHangNguyenLieu::where('ma_cua_hang', $maCuaHang)
                            ->where('ma_nguyen_lieu', $maNguyenLieu)
                            ->first();

                        if (!$material || $material->so_luong_ton <= 0) {
                            throw new \Exception("Không tìm thấy nguyên liệu hoặc đã hết tồn kho.");
                        }

                        $dinhLuongCan = $soLuongXuat * $material->nguyenLieu->so_luong;

                        if ($dinhLuongCan > $material->so_luong_ton) {
                            throw new \Exception("Số lượng xuất vượt tồn kho hiện tại cho nguyên liệu $maNguyenLieu.");
                        }

                        // Kiểm tra tồn tối thiểu
                        $tonSauKhiXuat = $material->so_luong_ton - $dinhLuongCan;
                        $tonToiThieu = $material->nguyenLieu->so_luong_ton_toi_thieu ?? 0;

                        if ($tonSauKhiXuat < $tonToiThieu) {
                            throw new \Exception("Không thể xuất nguyên liệu $maNguyenLieu vì sau xuất tồn kho sẽ nhỏ hơn tồn tối thiểu.");
                        }


                        $dinhLuongConLai = $dinhLuongCan;
                        $tongXuatThucTe = 0;

                        foreach ($phieuNhapList as $lo) {
                            $dinhLuongDaXuat = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                                ->where('ma_nguyen_lieu', $maNguyenLieu)
                                ->where('loai_phieu', 1)
                                ->where('so_lo', $lo->so_lo)
                                ->sum('dinh_luong');

                            $tonLo = $lo->dinh_luong - $dinhLuongDaXuat;
                            if ($tonLo <= 0) continue;

                            $xuatTuLo = min($dinhLuongConLai, $tonLo);
                            //$ghiChu = is_array($noteData) ? ($noteData[$maNguyenLieu] ?? '') : $noteData;
                            PhieuNhapXuatNguyenLieu::create([
                                'ma_cua_hang'         => $maCuaHang,
                                'ma_nguyen_lieu'      => $maNguyenLieu,
                                'ma_nhan_vien'        => $nhanVien->ma_nhan_vien,
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
                                'ghi_chu' => trim($ghiChu) . ' - Xuất theo FIFO từ lô ' . $lo->so_lo,
                            ]);

                            $tongXuatThucTe += $xuatTuLo;
                            $dinhLuongConLai -= $xuatTuLo;

                            if ($dinhLuongConLai <= 0) break;
                        }

                        if ($dinhLuongConLai > 0) {
                            throw new \Exception("Không đủ nguyên liệu trong kho theo FIFO để xuất nguyên liệu $maNguyenLieu.");
                        }

                        // Lưu dữ liệu để cập nhật tồn kho sau cùng (trừ đi lượng xuất thực tế)
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

                // Cập nhật tồn kho nguyên liệu đã xuất
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
        $maCuaHang = $nhanVien -> ma_cua_hang;
        $materialKeys = $request->input('materials', []);
        $materials = collect();

        foreach ($materialKeys as $key) {
            [$maCuaHang, $maNguyenLieu] = explode('|', $key) + [null, null];

            if (!$maCuaHang || !$maNguyenLieu) continue;

            $material = CuaHangNguyenLieu::with('nguyenLieu', 'cuaHang')
                ->where('ma_cua_hang', $maCuaHang)
                ->where('ma_nguyen_lieu', $maNguyenLieu)
                ->first();


            if ($material) {
                $phieuNhaps = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 0)
                    ->orderBy('han_su_dung', 'asc')
                    ->get();

                $xuathuyData = PhieuNhapXuatNguyenLieu::select('so_lo', DB::raw('SUM(dinh_luong) as total'))
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->whereIn('loai_phieu', [1, 2]) // 1: Xuất, 2: Hủy
                    ->groupBy('so_lo')
                    ->pluck('total', 'so_lo'); // ['LOxxx' => tổng đã xuất/hủy]

                $availableBatches = collect();

                foreach ($phieuNhaps as $lo) {
                    $tongXuatHuy = $xuathuyData->get($lo->so_lo, 0);

                    $conLai = $lo->dinh_luong - $tongXuatHuy;

                    if ($conLai > 0) {
                        $availableBatches->push([
                            'so_lo' => $lo->so_lo,
                            'con_lai' => $conLai,
                            'han_su_dung' => $lo->han_su_dung,
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

}
