<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn bán hàng</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .header h2 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .store-info, .order-info {
            margin-bottom: 10px;
        }
        .order-info p, .store-info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            vertical-align: middle;
        }
        th {
            background: #eee;
        }
        .text-end {
            text-align: right;
        }
        .total {
            font-weight: bold;
            color: red;
        }
        .product-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 3px;
            margin-right: 6px;
        }
        .summary {
            margin-top: 15px;
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            padding: 4px 8px;
            border: none !important; /* Loại bỏ viền */
        }
        .summary .label {
            text-align: right;
            font-weight: bold;
        }
        .summary .total td {
            font-size: 16px;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body style="position: relative;">
<div class="container">
    <!-- Thông tin cửa hàng -->
    @php
        $logoPath = public_path('images/' . ($thongTinWebsite['logo'] ?? 'logo.png'));
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : '';
    @endphp
    <!-- Giao diện -->
    <div class="store-info" style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo" style="height: 40px; max-width: 150px; object-fit: contain;">
        @endif
        <div>
            <small><strong>{{ $order->cuaHang->ten_cua_hang }}</strong></small><br>
            <small>{{ $order->cuaHang->dia_chi }}</small><br>
            <small>{{ $order->cuaHang->gio_mo_cua }} - {{ $order->cuaHang->gio_dong_cua }}</small>
        </div>
    </div>
   <!-- Tiêu đề -->
   <div class="header">
        <h2>HÓA ĐƠN BÁN HÀNG</h2>
    </div>
    <!-- Thông tin đơn hàng -->
    <div class="order-info">
        <p><strong>Mã hóa đơn:</strong> {{ $order->ma_hoa_don }}</p>
        <p><strong>Thời gian đặt hàng:</strong> {{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}</p>
        <p><strong>Khách hàng:</strong> {{ $order->ten_khach_hang }}</p>
        <p><strong>Phương thức nhận hàng: </strong>
            @if($order->phuong_thuc_nhan_hang === 'delivery')
                Giao hàng đến - {{ $order->dia_chi }}
            @elseif($order->phuong_thuc_nhan_hang === 'pickup')
                Nhận tại cửa hàng - {{ $order->dia_chi }}
            @endif
        </p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>SĐT:</strong> {{ $order->so_dien_thoai }}</p>
        <p><strong>Thanh toán:</strong> {{ $order->phuong_thuc_thanh_toan === 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản MB Bank (VietQR)' }}</p>
    </div>
    <!-- Danh sách sản phẩm -->
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
            @foreach($order->chiTietHoaDon as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @php
                        $imagePath = public_path('storage/' . ($item->sanPham->hinh_anh ?? 'default.png'));
                        $imgData = file_exists($imagePath) ? base64_encode(file_get_contents($imagePath)) : '';
                    @endphp
                    @if($imgData)
                        <img src="data:image/jpeg;base64,{{ $imgData }}" class="product-img" />
                    @endif
                    {{ $item->ten_san_pham }} - {{ $item->ten_size }}
                </td>
                <td class="text-end">{{ $item->so_luong }}</td>
                <td class="text-end">{{ number_format($item->don_gia + $item->gia_size, 0, ',', '.') }} đ</td>
                <td class="text-end">{{ number_format($item->thanh_tien, 0, ',', '.') }} đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tổng tiền -->
    <table class="summary">
        <tr>
            <td class="label">Tạm tính:</td>
            <td class="text-end">{{ number_format($order->tam_tinh, 0, ',', '.') }} đ</td>
        </tr>
        <tr>
            <td class="label">Giảm giá:</td>
            <td class="text-end">-{{ number_format($order->giam_gia, 0, ',', '.') }} đ</td>
        </tr>
        <tr>
            <td class="label">Phí ship:</td>
            <td class="text-end">{{ number_format($order->tien_ship, 0, ',', '.') }} đ</td>
        </tr>
        <tr class="total">
            <td class="label">Thành tiền:</td>
            <td class="text-end">{{ number_format($order->tong_tien, 0, ',', '.') }} đ</td>
        </tr>
    </table>
    <!-- Mộc xác nhận -->
    @php
        $statusText = '';
        $statusColor = '';

        switch ($order->trang_thai_thanh_toan) {
            case 0:
                $statusText = 'CHƯA THANH TOÁN';
                $statusColor = '#f44336'; // đỏ
                break;
            case 1:
                $statusText = 'ĐÃ THANH TOÁN';
                $statusColor = '#4caf50'; // xanh lá
                break;
            case 2:
                $statusText = 'ĐANG HOÀN TIỀN';
                $statusColor = '#ff9800'; // cam
                break;
            case 3:
                $statusText = 'ĐÃ HOÀN TIỀN';
                $statusColor = '#2196f3'; // xanh dương
                break;
        }
    @endphp
    @if($statusText)
        <div style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            border: 4px solid {{ $statusColor }};
            color: {{ $statusColor }};
            padding: 20px 40px;
            font-size: 22px;
            font-weight: bold;
            border-radius: 50%;
            text-transform: uppercase;
            opacity: 0.15;
            z-index: 999;
            pointer-events: none;
        ">
            {{ $statusText }}
        </div>
    @endif
</div>
</body>
</html>
