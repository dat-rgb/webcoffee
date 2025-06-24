<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\CuaHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdminStoreController extends Controller
{
    private function getToaDoTuDiaChi($address)
    {
        // Làm sạch địa chỉ
        $address = trim(preg_replace('/\s+/', ' ', $address)) . ', Việt Nam';

        if (strlen($address) < 5) {
            return [
                'success' => false,
                'message' => 'Địa chỉ không hợp lệ.'
            ];
        }

        try {
            $geoData = Http::timeout(5)->get('https://us1.locationiq.com/v1/search.php', [
                'key' => 'pk.5d26a9b9c838efaf212bce38a4a99682',
                'q' => $address,
                'format' => 'json',
                'limit' => 1
            ]);

            $data = $geoData->json();

            if ($geoData->failed() || empty($data) || !isset($data[0]['lat'])) {
                return [
                    'success' => false,
                    'message' => 'Không thể lấy tọa độ từ địa chỉ này.'
                ];
            }

            return [
                'success' => true,
                'latitude' => $data[0]['lat'],
                'longitude' => $data[0]['lon']
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi gọi API tọa độ: ' . $e->getMessage()
            ];
        }
    }
    public function generateMaCuaHang()
    {
        $lastStore = CuaHang::orderByDesc('ma_cua_hang')->first();

        if ($lastStore) {
            $lastNumber = intval(substr($lastStore->ma_cua_hang, 2)); // Bỏ "CH", lấy số
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'CH' . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
    }    
    public function index(Request $request)
    {
        $stores = CuaHang::all();
        $newStoreCode = $this->generateMaCuaHang(); 

        $viewData = [
            'title' => 'Danh sách cửa hàng | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách cửa hàng',
            'stores' => $stores,
            'newStoreCode' => $newStoreCode,
        ];
        return view('admins.pages.list_stores', $viewData);
    }
    public function addStore(Request $request)
    {
        $validated = $request->validate([
            'ma_cua_hang' => 'required|size:10|unique:cua_hangs,ma_cua_hang',
            'ten_cua_hang' => 'required|string|min:2|max:255',
            'so_nha' => 'nullable|string|max:100',
            'ten_duong' => 'nullable|string|max:100',
            'province' => 'nullable|numeric',
            'district' => 'nullable|numeric',
            'ward' => 'nullable|numeric',
            'provinceName' => 'nullable|string',
            'districtName' => 'nullable|string',
            'wardName' => 'nullable|string',
            'so_dien_thoai' => 'nullable|string|unique:cua_hangs,so_dien_thoai',
            'email' => 'nullable|string|unique:cua_hangs,email',
            'gio_mo_cua' => 'required',
            'gio_dong_cua' => 'required',
            'trang_thai' => 'required|in:0,1'
        ], [
            'ma_cua_hang.required' => 'Mã cửa hàng là bắt buộc.',
            'ma_cua_hang.size' => 'Mã cửa hàng phải đúng 10 ký tự.',
            'ma_cua_hang.unique' => 'Mã cửa hàng đã tồn tại.',
            'ten_cua_hang.required' => 'Tên cửa hàng là bắt buộc.',
            'ten_cua_hang.min' => 'Tên cửa hàng ít nhất 2 ký tự.',
            'ten_cua_hang.max' => 'Tên cửa hàng không vượt quá 255 ký tự.',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại.',
            'email.unique' => 'Email đã được sử dụng.',
            'gio_mo_cua.required' => 'Giờ mở cửa là bắt buộc.',
            'gio_dong_cua.required' => 'Giờ đóng cửa là bắt buộc.',
        ]);

        $slug = Str::slug($request->ten_cua_hang); 
        if (CuaHang::where('slug', $slug)->exists()) {
            toastr()->error('Tên cửa hàng đã được sử dụng');
            return redirect()->back()->withInput();
        }

        $addressForGeo = trim(preg_replace('/\s+/', ' ', 
        ($request->so_nha ?? '') . ', ' .
            ($request->ten_duong ?? '') . ', ' .
            ($request->wardName ?? '') . ', ' .
            ($request->districtName ?? '') . ', ' .
            ($request->provinceName ?? '')
        ));

        $toaDo = $this->getToaDoTuDiaChi($addressForGeo);
        if (!$toaDo['success']) {
            toastr()->error('Lỗi không tìm được địa chỉ');
            return redirect()->back()->withInput();
        }

        $latitude = $toaDo['latitude'];
        $longitude = $toaDo['longitude'];

        $fullAddress = trim(preg_replace('/\s+/', ' ', 
            ($request->so_nha ?? '') . ' ' .
            ($request->ten_duong ?? '') . ', ' .
            ($request->wardName ?? '') . ', ' .
            ($request->districtName ?? '') . ', ' .
            ($request->provinceName ?? '')
        ));

        DB::table('cua_hangs')->insert([
            'ma_cua_hang'   => $validated['ma_cua_hang'],
            'ten_cua_hang'  => $validated['ten_cua_hang'],
            'slug'          => $slug,
            'so_nha'        => $validated['so_nha'] ?? null,    
            'ten_duong'     => $validated['ten_duong'] ?? null,     
            'dia_chi'       => $fullAddress,
            'ma_tinh'       => $validated['province'] ?? null,
            'ma_quan'       => $validated['district'] ?? null,
            'ma_xa'         => $validated['ward'] ?? null,
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'email'         => $validated['email'] ?? null,
            'gio_mo_cua'    => $validated['gio_mo_cua'],
            'gio_dong_cua'  => $validated['gio_dong_cua'],
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'trang_thai'    => (int) $request->input('trang_thai'),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        toastr()->success('Thêm cửa hàng thành công!');
        return redirect()->back();
    }
    public function toggle(Request $request)
    {
        $check = $this->checkStore($request->input('store_ids'));
        if (!$check['pass']) {
            return response()->json([
                'success' => false,
                'message' => $check['message'],
                'errors' => $check['errors']
            ]);
        }

        // Nếu OK thì cập nhật trạng thái
        DB::table('cua_hangs')
            ->whereIn('ma_cua_hang', $request->input('store_ids'))
            ->update(['trang_thai' => DB::raw('1 - trang_thai')]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái cửa hàng thành công.'
        ]);
    }
    private function checkStore($storeIds)
    {
        $errors = [];

        foreach ($storeIds as $storeId) {
            $currentStatus = DB::table('cua_hangs')
                ->where('ma_cua_hang', $storeId)
                ->value('trang_thai');

            $hasActiveProducts = DB::table('san_pham_cua_hangs')
                ->where('ma_cua_hang', $storeId)
                ->where('trang_thai', 1)
                ->exists();

            if ($currentStatus == 1 && $hasActiveProducts) {
                $errors[] = $storeId;
            }
        }

        if (!empty($errors)) {
            return [
                'pass' => false,
                'message' => 'Không thể tắt các cửa hàng đang có sản phẩm đang bán.',
                'errors' => $errors
            ];
        }

        return ['pass' => true];
    }
    public function updateStore(Request $request, $id)
    {
        $validated = $request->validate([
            'ten_cua_hang' => 'required|string|min:2|max:255',
            'so_nha'       => 'nullable|string|max:100',
            'ten_duong'    => 'nullable|string|max:100',
            'province'     => 'nullable|numeric',
            'district'     => 'nullable|numeric',
            'ward'         => 'nullable|numeric',
            'provinceName' => 'nullable|string',
            'districtName' => 'nullable|string',
            'wardName'     => 'nullable|string',
            'so_dien_thoai'=> 'nullable|string|unique:cua_hangs,so_dien_thoai,' . $id,
            'email'        => 'nullable|string|unique:cua_hangs,email,' . $id,
            'gio_mo_cua'   => 'required',
            'gio_dong_cua' => 'required',
            'trang_thai'   => 'required|in:0,1'
        ], [
            'ten_cua_hang.required' => 'Tên cửa hàng là bắt buộc.',
            'ten_cua_hang.min'      => 'Tên cửa hàng ít nhất 2 ký tự.',
            'ten_cua_hang.max'      => 'Tên cửa hàng không vượt quá 255 ký tự.',
            'so_dien_thoai.unique'  => 'Số điện thoại đã tồn tại.',
            'email.unique'          => 'Email đã được sử dụng.',
            'gio_mo_cua.required'   => 'Giờ mở cửa là bắt buộc.',
            'gio_dong_cua.required' => 'Giờ đóng cửa là bắt buộc.',
        ]);

        $slug = Str::slug($validated['ten_cua_hang']);
        if (CuaHang::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            toastr()->error('Tên cửa hàng đã được sử dụng');
            return redirect()->back()->withInput();
        }

        $addressForGeo = trim(preg_replace('/\s+/', ' ',
            ($validated['so_nha'] ?? '') . ', ' .
            ($validated['ten_duong'] ?? '') . ', ' .
            ($validated['wardName'] ?? '') . ', ' .
            ($validated['districtName'] ?? '') . ', ' .
            ($validated['provinceName'] ?? '')
        ));

        $toaDo = $this->getToaDoTuDiaChi($addressForGeo);
        if (!$toaDo['success']) {
            toastr()->error('Lỗi không tìm được địa chỉ');
            return redirect()->back()->withInput();
        }

        $latitude = $toaDo['latitude'];
        $longitude = $toaDo['longitude'];

        $fullAddress = trim(preg_replace('/\s+/', ' ',
            ($validated['so_nha'] ?? '') . ' ' .
            ($validated['ten_duong'] ?? '') . ', ' .
            ($validated['wardName'] ?? '') . ', ' .
            ($validated['districtName'] ?? '') . ', ' .
            ($validated['provinceName'] ?? '')
        ));

        DB::table('cua_hangs')->where('id', $id)->update([
            'ten_cua_hang'  => $validated['ten_cua_hang'],
            'slug'          => $slug,
            'so_nha'        => $validated['so_nha'] ?? null,
            'ten_duong'     => $validated['ten_duong'] ?? null,
            'dia_chi'       => $fullAddress,
            'ma_tinh'       => $validated['province'] ?? null,
            'ma_quan'       => $validated['district'] ?? null,
            'ma_xa'         => $validated['ward'] ?? null,
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'email'         => $validated['email'] ?? null,
            'gio_mo_cua'    => $validated['gio_mo_cua'],
            'gio_dong_cua'  => $validated['gio_dong_cua'],
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'trang_thai'    => (int) $request->input('trang_thai'),
            'updated_at'    => now(),
        ]);

        toastr()->success('Cập nhật cửa hàng thành công!');
        return redirect()->back();
    }
}
