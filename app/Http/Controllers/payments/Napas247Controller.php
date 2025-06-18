<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\CuaHangNguyenLieu;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PayOS\PayOS;

class Napas247Controller extends Controller
{
    protected $payOS;
    protected $webhookUrl;

    public function __construct()
    {
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );

        $this->webhookUrl = env('WEBHOOK_URL');
    }
    /**
     * Tạo link thanh toán cho đơn hàng
     * @param array $orderData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPaymentLink(array $orderData)
    {
        $maHoaDon = HoaDon::generateMaHoaDon();
        // Tạo hóa đơn
        $hoaDon = HoaDon::create([
            'ma_hoa_don' => $maHoaDon,
            'ma_khach_hang' => $orderData['ma_khach_hang'],
            'ma_voucher' => !empty($orderData['ma_voucher']) ? $orderData['ma_voucher'] : null,
            'ma_cua_hang' => $orderData['ma_cua_hang'],
            'ten_khach_hang' => $orderData['ten_khach_hang'],
            'so_dien_thoai' => $orderData['so_dien_thoai'],
            'email' => $orderData['email'],
            'dia_chi' => $orderData['dia_chi'],
            'phuong_thuc_thanh_toan' => $orderData['phuong_thuc_thanh_toan'],
            'phuong_thuc_nhan_hang' => $orderData['phuong_thuc_nhan_hang'],
            'tam_tinh' => $orderData['tam_tinh'] ?? 0,
            'tien_ship' => $orderData['tien_ship'] ?? 0,
            'khuyen_mai' => $orderData['khuyen_mai'] ?? 0,
            'giam_gia' => $orderData['giam_gia'] ?? 0,
            'tong_tien' => $orderData['tong_tien'],
            'trang_thai_thanh_toan' => 0,
            'trang_thai' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        //Tạo transaction
        Transactions::create([
            'ma_hoa_don' => $maHoaDon,
            'tong_tien' => $hoaDon->tong_tien,
            'ten_khach_hang' => $hoaDon->ten_khach_hang,
            'email' => $hoaDon->email,
            'so_dien_thoai' => $hoaDon->so_dien_thoai,
            'dia_chi' => $hoaDon->dia_chi,
            'items_json' => json_encode($orderData['cart_items']),
            'payment_link' => null, 
            'trang_thai' => 'PENDING', 
        ]);

        // Tạo chi tiết hóa đơn
        foreach ($orderData['cart_items'] ?? [] as $item) {
            ChiTietHoaDon::create([
                'ma_hoa_don' => $maHoaDon,
                'ma_san_pham' => $item['product_id'],
                'ten_san_pham' => $item['product_name'],
                'ten_size' => $item['size_name'],
                'gia_size' => $item['size_price'],
                'so_luong' => $item['product_quantity'],
                'don_gia' => $item['product_price'],
                'thanh_tien' => $item['money'],
                'ghi_chu' => null,
            ]);
        }

        // Kiểm tra cart_items có hợp lệ không
        if (empty($orderData['cart_items']) || !is_array($orderData['cart_items'])) {
            toastr()->error('Danh sách sản phẩm không hợp lệ hoặc trống');
            return redirect()->back();
        }

        // Format items cho PayOS
        $items = array_map(function ($item) {
            return [
                'name' => $item['product_name'] . ' - ' . $item['size_name'],
                'quantity' => (int)$item['product_quantity'],
                'price' => (int)$item['product_price'] + (int)$item['size_price'],
            ];
        }, $orderData['cart_items']);

        $items = array_values($items); // Ép thành indexed array 

        // Lấy số nguyên từ ma_hoa_don làm orderCode
        preg_match_all('!\d+!', $maHoaDon, $matches);

        $orderCodeNum = $matches ? (int)implode('', $matches[0]) : time();

        try {
            $response = $this->payOS->createPaymentLink([
                'orderCode' => $orderCodeNum,
                'amount' => (int) round($hoaDon->tong_tien),
                'description' => substr("Thanh toán HD", 0, 25),
                'returnUrl' => route('payos.return'),
                'cancelUrl' => route('payos.cancel'),
                'buyerName' => $hoaDon->ten_khach_hang,
                'buyerEmail' => $hoaDon->email,
                'buyerPhone' => $hoaDon->so_dien_thoai,
                'buyerAddress' => $hoaDon->dia_chi,
                'items' => $items,
                'expiredAt' => now()->addMinutes(1)->timestamp,
            ]);

            Transactions::where('ma_hoa_don', $maHoaDon)->update([
                'payment_link' => $response['checkoutUrl'],
            ]);

            session()->forget('cart');

            return redirect()->away($response['checkoutUrl']);

        } catch (\Exception $e) {
            toastr()->error('Tạo yêu cầu thanh toán thất bại: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    /**
     * Lấy thông tin link thanh toán
     * @param int|string $orderCode
     * @return array|null
     */
    public function getPaymentLinkInformation($orderCode)
    {
        try {
            $info = $this->payOS->getPaymentLinkInformation($orderCode);

            if (!isset($info['status']) || !isset($info['checkoutUrl'])) {
                \Log::warning("Thiếu dữ liệu trong getPaymentLinkInformation cho orderCode: $orderCode", $info);
            }

            return $info;
        } catch (\Exception $e) {
            \Log::error('PayOS getPaymentLinkInformation Error (orderCode: ' . $orderCode . '): ' . $e->getMessage());
            return null;
        }
    }
    public function checkPaymentStatus($orderCode)
    {
        $paymentInfo = $this->getPaymentLinkInformation($orderCode);

        if (!$paymentInfo) {
            return response()->json([
                'error' => 'Không lấy được thông tin thanh toán'
            ], 404);
        }

        switch ($paymentInfo['status']) {
            case 'PAID':
                $this->updatePaymentSuccess($orderCode);
                return response()->json([
                    'message' => 'Thanh toán thành công',
                    'data' => $paymentInfo
                ]);

            case 'CANCELLED':
            case 'TIMEOUT':
                $this->updatePaymentCancel($orderCode);
                return response()->json([
                    'message' => 'Thanh toán đã bị hủy',
                    'data' => $paymentInfo
                ]);

            default:
                return response()->json([
                    'message' => 'Thanh toán chưa hoàn thành',
                    'data' => $paymentInfo
                ]);
        }
    }
    // Các method xử lý callback, trả về từ PayOS
    public function handleReturn(Request $request)
    {
        $orderCode = $request->input('orderCode');

        if ($orderCode) {
            $paymentInfo = $this->getPaymentLinkInformation(orderCode: $orderCode);
            if ($paymentInfo && $paymentInfo['status'] === 'PAID') {
                $this->updatePaymentSuccess($orderCode);
            }
        }

        return redirect()->route('checkout_status')->with('status', 'success');
    }
    protected function updatePaymentSuccess($orderCode)
    {
        $maHoaDon = 'HD' . $orderCode;
        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
        $transaction = Transactions::where('ma_hoa_don', $maHoaDon)->first();
       

        if ($hoaDon && $hoaDon->trang_thai_thanh_toan != 1) {
            $hoaDon->trang_thai_thanh_toan = 1; // Đã thanh toán
            $hoaDon->save();

            $cartItems = json_decode($transaction->items_json ?? '[]', true);
            
            $sendEmail = new PaymentController();
            $statusPayment = 'Đã thanh toán';
            $status = 'Chờ xác nhận';

            $sendEmail->sendEmail(
                $hoaDon->ma_hoa_don,
                $hoaDon->ten_khach_hang,
                $hoaDon->email,
                $hoaDon->so_dien_thoai,
                $hoaDon->phuong_thuc_nhan_hang,
                $hoaDon->phuong_thuc_thanh_toan,
                $hoaDon->$status,
                $hoaDon->$statusPayment,
                $hoaDon->dia_chi,
                $cartItems,
                $hoaDon->tong_tien
            );
        }

        if ($transaction && $transaction->trang_thai != 'SUCCESS') {
            $transaction->trang_thai = 'SUCCESS';
            $transaction->save();
        }
    }
    public function handleCancel(Request $request)
    {
        $orderCode = $request->input('orderCode');

        if ($orderCode) {
            $paymentInfo = $this->getPaymentLinkInformation(orderCode: $orderCode);
            if ($paymentInfo && $paymentInfo['status'] === 'CANCELLED') {
                $this->updatePaymentCancel($orderCode);
            }
        }

        return redirect()->route('checkout_status')->with('status', 'cancel');
    }
    protected function updatePaymentCancel($orderCode)
    {
        $maHoaDon = 'HD' . $orderCode;

        $hoaDon = HoaDon::where('ma_hoa_don', $maHoaDon)->first();
        if (!$hoaDon) return;

        if ($hoaDon->ma_voucher) {
            $voucher = KhuyenMai::where('ma_voucher', $hoaDon->ma_voucher)->first();
            if ($voucher) {
                $voucher->increment('so_luong', 1);
            }
        }
        
        // Update trạng thái hóa đơn
        $hoaDon->trang_thai_thanh_toan = 0; // Chờ thanh toán
        $hoaDon->trang_thai = 5; // Đã hủy
        $hoaDon->save();

        Transactions::where('ma_hoa_don', $maHoaDon)->update([
            'trang_thai' => 'CANCELLED'
        ]);

        // Lấy chi tiết hóa đơn
        $chiTietHoaDons = ChiTietHoaDon::where('ma_hoa_don', $maHoaDon)->get();

        foreach ($chiTietHoaDons as $chiTiet) {
            $maSanPham = $chiTiet->ma_san_pham;

            $maSize = Sizes::where('ten_size', $chiTiet->ten_size)->value('ma_size');
            if (!$maSize) {
                // Size không tồn tại, bỏ qua
                continue;
            }

            $thanhPhanNLs = ThanhPhanSanPham::where('ma_san_pham', $maSanPham)
                ->where('ma_size', $maSize)
                ->get();

            foreach ($thanhPhanNLs as $tp) {
                $soLuongHoanTra = $tp->dinh_luong * $chiTiet->so_luong;

                // Cập nhật tồn kho bằng query builder vì composite key
                DB::table('cua_hang_nguyen_lieus')
                    ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                    ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                    ->increment('so_luong_ton', $soLuongHoanTra);
            }
        }
    }
}
