<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Models\Sizes;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        //dd($request->all());
        // Validate
        $validated = $request->validate([
            'ho_ten_khach_hang' => 'required|string|min:2|max:255',
            'so_dien_thoai' => 'required|regex:/^0\d{9}$/',
            'email' => 'required|email|max:255',
            'dia_chi' => 'nullable|string|max:255',
            'paymentMethod' => 'required|string|max:50',
            'ghi_chu' => 'nullable|string',
            'shippingMethod' => 'required',
            
        ], [
            'ho_ten_khach_hang.required' => 'Vui lòng nhập tên của bạn.',
            'ho_ten_khach_hang.min' => 'Tên ít nhất 2 ký tự.',
            'ho_ten_khach_hang.max' => 'Tên không quá 255 ký tự.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không quá 255 ký tự.',
            'dia_chi.max' => 'Địa chỉ không quá 255 ký tự.',
            'paymentMethod.required' => 'Vui lòng chọn phương thức thanh toán.',
            'shippingMethod.required' => 'Vui lòng chọn hình thức nhận hàng.',
        ]);

        
        $khachHang = optional(Auth::user())->khachHang;
        $customerId = null;
        if ($khachHang) {
            $customerId = $khachHang->ma_khach_hang;
        }   

        $address = $request->dia_chi .' '. $request->wardName .' '. $request-> districtName .' '. $request->provinceName;
        $storeId = session('selected_store_id');
        if(!$storeId){
            return redirect()->back()->with('error', 'Vui lòng chọn cửa hàng.');
        }

        $storeCheck = $this->checkStore($storeId);
        if (!$storeCheck['success']) {
            return redirect()->back()->with('error', $storeCheck['message'] ?? 'Lỗi không xác định.');
        }

        if ($this->checkCartPrices()) {
            toastr()->warning('Một số sản phẩm đã được cập nhật giá. Vui lòng kiểm tra lại giỏ hàng!');
            return redirect()->route('cart');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
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
            $address = 'Đến lấy tại cửa hàng: ' . session('selected_store_name').' - '.session('selected_store_dia_chi');
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
                'tien_ship' => $shippingFee,
                'khuyen_mai' => $voucher ? $voucher->gia_tri_giam : 0,
                'giam_gia' => $discount,
                'tong_tien' => $total,
                'cart_items' => $cart,
            ];

            //dd($orderData);

            try {
                $maHoaDon = $this->processCOD($orderData);
               
                    $orderData['maHoaDon'] = $maHoaDon;
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
                        $orderData['tong_tien'],
                    );

                session()->forget('cart'); 
                toastr()->success('Đặt hàng thành công!');
                return redirect()->route('checkout_status')->with('status', 'success');
            } catch (\Exception $e) {
                // Ghi log nếu cần: Log::error($e->getMessage());
                toastr()->error('Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau! '. $e->getMessage());
                return redirect()->back();
            }
        }
        if ($request->paymentMethod === 'NAPAS247') {
            $napas = new Napas247Controller();

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
                'tien_ship' => $shippingFee,
                'khuyen_mai' => $voucher ? $voucher->gia_tri_giam : 0,
                'giam_gia' => $discount,
                'tong_tien' => $total,
                'cart_items' => $cart,
            ];
            
            return $napas->createPaymentLink($orderData); 
        }
        return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }
    public function processCOD(array $data)
    {
        $maHoaDon = HoaDon::generateMaHoaDon();

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
            'tien_ship' => $data['tien_ship'] ?? 0,
            'khuyen_mai' => $data['khuyen_mai'] ?? 0,
            'giam_gia' => $data['giam_gia'] ?? 0,
            'tong_tien' => $data['tong_tien'],
            'trang_thai_thanh_toan' => 0,
            'trang_thai' => 0,
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

        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $sizeId = $item['size_id'];
            $quantity = (int)$item['product_quantity'];

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
                    'message' => "Nguyên liệu không đủ cung cấp cho sản phẩm của bạn. Hãy chọn cửa hàng khác!"
                ];
            }
        }

        return ['success' => true, 'usedIngredients' => $totalUsedIngredients];
    }
    public function checkCartPrices() {
        $cart = session('cart', []);
        foreach ($cart as $item) {
            $product = SanPham::where('ma_san_pham', $item['product_id'])->first();
            $size = Sizes::where('ma_size', $item['size_id'])->first();

            $expectedPrice = $product->gia;
            $expectedSizePrice = $size->gia_size;

            if (
                round($item['product_price'], 0) != round($expectedPrice, 0) ||
                round($item['size_price'], 0) != round($expectedSizePrice, 0)
            ) {
                $cartKey = $item['product_id'] . '_' . $item['size_id'];
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
    }
    public function sendEmail($order_id, $name, $email, $phone, $shippingMethod, $paymentMethod, $status, $statusPayment, $address, $cartItems, $tongTien)
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
                $tongTien
            ));
        } catch (\Exception $e) {
            // Ghi log lỗi nếu cần
            \Log::error('Gửi mail thất bại: ' . $e->getMessage());
        }
    }
    public function checkoutStatus(){
        $viewData = [
            'title' => 'Trạng thái thanh toán',    
        ];
        return view('clients.pages.payments.checkout_status', $viewData);
    }
}
