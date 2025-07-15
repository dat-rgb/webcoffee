<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 2px 0; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="center bold">{{ $order->cuaHang->ten_cua_hang }}</div>
    <div class="center">{{ $order->cuaHang->dia_chi }}</div>
    <div class="line"></div>
    <div>Mã HĐ: {{ $order->ma_hoa_don }}</div>
    <div>Ngày: {{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('H:i d/m/Y') }}</div>
    <div>Khách: {{ $order->ten_khach_hang }}</div>
    <div>SĐT: {{ $order->so_dien_thoai }}</div>
    <div class="line"></div>

    <table>
        @foreach ($order->chiTietHoaDon as $item)
        <tr>
            <td colspan="2">{{ $item->ten_san_pham }}</td>
        </tr>
        <tr>
            <td>{{ $item->so_luong }} x {{ number_format($item->don_gia + $item->gia_size) }}</td>
            <td class="right">{{ number_format($item->thanh_tien) }}đ</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>
    <div class="right">Tạm tính: {{ number_format($order->tam_tinh) }}đ</div>
    <div class="right">Giảm giá: -{{ number_format($order->giam_gia) }}đ</div>
    <div class="right">Ship: {{ number_format($order->tien_ship) }}đ</div>
    <div class="right bold">Tổng: {{ number_format($order->tong_tien) }}đ</div>
    <div class="line"></div>
    <div class="center">Cảm ơn quý khách!</div>
</body>
</html>
