<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 5px;
        }
        .title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .item {
            margin-bottom: 6px;
        }
        .item p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="title">TEM LY - {{ $order->ma_hoa_don }}</div>

    @foreach ($order->chiTietHoaDon as $item)
        <div class="item">
            <p><strong>{{ $item->ten_san_pham }}</strong> - {{ $item->ten_size }}</p>
            <p>Số lượng: {{ $item->so_luong }}</p>
        </div>
    @endforeach

    <p>Khách: {{ $order->ten_khach_hang }}</p>
</body>
</html>
