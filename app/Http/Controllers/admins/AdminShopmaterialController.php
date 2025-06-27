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

use function Laravel\Prompts\search;

class AdminShopmaterialController extends Controller
{
    public function index(Request $request)
    {
        $search = null;
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

        //$materials = $query->paginate(10);
        $perPage = 10;
        $page = $request->input('page', 1);
        $materials = $query->paginate($perPage);
        $lastPage = $materials->lastPage();

        // Nếu người dùng nhập page > lastPage => redirect về lastPage
        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('admins.shopmaterial.index', array_merge(
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }
        $stores = CuaHang::all();
        return view('admins.shopmaterial.index', [
            'materials' => $materials,
            'stores' => $stores,
            'title' => 'Quản lý nguyên vật liệu cửa hàng',
            'subtitle' => 'Quản lý danh sách nguyên vật liệu',
            'search'=>$search
        ]);
    }
    public function create(Request $request)
    {
        $title = 'Thêm nguyên liệu';
        $subtitle = 'Thêm nguyên liệu vào kho';
        if (!$request->has('ma_cua_hang')) {
            toastr()->error('Vui lòng chọn cửa hàng!');
            return redirect()->route('admins.shopmaterial.index');
        }

        $maCuaHang = $request->ma_cua_hang;

        // Lấy danh sách mã nguyên liệu đã có trong kho cửa hàng này
        $nguyenLieuDaCo = CuaHangNguyenLieu::where('ma_cua_hang', $maCuaHang)
            ->pluck('ma_nguyen_lieu')
            ->toArray();

        // Lấy danh sách nguyên liệu CHƯA có trong cửa hàng và còn hoạt động
        $materials = NguyenLieu::where('trang_thai', 1)
            ->whereNotIn('ma_nguyen_lieu', $nguyenLieuDaCo)
             ->get(['ma_nguyen_lieu', 'ten_nguyen_lieu', 'don_vi', 'so_luong','gia']);

        $tenCuaHang = CuaHang::where('ma_cua_hang', $maCuaHang)->value('ten_cua_hang');
        $viewData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'materials' => $materials,
            'ma_cua_hang' => $maCuaHang,
            'ten_cua_hang' => $tenCuaHang,
        ];

        return view('admins.shopmaterial.create', $viewData);
    }
    public function store(Request $request)
    {
        $request->validate([
            'ma_cua_hang' => 'required|exists:cua_hangs,ma_cua_hang',
            'ma_nguyen_lieu' => 'required|array|min:1',
            'ma_nguyen_lieu.*' => 'required|exists:nguyen_lieus,ma_nguyen_lieu',
            'so_luong_ton_min' => 'required|array',
            'don_vi' => 'required|array',
            'gia_nhap' =>'required|array',
        ], [
            'ma_nguyen_lieu.required' => 'Vui lòng chọn ít nhất một nguyên liệu.',
            'ma_nguyen_lieu.*.exists' => 'Một trong các nguyên liệu không tồn tại.',
            'so_luong_ton_min.required' => 'Vui lòng nhập số lượng tồn tối thiểu.',
            'don_vi.required' => 'Vui lòng nhập đơn vị cho mỗi nguyên liệu.',
            'gia_nhap.required' => 'Vui lòng nhập giá nhập cho nguyên liệu.',
        ]);

        foreach ($request->ma_nguyen_lieu as $maNL) {
        $daTonTai = CuaHangNguyenLieu::where([
            ['ma_cua_hang', $request->ma_cua_hang],
            ['ma_nguyen_lieu', $maNL]
        ])->exists();

        if (!$daTonTai) {
            $nguyenLieu = NguyenLieu::where('ma_nguyen_lieu', $maNL)->first();
            $inputMin = $request->so_luong_ton_min[$maNL] ?? 0;
            $dinhLuong = $nguyenLieu->so_luong ?? 1;
            $giaNhap = $request->gia_nhap[$maNL] ?? 0;

            CuaHangNguyenLieu::create([
                'ma_cua_hang' => $request->ma_cua_hang,
                'ma_nguyen_lieu' => $maNL,
                'so_luong_ton' => 0,
                'so_luong_ton_min' => (float)$inputMin * (float)$dinhLuong,
                'don_vi' => $request->don_vi[$maNL] ?? $nguyenLieu->don_vi,
                'gia_nhap' => $giaNhap, // ✅ Lưu giá nhập vào kho cửa hàng
            ]);
        }
    }


        toastr()->success('Đã thêm nguyên liệu vào kho.');
        return redirect()->route('admins.shopmaterial.index', ['ma_cua_hang' => $request->ma_cua_hang]);
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
                // Nếu chưa có giá nhập thì lấy giá_tiền (từ bảng nguyên liệu) làm mặc định
                if (is_null($material->gia_nhap)) {
                    $material->gia_nhap = $material->nguyenLieu->gia ?? 0;
                }
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
        $soLo = PhieuNhapXuatNguyenLieu::generateSoLo();

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
        $noteData = $request->input('note');
        $datensxuatData = $request->input('nsx');
        $datehsData = $request->input('hsd');

        if (!$importData || empty($importData)) {
            return redirect()->back()->withErrors(['import' => 'Không có dữ liệu nhập.'])->withInput();
        }
        $firstShopKey = array_key_first($importData);

        $now = now()->startOfDay(); // lấy ngày hiện tại (không tính giờ phút)

        try {
            DB::transaction(function () use ($importData, $noteData, $datensxuatData, $datehsData, $now, $request) {
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

                        //kiểm tra gia nhap
                        $giaNhapMoi = $request->input("gia_nhap.$maCuaHang.$maNguyenLieu");
                        if (is_numeric($giaNhapMoi) && $giaNhapMoi > 0) {
                            CuaHangNguyenLieu::where('ma_cua_hang', $maCuaHang)
                                ->where('ma_nguyen_lieu', $maNguyenLieu)
                                ->update(['gia_nhap' => $giaNhapMoi]);

                            // Đồng bộ với đối tượng hiện tại nếu dùng lại sau đó
                            $material->gia_nhap = $giaNhapMoi;
                        }



                        $dinhluong = $soLuongNhap * $material->nguyenLieu->so_luong;
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
                            'loai_phieu'     => 0, // Phiếu nhập
                            'so_lo'          => $request->soLo,
                            'ngay_san_xuat'  => $nsxDate,
                            'han_su_dung'    => $hsdDate,
                            'so_luong'       => $soLuongNhap,
                            'dinh_luong'     => $dinhluong,
                            'so_luong_ton_truoc' => $material->so_luong_ton ,
                            'don_vi'         => $material->don_vi,
                            'gia_tien'       => $material->nguyenLieu->gia ?? 0,
                            //'tong_tien'      => ($material->nguyenLieu->gia ?? 0) * $soLuongNhap,
                            //'gia_tien'       => $material->gia_nhap ?? 0,
                            //'tong_tien'      => ($material->gia_nhap ?? 0) * $soLuongNhap,
                            'gia_nhap'  => $material->gia_nhap ?? ($material->nguyenLieu->gia ?? 0), // fallback nếu chưa lưu
                            'tong_tien' => ($material->gia_nhap ?? ($material->nguyenLieu->gia ?? 0)) * $soLuongNhap,
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
            return redirect()->route('admins.shopmaterial.index', ['ma_cua_hang' => $firstShopKey]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'import' => 'Có lỗi xảy ra khi nhập nguyên liệu: ' . $e->getMessage()
            ])->withInput();
        }
    }
    public function showExportPage(Request $request)
    {
        $today = Carbon::now()->format('d/m/Y');
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
                // Lấy danh sách lô nhập
                $loList = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 0)
                    ->where('han_su_dung', '>=', $now)
                    ->orderBy('han_su_dung', 'asc')
                    ->orderBy('ngay_tao_phieu', 'asc')
                    ->get();

                // Tính định lượng đã dùng từ hóa đơn
                $fromHD = DB::table('chi_tiet_hoa_dons as cthd')
                    ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                    ->join('thanh_phan_san_phams as tp', 'cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                    ->where('hd.ma_cua_hang', $maCuaHang)
                    ->where('tp.ma_nguyen_lieu', $maNguyenLieu)
                    ->select('hd.ngay_lap_hoa_don as ngay_phat_sinh', DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong'))
                    ->get();

                // Từ phiếu xuất
                $fromPX = DB::table('phieu_nhap_xuat_nguyen_lieus')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 1)
                    ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                    ->get();

                // Từ phiếu hủy
                $fromHuy = DB::table('phieu_nhap_xuat_nguyen_lieus')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 2)
                    ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                    ->get();

                // Gộp toàn bộ giao dịch giảm
                $transactions = $fromHD->merge($fromPX)->merge($fromHuy)->sortBy('ngay_phat_sinh')->values();

                // Trừ FIFO
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
            return redirect()->route('admins.shopmaterial.index');
        }

        $soLo = PhieuNhapXuatNguyenLieu::generateSoLo();

        return view('admins.shopmaterial.export', [
            'materials' => $materials,
            'subtitle' => 'Xuất nguyên liệu khỏi cửa hàng',
            'title' => 'Xuất nguyên liệu | CDMT & tea and coffee',
            'soLo' => $soLo,
            'today' => $today,
        ]);
    }
    public function export(Request $request)
    {
        $exportData = $request->input('export');
        $noteData = $request->input('note');

        if (!$exportData || empty($exportData)) {
            return redirect()->back()->withErrors(['export' => 'Không có dữ liệu xuất.'])->withInput();
        }
        $firstShopKey = array_key_first($exportData);

        $now = now()->startOfDay();

        try {
            DB::transaction(function () use ($exportData, $noteData, $now) {
                $updateData = [];

                foreach ($exportData as $maCuaHang => $nguyenLieus) {
                    foreach ($nguyenLieus as $maNguyenLieu => $soLuongXuat) {
                        if (!is_numeric($soLuongXuat) || $soLuongXuat <= 0) {
                            throw new \Exception("Số lượng xuất cho nguyên liệu $maNguyenLieu phải là số dương.");
                        }

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
                        $tonToiThieu = $material->so_luong_ton_min ?? 0;

                        if ($tonSauKhiXuat < $tonToiThieu) {
                            throw new \Exception("Không thể xuất nguyên liệu $maNguyenLieu vì sau xuất tồn kho sẽ nhỏ hơn tồn tối thiểu.");
                        }

                        $dinhLuongConLai = $dinhLuongCan;
                        $tongXuatThucTe = 0;

                        $tonLoCache = [];

                        foreach ($phieuNhapList as $lo) {
                            $loKey = $lo->so_lo;

                            // Nếu chưa có trong cache, tính 1 lần rồi lưu lại
                            if (!isset($tonLoCache[$loKey])) {
                                $dinhLuongDaXuat = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                                    ->where('loai_phieu', 1)
                                    ->where('so_lo', $lo->so_lo)
                                    ->sum('dinh_luong');

                                $tonLoCache[$loKey] = $lo->dinh_luong - $dinhLuongDaXuat;
                            }

                            if ($tonLoCache[$loKey] <= 0) continue;

                            $xuatTuLo = min($dinhLuongConLai, $tonLoCache[$loKey]);

                            $giaNhap = $lo->gia_nhap ?? $material->gia_nhap ?? $material->nguyenLieu->gia ?? 0;
                            $giaNguyenLieu = $material->nguyenLieu->gia ?? 0;

                            PhieuNhapXuatNguyenLieu::create([
                                'ma_cua_hang'         => $maCuaHang,
                                'ma_nguyen_lieu'      => $maNguyenLieu,
                                'loai_phieu'          => 1,
                                'so_lo'               => $lo->so_lo,
                                'ngay_san_xuat'       => $lo->ngay_san_xuat,
                                'han_su_dung'         => $lo->han_su_dung,
                                'so_luong'            => $xuatTuLo / $material->nguyenLieu->so_luong,
                                'dinh_luong'          => $xuatTuLo,
                                'so_luong_ton_truoc'  => $material->so_luong_ton,
                                'don_vi'              => $material->don_vi,
                                'gia_tien'            => $giaNguyenLieu, // lấy từ bảng nguyen_lieu
                                'gia_nhap'            => $giaNhap,       // lấy từ lô nhập
                                'tong_tien'           => $giaNhap * ($xuatTuLo / $material->nguyenLieu->so_luong),
                                'ngay_tao_phieu'      => now(),
                                'ghi_chu'             => ($noteData[$maCuaHang][$maNguyenLieu] ?? '') . ' | Xuất theo FIFO từ lô ' . $lo->so_lo,
                            ]);

                            $tongXuatThucTe += $xuatTuLo;
                            $dinhLuongConLai -= $xuatTuLo;

                            // Trừ trực tiếp vào cache
                            $tonLoCache[$loKey] -= $xuatTuLo;

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
            return redirect()->route('admins.shopmaterial.index', ['ma_cua_hang' => $firstShopKey   ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'export' => 'Có lỗi khi xuất nguyên liệu: ' . $e->getMessage()
            ])->withInput();
        }
    }
    public function showDestroyPage(Request $request)
    {
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
                // Lấy các lô còn hạn sử dụng
                $loList = PhieuNhapXuatNguyenLieu::where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 0)
                    ->where('han_su_dung', '>=', $now)
                    ->orderBy('han_su_dung', 'asc')
                    ->orderBy('ngay_tao_phieu', 'asc')
                    ->get();

                // Định lượng đã tiêu hao từ hóa đơn
                $fromHD = DB::table('chi_tiet_hoa_dons as cthd')
                    ->join('hoa_dons as hd', 'cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                    ->join('thanh_phan_san_phams as tp', 'cthd.ma_san_pham', '=', 'tp.ma_san_pham')
                    ->where('hd.ma_cua_hang', $maCuaHang)
                    ->where('tp.ma_nguyen_lieu', $maNguyenLieu)
                    ->select('hd.ngay_lap_hoa_don as ngay_phat_sinh', DB::raw('tp.dinh_luong * cthd.so_luong as dinh_luong'))
                    ->get();

                // Phiếu xuất
                $fromPX = DB::table('phieu_nhap_xuat_nguyen_lieus')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 1)
                    ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                    ->get();

                // Phiếu hủy
                $fromHuy = DB::table('phieu_nhap_xuat_nguyen_lieus')
                    ->where('ma_cua_hang', $maCuaHang)
                    ->where('ma_nguyen_lieu', $maNguyenLieu)
                    ->where('loai_phieu', 2)
                    ->select('ngay_tao_phieu as ngay_phat_sinh', 'dinh_luong')
                    ->get();

                // Tổng hợp giao dịch giảm
                $transactions = $fromHD->merge($fromPX)->merge($fromHuy)->sortBy('ngay_phat_sinh')->values();

                // Trừ dần theo FIFO để biết còn lại bao nhiêu
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
            return redirect()->route('admins.shopmaterial.index');
        }

        return view('admins.shopmaterial.destroy', [
            'materials'    => $materials,
            'title'        => 'Hủy nguyên liệu | CDMT & tea and coffee',
            'subtitle'     => 'Hủy nguyên liệu theo lô',
            'today'        => Carbon::now()->format('d/m/Y'),
            'ma_cua_hang'  => $materialKeys[0] ?? '',
        ]);
    }
    public function destroy(Request $request)
    {
        $data = $request->input();
        $now = now();

        try {
            if (!isset($data['batch']) || !is_array($data['batch'])) {
                throw new \Exception("Dữ liệu không hợp lệ: thiếu thông tin lô nguyên liệu.");
            }
            $firstShopCode = array_key_first($data['batch']);

            DB::transaction(function () use ($data, $now) {
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
                            'loai_phieu'         => 2, // phiếu hủy
                            'so_lo'              => $soLo,
                            'ngay_san_xuat'      => $batchRecord->ngay_san_xuat,
                            'han_su_dung'        => $batchRecord->han_su_dung,
                            'so_luong'           => $quantity,
                            'dinh_luong'         => $dinhLuongHuy,
                            'so_luong_ton_truoc' => $materialStock->so_luong_ton,
                            'don_vi'             => $materialStock->don_vi,
                            //'gia_tien'           => optional($materialStock->nguyenLieu)->gia ?? 0,
                            //'tong_tien'          => (optional($materialStock->nguyenLieu)->gia ?? 0) * $quantity,
                            'gia_tien'          =>$batchRecord->gia_tien ?? 0,
                            'gia_nhap'           => $batchRecord->gia_nhap ?? 0,
                            'tong_tien'          => ($batchRecord->gia_nhap ?? 0) * $quantity,
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
            return redirect()->route('admins.shopmaterial.index', ['ma_cua_hang' => $firstShopCode]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Lỗi khi hủy nguyên liệu: ' . $e->getMessage());
        }
    }
    public function showAllPhieu(Request $request)
    {
        $query = PhieuNhapXuatNguyenLieu::query();
        $search = null;
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                if (strtoupper($search) === 'ADMIN') {
                    $q->whereNull('ma_nhan_vien');
                } else {
                    $q->where('ma_cua_hang', 'like', "%$search%")
                    ->orWhere('ma_nhan_vien', 'like', "%$search%");
                }
            });
        }
        $dsCuaHang = CuaHang::all();
        if ($request->filled('ma_cua_hang')) {
            $query->where('ma_cua_hang', $request->ma_cua_hang);
        }

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


        return view('admins.shopmaterial.list', [
            'title' => 'Danh sách phiếu',
            'subtitle' => 'Danh sách Phiếu Nhập - Xuất - Hủy',
            'danhSachPhieu' => $danhSachLo,
            'search'=>$search,
            'dsCuaHang' => $dsCuaHang,
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

