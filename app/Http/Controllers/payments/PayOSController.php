<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\NguyenLieu;
use App\Models\SanPham;
use App\Models\Sizes;
use App\Models\ThanhPhanSanPham;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class PayOSController extends Controller
{
    public function createPaymentLinkFromOrderData(array $orderData)
    {
        DB::beginTransaction();

        try {
            $maHoaDon = HoaDon::generateMaHoaDon();
            $token_bao_mat = Str::random(32);
            $hoaDon = HoaDon::create([
                'ma_hoa_don' => $maHoaDon,
                'ma_khach_hang' => $orderData['ma_khach_hang'],
                'ma_voucher' => $orderData['ma_voucher'] ?? null,
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
                'token_bao_mat' => $token_bao_mat,
            ]);

            foreach ($orderData['cart_items'] as $item) {
                ChiTietHoaDon::create([
                    'ma_hoa_don' => $maHoaDon,
                    'ma_san_pham' => $item['product_id'],
                    'ten_san_pham' => $item['product_name'],
                    'ma_size' => $item['size_id'],
                    'ten_size' => $item['size_name'],
                    'gia_size' => $item['size_price'],
                    'so_luong' => $item['product_quantity'],
                    'don_gia' => $item['product_price'],
                    'thanh_tien' => $item['money'],
                    'ghi_chu' => null,
                ]);
            }

            preg_match_all('!\d+!', $maHoaDon, $matches);
            $orderCodeNum = $matches ? (int)implode('', $matches[0]) : time();

            $paymentData = [
                'orderCode' => $orderCodeNum,
                'amount' => (int)$orderData['tong_tien'],
                'expiredAt' => now()->addMinutes(1)->timestamp,
                'description' => 'Thanh toán đơn hàng',
                'returnUrl' => config('app.url') . "/payment/thanh-cong",
                'cancelUrl' => config('app.url') . "/payment/that-bai",
                
            ];

            $response = $this->payOS->createPaymentLink($paymentData);

            Transactions::create([
                'ma_hoa_don' => $maHoaDon,
                'tong_tien' => $hoaDon->tong_tien,
                'ten_khach_hang' => $hoaDon->ten_khach_hang,
                'email' => $hoaDon->email,
                'so_dien_thoai' => $hoaDon->so_dien_thoai,
                'dia_chi' => $hoaDon->dia_chi,
                'items_json' => json_encode($orderData['cart_items']),
                'payment_link' => $response['checkoutUrl'] ?? null,
                'trang_thai' => 'PENDING',
            ]);

            DB::commit();
            session()->forget('cart');

            return redirect($response['checkoutUrl']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->handleException($th);
        }
    }

    public function getPaymentLinkInformation(string|int $orderCode)
    {
        try {
            $response = $this->payOS->getPaymentLinkInformation($orderCode);

            return response()->json([
                'error' => 0,
                'message' => 'Success',
                'data' => $response['data'] ?? $response,
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }

    public function cancelPaymentLink(Request $request, string|int $orderCode)
    {
        $reason = $request->input('cancellationReason') ?? 'Khách hàng hủy đơn';

        try {
            $response = $this->payOS->cancelPaymentLink($orderCode, [
                'cancellationReason' => $reason
            ]);

            return response()->json([
                'error' => 0,
                'message' => 'Đã hủy link thanh toán',
                'data' => $response['data'] ?? $response,
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }

    public function confirmWebhook(Request $request)
    {
        $webhookUrl = config('app.url') . '/webhook/payos';

        try {
            $response = $this->payOS->confirmWebhook($webhookUrl);

            return response()->json([
                'error' => 0,
                'message' => 'Webhook xác nhận thành công',
                'data' => $response
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }

    public function handleWebhook(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 1, 'message' => 'Invalid JSON'], 400);
        }

        $code = $body['code'] ?? null;
        $success = $body['success'] ?? false;
        $data = $body['data'] ?? [];
        $signature = $body['signature'] ?? '';

        if (!$this->verifyPayOSWebhookData($data, $signature)) {
            return response()->json(['error' => 1, 'message' => 'Sai chữ ký'], 403);
        }

        try {
            $hoaDon = null;

            if ($success && $code == '00') {
                $orderCode = $data['orderCode'] ?? null;
                $maHoaDon = 'HD' . $orderCode;

                $hoaDon = HoaDon::with('chiTietHoaDon')->where('ma_hoa_don', $maHoaDon)->first();

                if (!$hoaDon) {
                    return response()->json(['error' => 1, 'message' => 'Không tìm thấy hóa đơn'], 404);
                }

                $hoaDon->update([
                    'trang_thai' => 0,
                    'trang_thai_thanh_toan' => 1,
                ]);

                Transactions::where('ma_hoa_don', $maHoaDon)->update([
                    'trang_thai' => 'success',
                    'counter_account_bank_id' => $data['counterAccountBankId'] ?? null,
                    'counter_account_bank_name' => $data['counterAccountBankName'] ?? null,
                    'counter_account_name' => $data['counterAccountName'] ?? null,
                    'counter_account_number' => $data['counterAccountNumber'] ?? null,
                    'virtual_account_name' => $data['virtualAccountName'] ?? null,
                    'virtual_account_number' => $data['virtualAccountNumber'] ?? null,
                ]);

                $cartItems = $hoaDon->chiTietHoaDon->map(function ($item) {
                    return [
                        'product_name'     => $item->ten_san_pham ?? 'N/A',
                        'size_name'        => $item->ten_size ?? '',
                        'product_quantity' => $item->so_luong,
                        'product_price'    => $item->don_gia,
                        'size_price'       => $item->gia_size ?? 0,
                    ];
                })->toArray();

                $statusPayment = 'Đã thanh toán';
                $status = 'Chờ xác nhận';

                app(PaymentController::class)->sendEmail(
                    $hoaDon->ma_hoa_don,
                    $hoaDon->ten_khach_hang,
                    $hoaDon->email,
                    $hoaDon->so_dien_thoai,
                    $hoaDon->phuong_thuc_nhan_hang,
                    $hoaDon->phuong_thuc_thanh_toan,
                    $status,
                    $statusPayment,
                    $hoaDon->dia_chi,
                    $cartItems,
                    $hoaDon->tam_tinh,
                    $hoaDon->giam_gia,
                    $hoaDon->tien_ship,
                    $hoaDon->tong_tien,
                    $hoaDon->token_bao_mat,
                );
            }

            if ($hoaDon) {
                event(new \App\Events\OrderCreated($hoaDon));
            }

            return response()->json(['success' => true]);

        } catch (\Throwable $th) {
            \Log::error('Webhook PayOS xử lý lỗi:', [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);

            return response()->json(['error' => 1, 'message' => 'Lỗi xử lý'], 500);
        }
    }

    protected function verifyPayOSWebhookData(array $data, string $signature): bool
    {
        ksort($data);

        $dataStrArr = [];
        foreach ($data as $key => $value) {
            $value = (in_array($value, ["undefined", "null"]) || is_null($value)) ? "" : $value;
            $dataStrArr[] = $key . "=" . $value;
        }

        $dataString = implode("&", $dataStrArr);
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        $expectedSignature = hash_hmac('sha256', $dataString, $checksumKey);

        return hash_equals($expectedSignature, $signature);
    }

    public function paymentSuccess(Request $request)
    {
        $orderCode = $request->query('orderCode');
        $status = $request->query('status');
        $maHoaDon = 'HD' . $orderCode;
      
        $hoaDon = HoaDon::with('chiTietHoaDon', 'transaction', 'cuaHang')->where('ma_hoa_don', $maHoaDon)->first();
        $transaction = Transactions::where('ma_hoa_don', $maHoaDon)->first();

    
        $viewData = [
            'title' => 'Đặt hàng thành công',
            'hoaDon' => $hoaDon,
            'status' => $status,
            'error' => !$hoaDon ? 'Không tìm thấy hóa đơn.' : null
        ];
        //event(new \App\Events\OrderCreated($hoaDon));
        return view('clients.pages.payments.thanh-cong', $viewData);
    }
    public function paymentCancel(Request $request)
    {
        $orderCode = $request->query('orderCode');
        $status = $request->query('status');
        $maHoaDon = 'HD' . $orderCode;

        $hoaDon = HoaDon::with('chiTietHoaDon')->where('ma_hoa_don', $maHoaDon)->first();

        if ($hoaDon) {
            $hoaDon->update([
                'trang_thai_thanh_toan' => 0, 
                'trang_thai' => 5, 
            ]);

            Transactions::where('ma_hoa_don', $maHoaDon)->update([
                'trang_thai' => 'CANCELLED',
            ]);
            
            $chiTietHoaDons = ChiTietHoaDon::where('ma_hoa_don', $maHoaDon)->get();

            foreach ($chiTietHoaDons as $chiTiet) {
                $maSanPham = $chiTiet->ma_san_pham;

                // Lấy loại sản phẩm
                $sanPham = SanPham::where('ma_san_pham', $maSanPham)->first();
                if (!$sanPham) continue;

                if ($sanPham->loai_san_pham == 0) {
                    // Sản phẩm pha chế (có size, có định lượng)
                    $maSize = Sizes::where('ten_size', $chiTiet->ten_size)->value('ma_size');
                    if (!$maSize) continue;

                    $thanhPhanNLs = ThanhPhanSanPham::where('ma_san_pham', $maSanPham)
                        ->where('ma_size', $maSize)
                        ->get();

                    foreach ($thanhPhanNLs as $tp) {
                        $soLuongHoanTra = $tp->dinh_luong * $chiTiet->so_luong;

                        DB::table('cua_hang_nguyen_lieus')
                            ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                            ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                            ->increment('so_luong_ton', $soLuongHoanTra);
                    }

                } elseif ($sanPham->loai_san_pham == 1) {
                    // Sản phẩm đóng gói (1 nguyên liệu, không size)

                    $tp = ThanhPhanSanPham::where('ma_san_pham', $maSanPham)->first();
                    if (!$tp) continue;

                    // Lấy định lượng từ bảng nguyen_lieus (sản phẩm đóng gói lấy full số lượng nguyên liệu)
                    $nguyenLieu = NguyenLieu::where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)->first();
                    if (!$nguyenLieu) continue;

                    $soLuongHoanTra = $nguyenLieu->so_luong * $chiTiet->so_luong;

                    DB::table('cua_hang_nguyen_lieus')
                        ->where('ma_cua_hang', $hoaDon->ma_cua_hang)
                        ->where('ma_nguyen_lieu', $tp->ma_nguyen_lieu)
                        ->increment('so_luong_ton', $soLuongHoanTra);
                }
            }

            if ($hoaDon->ma_voucher) {
                $voucher = KhuyenMai::where('ma_voucher', $hoaDon->ma_voucher)->first();
                if ($voucher) {
                    $voucher->increment('so_luong', 1);
                }
            }
        }

        $viewData = [
            'title' => 'Đặt hàng không thành công',
            'hoaDon' => $hoaDon,
            'status' => $status,
            'error' => !$hoaDon ? 'Không tìm thấy hóa đơn.' : null
        ];

        return view('clients.pages.payments.that-bai', $viewData);
    }
}
