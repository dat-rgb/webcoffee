<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PayOS\PayOS;

class Napas247Controller extends Controller
{
    protected $payOS;

    public function __construct()
    {
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
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
            'ma_cua_hang' => $orderData['ma_cua_hang'],
            'ten_khach_hang' => $orderData['ten_khach_hang'],
            'so_dien_thoai' => $orderData['so_dien_thoai'],
            'email' => $orderData['email'],
            'dia_chi' => $orderData['dia_chi'],
            'phuong_thuc_thanh_toan' => $orderData['phuong_thuc_thanh_toan'],
            'phuong_thuc_nhan_hang' => $orderData['phuong_thuc_nhan_hang'],
            'ghi_chu' => $orderData['ghi_chu'],
            'tien_ship' => $orderData['tien_ship'],
            'khuyen_mai' => $orderData['khuyen_mai'],
            'giam_gia' => $orderData['giam_gia'],
            'tong_tien' => $orderData['tong_tien'],
            'trang_thai_thanh_toan' => 1,
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
            'payment_link' => null, // sẽ cập nhật sau khi có response
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
                'expiredAt' => now()->addMinutes(30)->timestamp,
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
            return $this->payOS->getPaymentLinkInformation($orderCode);
        } catch (\Exception $e) {
            \Log::error('PayOS getPaymentLinkInformation Error: ' . $e->getMessage());
            return null;
        }
    }

    public function checkPaymentStatus($orderCode)
    {
        $paymentInfo = $this->getPaymentLinkInformation($orderCode);

        if (!$paymentInfo) {
            return response()->json(['error' => 'Không lấy được thông tin thanh toán'], 404);
        }

        // Ví dụ check trạng thái đã thanh toán
        if ($paymentInfo['status'] === 'PAID') {
            // update database đơn hàng trạng thái thanh toán thành công
            // ...

            return response()->json(['message' => 'Thanh toán thành công', 'data' => $paymentInfo]);
        }

        return response()->json(['message' => 'Thanh toán chưa hoàn thành', 'data' => $paymentInfo]);
    }

    /**
     * Hủy link thanh toán
     * @param int|string $orderCode
     * @param string $reason
     * @return array|null
     */
    public function cancelPaymentLink($orderCode, $reason = 'User canceled')
    {
        try {
            return $this->payOS->cancelPaymentLink($orderCode, $reason);
        } catch (\Exception $e) {
            \Log::error('PayOS cancelPaymentLink Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Xác thực URL webhook
     * @param string $url
     * @return string|null
     */
    public function confirmWebhook($url)
    {
        try {
            return $this->payOS->confirmWebhook($url);
        } catch (\Exception $e) {
            \Log::error('PayOS confirmWebhook Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Xác minh dữ liệu webhook thanh toán
     * @param array $data
     * @return array|null
     */
    public function verifyPaymentWebhookData(array $data)
    {
        try {
            return $this->payOS->verifyPaymentWebhookData($data);
        } catch (\Exception $e) {
            \Log::error('PayOS verifyPaymentWebhookData Error: ' . $e->getMessage());
            return null;
        }
    }

    // Các method xử lý callback, trả về từ PayOS
    public function handleReturn(Request $request)
    {
        // TODO: Xác thực dữ liệu callback, cập nhật trạng thái đơn hàng

        return redirect()->route('home')->with('success', 'Thanh toán thành công!');
    }

    public function handleCancel(Request $request)
    {
        return redirect()->route('cart')->with('error', 'Bạn đã hủy thanh toán.');
    }
}
