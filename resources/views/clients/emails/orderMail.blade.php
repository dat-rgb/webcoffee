<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng | CDMT Coffee & Tea</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
            color: #333;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Xin chào {{ $name }},</h2>
    <div class="title">Cảm ơn bạn đã đặt hàng tại CDMT Coffee & Tea!</div>
    <div class="info"><strong>Tên khách hàng:</strong> {{ $name }}</div>
    <div class="info"><strong>Email:</strong> {{ $email }}</div>
    <div class="info"><strong>Số điện thoại:</strong> {{ $phone }}</div>
    <div class="info"><strong>Địa chỉ giao hàng:</strong> {{ $address }}</div>
    <div class="info"><strong>Thời gian đặt hàng:</strong> {{ $order_time }}</div>
    
    <hr>

    <div class="info"><strong>Chi tiết đơn hàng:</strong></div>
    <ul>
        @foreach($cart as $item)
            <li>{{ $item['product_name'] }} - Size {{ $item['size_name'] }} - SL: {{ $item['product_quantity'] }} - Giá: {{ number_format($item['product_price'] + $item['size_price'], 0, ',', '.') }} đ</li>
        @endforeach
    </ul>

    <div class="info"><strong>Tổng thanh toán:</strong> {{ number_format($total, 0, ',', '.') }}đ</div>

    <div class="footer">
        Đây là email tự động. Vui lòng không phản hồi lại email này.<br>
        Nếu bạn có bất kỳ thắc mắc nào, hãy liên hệ với CDMT Coffee & Tea qua <a href="https://www.facebook.com/profile.php?viewas=100000686899395&id=61563530795788">fanpage</a> hoặc hotline 0901318766.
    </div>
</div>
</body>
</html>
