<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class PaymentController extends Controller
{
    //client id: a1b56e20-cd8e-4983-9dec-ce312f04d1a8
    //API Key:  2b21f66c-df9f-410b-b922-d630c0b9a673
    //checksum Key: 87a5d3518ee609401391fa03fce7ac06352db8882ecef2553457a7fcd91e9a67
    //
    //Thục hiện:
    //Add to cart 
    //Checkout
    //
    
    public function payment(Request $request)
    {
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

        
        $customer = Auth::user();
        $khachHang = $customer->khachHang;

        if (!$khachHang) {
            return redirect()->back()->with('error', 'Thông tin khách hàng chưa hợp lệ!');
        }

        $customerId = $khachHang->ma_khach_hang;
        $address = $request->dia_chi .' '. $request->wardName .' '. $request-> districtName .' '. $request->provinceName;
        $storeId = session('selected_store_id');
        if(!$storeId){
            return redirect()->back()->with('error', 'Vui lòng chọn cửa hàng.');
        }

        $storeCheck = $this->checkStore($storeId);

        if (!$storeCheck['success']) {
            return redirect()->back()->with('error', $storeCheck['message'] ?? 'Lỗi không xác định.');
        }

        if ($request->paymentMethod === 'COD') {
            $cart = session('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            if($validated['shippingMethod'] == 'pickup'){
                $address = session('selected_store_name').'-'.session('selected_store_dia_chi');
            }

            $orderData = [
                'ma_cua_hang' => $storeId,
                'ma_khach_hang' => $customerId,
                'ten_khach_hang' => $validated['ho_ten_khach_hang'],
                'so_dien_thoai' => $validated['so_dien_thoai'],
                'email' => $validated['email'],
                'dia_chi' => $address,
                'phuong_thuc_thanh_toan' => $validated['paymentMethod'],
                'phuong_thuc_nhan_hang' => $validated['shippingMethod'],
                'ghi_chu' => $validated['ghi_chu'] ?? '',
                'tien_ship' => 0,
                'khuyen_mai' => 0,
                'giam_gia' => 0,
                'tong_tien' => array_sum(array_column($cart, 'money')),
                'cart_items' => $cart,
            ];

            try {
                $maHoaDon = $this->processCOD($orderData);
               
                $this->sendEmail(
                    $orderData['ten_khach_hang'],
                    $orderData['email'],
                    $orderData['so_dien_thoai'],
                    $orderData['dia_chi'],
                    $orderData['cart_items'],
                    $orderData['tong_tien']
                );
                session()->forget('cart'); // Xóa giỏ hàng sau khi đặt thành công
                return redirect()->route('cart')->with('success', 'Đặt hàng thành công! Mã đơn: ' . $maHoaDon);
            } catch (\Exception $e) {
                // Ghi log nếu cần: Log::error($e->getMessage());
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau!');
            }
        }

        return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }

    public function processCOD(array $data)
    {
        $maHoaDon = $this->generateMaHoaDon();

        DB::table('hoa_dons')->insert([
            'ma_hoa_don' => $maHoaDon,
            'ma_cua_hang' => $data['ma_cua_hang'],
            'ma_khach_hang' => $data['ma_khach_hang'],
            'ten_khach_hang' => $data['ten_khach_hang'],  
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
        if (!$storeId || empty($cart)) {
            return [
                'success' => false,
                'message' => 'Không có thông tin cửa hàng hoặc giỏ hàng rỗng.'
            ];
        }

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
                if (!isset($totalUsedIngredients[$idNL])) {
                    $totalUsedIngredients[$idNL] = 0;
                }
                $totalUsedIngredients[$idNL] += $ingredient->dinh_luong * $quantity;
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
                    'message' => "Nguyên liệu mã $ingredientId không đủ tồn kho để thực hiện thanh toán."
                ];
            }
        }

        // Trừ nguyên liệu
        foreach ($totalUsedIngredients as $ingredientId => $requiredQty) {
            DB::table('cua_hang_nguyen_lieus')
                ->where('ma_cua_hang', $storeId)
                ->where('ma_nguyen_lieu', $ingredientId)
                ->decrement('so_luong_ton', $requiredQty);
        }

        return [
            'success' => true,
            'message' => 'Đủ nguyên liệu và đã trừ tồn kho.'
        ];
    }
    private function sendEmail($name, $email, $phone, $address, $cartItems, $tongTien)
    {
        try {
            Mail::to($email)->send(new OrderMail(
                $name,
                $email,
                $phone,
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

    public function generateMaHoaDon(): string
    {
        do {
            $prefix = 'HD';
            $datetime = now()->format('HisdmY');
            $randomStr = strtoupper(Str::random(8));
            $maHoaDon = $prefix . $datetime . $randomStr;
        } while (DB::table('hoa_dons')->where('ma_hoa_don', $maHoaDon)->exists());

        return $maHoaDon;
    }
}
