<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use App\Models\CuaHang;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Models\SanPhamCuaHang;
use App\Models\Sizes;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validated = $request->validate([
            'ho_ten_khach_hang' => 'required|string|min:2|max:255',
            'so_dien_thoai' => 'required|regex:/^0\d{9}$/',
            'email' => 'required|email|max:255',
            'paymentMethod' => 'required|string|max:50',
            'ghi_chu' => 'nullable|string',
            'shippingMethod' => 'required|in:delivery,pickup',
        ], [
            'ho_ten_khach_hang.required' => 'Vui lòng nhập tên của bạn.',
            'ho_ten_khach_hang.min' => 'Tên ít nhất 2 ký tự.',
            'ho_ten_khach_hang.max' => 'Tên không quá 255 ký tự.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không quá 255 ký tự.',
            'paymentMethod.required' => 'Vui lòng chọn phương thức thanh toán.',
            'shippingMethod.required' => 'Vui lòng chọn hình thức nhận hàng.',
        ]);


        $khachHang = optional(Auth::user())->khachHang;
        $customerId = null;
        if ($khachHang) {
            $customerId = $khachHang->ma_khach_hang;
        }   

        $storeId = session('selected_store_id');
        if(!$storeId){
            toastr()->error('Vui lòng chọn cửa hàng.');
            return redirect()->route('cart');
        }
        $store = CuaHang::where('ma_cua_hang', $storeId)->first();
        $now = Carbon::now();
        $openTime = Carbon::createFromFormat('H:i:s', $store->gio_mo_cua);
        $closeTime = Carbon::createFromFormat('H:i:s', $store->gio_dong_cua);

        if (!$now->between($openTime, $closeTime)) {
            toastr()->error('Cửa hàng hiện đang đóng cửa. Vui lòng chọn cửa hàng khác hoặc quay lại trong giờ mở cửa.');
            return redirect()->route('cart');
        }  

        $storeCheck = $this->checkStore($storeId);
        if (!$storeCheck['success']) {
            toastr()->error($storeCheck['message'] ?? 'Lỗi không xác định.');
            return redirect()->route('cart');
        }

        if ($this->checkCartPrices()) {
            toastr()->warning('Một số sản phẩm đã được cập nhật giá. Vui lòng kiểm tra lại giỏ hàng!');
            return redirect()->route('cart');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            toastr()->warning('Giỏ hàng của bạn đang trống.');
            return redirect()->route('cart');
        }

        $shippingFee = $request->shippingFee ?? 0;
        $subTotal = array_sum(array_column($cart, 'money'));
        $discount = 0;
        $voucherCode = $request->voucher ?? null;
        $voucher = null;

        if ($voucherCode) {
            $voucher = KhuyenMai::where('ma_voucher', $voucherCode)->first();
            if (!$voucher) {
                toastr()->warning('Voucher không tồn tại.');
                return redirect()->back();
            }

            if ($voucher->so_luong <= 0) {
                toastr()->warning('Voucher đã hết lược sử dụng.');
                return redirect()->back();
            }

            if (now()->lt($voucher->ngay_bat_dau) || now()->gt($voucher->ngay_ket_thuc)) {
                toastr()->warning('Voucher đã hết hạn.');
                return redirect()->back();
            }

            // Áp dụng giảm giá
            $discount = $voucher->gia_tri_giam <= 100
                ? $subTotal * ($voucher->gia_tri_giam / 100)
                : $voucher->gia_tri_giam;

            $discount = min($discount, $subTotal);

            // Trừ 1 lượt sử dụng
            $voucher->so_luong -= 1;
            $voucher->save();
        }
      
        $total = $subTotal + $shippingFee - $discount;

        if($validated['shippingMethod'] == 'pickup'){
            $address = session('selected_store_name').' - '.session('selected_store_dia_chi');
        }
        if ($validated['shippingMethod'] == 'delivery') {
            $address = $request->ten_duong . ', ' . $request->wardName . ', ' . $request->districtName . ', ' . $request->provinceName;
            if (!$address || strlen(trim($address)) < 5) {
                toastr()->error('Địa chỉ giao hàng không hợp lệ.');
                return redirect()->back();
            }
            $check = $this->checkAddress($address);
            //dd($check);
            if (!$check['success']) {
                toastr()->error($check['message'] ?? 'Khoảng cách giao hàng không được quá 3 km');
                return redirect()->back();
            }
            $address = $request->so_nha . ' ' . $check['address']; 
        }

        $this->subtractIngredients($storeId, $storeCheck['usedIngredients']);

        if ($request->paymentMethod === 'COD') {

            $orderData = [
                'ma_cua_hang' => $storeId,
                'ma_khach_hang' => $customerId,
                'ma_voucher' => $voucherCode ?: null,
                'ten_khach_hang' => $validated['ho_ten_khach_hang'],
                'so_dien_thoai' => $validated['so_dien_thoai'],
                'email' => $validated['email'],
                'dia_chi' => $address,
                'phuong_thuc_thanh_toan' => 'COD',
                'phuong_thuc_nhan_hang' => $validated['shippingMethod'],
                'ghi_chu' => $validated['ghi_chu'] ?? '',
                'tam_tinh' => $subTotal,
                'tien_ship' => $shippingFee,
                'khuyen_mai' => $voucher ? $voucher->gia_tri_giam : 0,
                'giam_gia' => $discount,
                'tong_tien' => $total,
                'cart_items' => $cart,
                
            ];

            try {
                $maHoaDon = $this->processCOD($orderData);
                    // Lấy lại token từ DB sau khi insert
                    $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
                    $orderData['maHoaDon'] = $maHoaDon;
                    $orderData['token_bao_mat'] = $hoaDon->token_bao_mat;
                    $orderData['trang_thai'] = 'Chờ xác nhận';
                    $orderData['trang_thai_thanh_toan'] = 'Thanh toán khi nhận hàng';

                    $this->sendEmail(
                        $orderData['maHoaDon'],
                        $orderData['ten_khach_hang'],
                        $orderData['email'],
                        $orderData['so_dien_thoai'],
                        $orderData['phuong_thuc_nhan_hang'],
                        $orderData['phuong_thuc_thanh_toan'],
                        $orderData['trang_thai'],
                        $orderData['trang_thai_thanh_toan'],
                        $orderData['dia_chi'],
                        $orderData['cart_items'],
                        $orderData['tam_tinh'],
                        $orderData['giam_gia'],
                        $orderData['tien_ship'],
                        $orderData['tong_tien'],
                        $orderData['token_bao_mat'],
                    );

                session()->forget('cart'); 
                event(new \App\Events\OrderCreated($hoaDon));
                
                return redirect()->route('theoDoiDonHang', [
                    'orderCode' => $maHoaDon,
                    'token' => $orderData['token_bao_mat'],
                ]);

            } catch (\Exception $e) {
                toastr()->error('Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau! '. $e->getMessage());
                return redirect()->back();
            }
        }
        if ($request->paymentMethod === 'NAPAS247') {

            $orderData = [
                'ma_cua_hang' => $storeId,
                'ma_khach_hang' => $customerId,
                'ma_voucher' => $voucherCode ?: null,
                'ten_khach_hang' => $validated['ho_ten_khach_hang'],
                'so_dien_thoai' => $validated['so_dien_thoai'],
                'email' => $validated['email'],
                'dia_chi' => $address,
                'phuong_thuc_thanh_toan' => 'NAPAS247',
                'phuong_thuc_nhan_hang' => $validated['shippingMethod'],
                'ghi_chu' => $validated['ghi_chu'] ?? '',
                'tam_tinh' => $subTotal,
                'tien_ship' => $shippingFee,
                'khuyen_mai' => $voucher ? $voucher->gia_tri_giam : 0,
                'giam_gia' => $discount,
                'tong_tien' => $total,
                'cart_items' => $cart,
            ];
            $payOS = app(\App\Http\Controllers\payments\PayOSController::class);

            return $payOS->createPaymentLinkFromOrderData($orderData);
        }
        toastr()->error('Phương thức thanh toán không hợp lệ.');
        return redirect()->back();
    }
    public function processCOD(array $data)
    {
        $maHoaDon = HoaDon::generateMaHoaDon();
        $token_bao_mat = Str::random(32);

        DB::table('hoa_dons')->insert([
            'ma_hoa_don' => $maHoaDon,
            'ma_cua_hang' => $data['ma_cua_hang'],
            'ma_voucher' => $data['ma_voucher'] ?: null,
            'ma_khach_hang' => $data['ma_khach_hang'],
            'ten_khach_hang' => $data['ten_khach_hang'],  
            'email' => $data['email'],
            'so_dien_thoai' => $data['so_dien_thoai'],
            'dia_chi' => $data['dia_chi'],
            'phuong_thuc_thanh_toan' => $data['phuong_thuc_thanh_toan'],
            'phuong_thuc_nhan_hang' => $data['phuong_thuc_nhan_hang'],
            'ghi_chu' => $data['ghi_chu'],
            'tam_tinh' => $data['tam_tinh'] ?? 0,
            'tien_ship' => $data['tien_ship'] ?? 0,
            'khuyen_mai' => $data['khuyen_mai'] ?? 0,
            'giam_gia' => $data['giam_gia'] ?? 0,
            'tong_tien' => $data['tong_tien'],
            'trang_thai_thanh_toan' => 0,
            'trang_thai' => 0,
            'token_bao_mat' => $token_bao_mat,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($data['cart_items'] as $item) {
            DB::table('chi_tiet_hoa_dons')->insert([
                'ma_hoa_don' => $maHoaDon,
                'ma_san_pham' => $item['product_id'],
                'ten_san_pham' => $item['product_name'],
                'ten_size' => $item['size_name'],
                'gia_size' => $item['size_price'],
                'so_luong' => $item['product_quantity'],
                'don_gia' => $item['product_price'],
                'thanh_tien' => $item['money'],
                'ghi_chu' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $maHoaDon;
    }
    public function checkStore($storeId)
    {
        $cart = session()->get('cart', []);
        $totalUsedIngredients = [];

        $statusCheck = $this->checkStatus($cart, $storeId);
        if (!$statusCheck['success']) {
            return $statusCheck;
        }

        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $sizeId = $item['size_id'];
            $quantity = (int)$item['product_quantity'];

            // Lấy loại sản phẩm để check nếu là đóng gói (loai_san_pham == 1) thì bỏ qua
            $product = DB::table('san_phams')
                ->select('loai_san_pham')
                ->where('ma_san_pham', $productId)
                ->first();

            if ($product && $product->loai_san_pham == 1) {
                continue; // bỏ qua sản phẩm đóng gói
            }

            $ingredients = DB::table('thanh_phan_san_phams')
                ->where('ma_san_pham', $productId)
                ->where('ma_size', $sizeId)
                ->get();

            foreach ($ingredients as $ingredient) {
                $idNL = $ingredient->ma_nguyen_lieu;
                $totalUsedIngredients[$idNL] = ($totalUsedIngredients[$idNL] ?? 0) + $ingredient->dinh_luong * $quantity;
            }
        }

        $ingredientStocks = DB::table('cua_hang_nguyen_lieus')
            ->where('ma_cua_hang', $storeId)
            ->pluck('so_luong_ton', 'ma_nguyen_lieu');

        foreach ($totalUsedIngredients as $ingredientId => $requiredQty) {
            $stock = $ingredientStocks[$ingredientId] ?? null;

            if (is_null($stock) || $requiredQty > $stock) {
                return [
                    'success' => false,
                    'message' => "Nguyên liệu không đủ cung cấp cho sản phẩm trong giỏ hàng. Hãy chọn cửa hàng khác!"
                ];
            }
        }

        return ['success' => true, 'usedIngredients' => $totalUsedIngredients];
    }
    public function checkStatus($cart, $storeId)
    {
        $storeName = session('selected_store_name');

        foreach ($cart as $item) {
            $productId = $item['product_id'];

            // Check trạng thái sản phẩm
            $product = SanPham::where('ma_san_pham', $productId)->first();
            if (!$product || $product->trang_thai != 1) {
                return [
                    'success' => false,
                    'message' => "Sản phẩm \"" . ($item['product_name'] ?? '') . "\" hiện đã ngừng bán."
                ];
            }

            // Check trạng thái sản phẩm ở cửa hàng
            $productStore = SanPhamCuaHang::where('ma_san_pham', $productId)
                ->where('ma_cua_hang', $storeId)
                ->first();

            if (!$productStore || $productStore->trang_thai != 1) {
                return [
                    'success' => false,
                    'message' => "Sản phẩm \"" . ($item['product_name'] ?? '') . "\" đã ngưng bán tại cửa hàng ".$storeName
                ];
            }
        }

        return ['success' => true];
    }
    public function checkCartPrices()
    {
        $cart = session('cart', []);

        foreach ($cart as $item) {
            $product = SanPham::where('ma_san_pham', $item['product_id'])->first();
            if (!$product) continue;

            $expectedPrice = $product->gia;
            $expectedSizePrice = 0;

            // Mặc định giá size là 0 nếu là sản phẩm đóng gói
            if ($product->loai_san_pham != 1 && !empty($item['size_id'])) {
                $size = Sizes::where('ma_size', $item['size_id'])->first();
                $expectedSizePrice = $size ? $size->gia_size : 0;
            }

            if (
                round($item['product_price'], 0) != round($expectedPrice, 0) ||
                round($item['size_price'], 0) != round($expectedSizePrice, 0)
            ) {
                $cartKey = $item['product_id'] . '_' . ($item['size_id'] ?? '0');
                $item['product_price'] = $expectedPrice;
                $item['size_price'] = $expectedSizePrice;
                $item['money'] = ($expectedPrice + $expectedSizePrice) * $item['product_quantity'];

                $cart[$cartKey] = $item;
                session()->put('cart', $cart);

                toastr()->warning("Giá của sản phẩm trong giỏ hàng đã thay đổi. Đã cập nhật lại trong giỏ hàng.");
                return redirect()->back();
            }
        }
    }
    public function subtractIngredients($storeId, $usedIngredients)
    {
        foreach ($usedIngredients as $ingredientId => $requiredQty) {
            DB::table('cua_hang_nguyen_lieus')
                ->where('ma_cua_hang', $storeId)
                ->where('ma_nguyen_lieu', $ingredientId)
                ->decrement('so_luong_ton', $requiredQty);
        }

        $cart = session('cart', []);
        foreach ($cart as $item) {
            $product = DB::table('san_phams')
                ->select('loai_san_pham')
                ->where('ma_san_pham', $item['product_id'])
                ->first();

            if (!$product || $product->loai_san_pham != 1) {
                continue;
            }

            $quantity = (int)$item['product_quantity'];

            $ingredients = DB::table('thanh_phan_san_phams')
                ->where('ma_san_pham', $item['product_id'])
                ->whereNull('ma_size') // đặc trưng sản phẩm đóng gói
                ->get();

            foreach ($ingredients as $ingredient) {
                $totalQty = $ingredient->dinh_luong * $quantity;

                DB::table('cua_hang_nguyen_lieus')
                    ->where('ma_cua_hang', $storeId)
                    ->where('ma_nguyen_lieu', $ingredient->ma_nguyen_lieu)
                    ->decrement('so_luong_ton', $totalQty);
            }
        }
    }
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); 
    }
    private function checkAddress($address)
    {
        if (!$address || strlen(trim($address)) < 5) {
            return [
                'success' => false,
                'message' => 'Địa chỉ không hợp lệ.'
            ];
        }

        $address = trim(preg_replace('/\s+/', ' ', $address)) . ', Việt Nam';
        $geoData = Http::get('https://us1.locationiq.com/v1/search.php', [
            'key' => 'pk.5d26a9b9c838efaf212bce38a4a99682',
            'q' => $address,
            'format' => 'json',
            'limit' => 1
        ]);


        if ($geoData->failed() || empty($geoData->json())) {
            return [
                'success' => false,
                'message' => 'Không thể xác định vị trí từ địa chỉ giao hàng.'
            ];
        }

        $data = $geoData->json()[0];
        $latUser = floatval($data['lat']);
        $lngUser = floatval($data['lon']);

        $storeId = session('selected_store_id');
        if (!$storeId) {
            return [
                'success' => false,
                'message' => 'Không xác định được mã cửa hàng.'
            ];
        }

        $store = CuaHang::where('ma_cua_hang', $storeId)->first();
        if (!$store || !$store->latitude || !$store->longitude) {
            return [
                'success' => false,
                'message' => 'Không xác định được tọa độ cửa hàng.'
            ];
        }

        $distance = $this->calculateDistance(
            floatval($store->latitude),
            floatval($store->longitude),
            $latUser,
            $lngUser
        );

        if ($distance > 3) {
            return [
                'success' => false,
                'message' => "Khoảng cách giao hàng vượt quá 3 km (hiện tại: {$distance} km)"
            ];
        }

        return [
            'success' => true,
            'message' => '',
            'lat' => $latUser,
            'lng' => $lngUser,
            'distance' => $distance,
            'address' => $address 
        ];
    }
    public function sendEmail($order_id, $name, $email, $phone, $shippingMethod, $paymentMethod, $status, $statusPayment, $address, $cartItems, $tamTinh, $giamGia, $tienShip, $tongTien, $token)
    {
        try {
            Mail::to($email)->send(new OrderMail(
                $order_id,
                $name,
                $email,
                $phone,
                $shippingMethod,
                $paymentMethod,
                $status,
                $statusPayment,
                $address,
                now()->format('d/m/Y H:i'),
                $cartItems,
                $tamTinh,
                $giamGia,
                $tienShip,
                $tongTien,
                $token,
            ));
        } catch (\Exception $e) {
            // Ghi log lỗi nếu cần
            \Log::error('Gửi mail thất bại: ' . $e->getMessage());
        }
    }
    public function checkoutStatus(){
        $viewData = [
            'title' => 'Trạng thái đơn hàng | CDMT Coffee & Tea',    
        ];
        return view('clients.pages.payments.checkout_status', $viewData);
    }
    public function paymentSuccess(Request $request, $orderCode)
    {
        $token = $request->query('token');

        $hoaDon = HoaDon::with('chiTietHoaDon', 'transaction', 'cuaHang')
            ->where('ma_hoa_don', $orderCode)
            ->where('token_bao_mat', $token)
            ->first();

        $viewData = [
            'title' => 'Thông tin đơn hàng ' . $orderCode,
            'hoaDon' => $hoaDon,
            'error' => !$hoaDon ? 'Không tìm thấy hóa đơn hoặc token không hợp lệ.' : null
        ];

        return view('clients.pages.payments.thanh-cong', $viewData);
    }
}
