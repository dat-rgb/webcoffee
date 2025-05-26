<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng | CDMT Coffee & Tea</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fdfaf6;
            padding: 40px 20px;
            color: #4e342e;
        }
        .container {
            background: #fff8f0;
            padding: 40px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #6d4c41;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #8d6e63;
            margin-bottom: 25px;
        }
        .info {
            margin-bottom: 12px;
            font-size: 16px;
        }
        .info strong {
            color: #5d4037;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        ul li {
            background: #fff;
            border: 1px solid #efebe9;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 8px;
        }
        hr {
            border: none;
            border-top: 1px solid #d7ccc8;
            margin: 30px 0;
        }
        .footer {
            font-size: 14px;
            color: #a1887f;
            margin-top: 30px;
            line-height: 1.6;
        }
        .footer a {
            color: #6d4c41;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Xin chào {{ $name }},</h2>
    <div class="title">Cảm ơn bạn đã đặt hàng tại CDMT Coffee & Tea!</div>
    <div class="info"><strong>Hóa đơn:</strong> {{ $order_id }}</div>
    <div class="info"><strong>Thời gian đặt hàng:</strong> {{ $order_time }}</div>
    <div class="info"><strong>Tên khách hàng:</strong> {{ $name }}</div>
    <div class="info"><strong>Số điện thoại:</strong> {{ $phone }}</div>
    <div class="info"><strong>Email:</strong> {{ $email }}</div>
    @if ($shippingMethod === 'pickup')
        <div class="info"><strong>Nhận tại cửa hàng:</strong> {{ $address }}</div>
    @else
        <div class="info"><strong>Địa chỉ giao hàng:</strong> {{ $address }}</div>
    @endif
    <div class="info"><strong>Hình thức thanh toán:</strong>
        @if ($paymentMethod === 'COD')
            Tiền mặt
        @endif
        @if ($paymentMethod === 'NAPAS247')
            Chuyển khoản
        @endif
    </div>
    <div class="info"><strong>Trạng thái thanh toán:</strong> {{ $statusPayment }}</div>
    <div class="info"><strong>Trạng thái đơn hàng:</strong> {{ $status }}</div>
    <hr>
    <div class="info"><strong>Chi tiết đơn hàng:</strong></div>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px; border: 1px solid #ddd;">#</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Sản phẩm</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Size</th>
                <th style="padding: 10px; border: 1px solid #ddd;">SL</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $item)
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $loop->iteration }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $item['product_name'] }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $item['size_name'] }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $item['product_quantity'] }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {{ number_format($item['product_price'] + $item['size_price'], 0, ',', '.') }} đ
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info"><strong>Tổng thanh toán:</strong> {{ number_format($total, 0, ',', '.') }} đ</div>

    <div class="footer">
        Đây là email tự động. Vui lòng không phản hồi lại email này.<br>
        Nếu bạn cần hỗ trợ, hãy liên hệ với CDMT Coffee & Tea qua
        <a href="https://www.facebook.com/profile.php?viewas=100000686899395&id=61563530795788">fanpage</a>
        hoặc hotline <strong>0901 318 766</strong>.
    </div>
</div>
</body>
</html>
