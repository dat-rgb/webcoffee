<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin đơn hàng | CDMT Coffee & Tea</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600&display=swap');

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1rem;
            line-height: 1.8;
            color: #051922;
            background-color: #f5f5f5;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .header {
            background-color: #012738;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .title {
            font-size: 18px;
            color: #F28123;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .info {
            margin-bottom: 12px;
        }

        .info strong {
            color: #012738;
        }

        a.order-link {
            color: #F28123;
            text-decoration: none;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 30px 0;
        }

        .total {
            margin-top: 10px;
            font-weight: bold;
            color: #F28123;
        }

        .footer {
            background-color: #012738;
            color: #ddd;
            font-size: 14px;
            padding: 20px 30px;
            text-align: center;
            line-height: 1.6;
        }

        .footer a {
            color: #F28123;
            text-decoration: none;
            font-weight: bold;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>CDMT Coffee & Tea</h2>
    </div>
    <div class="content">
        <div class="title">Cảm ơn bạn đã đặt hàng tại CDMT Coffee & Tea!</div>
        <div class="info" style="margin-bottom: 16px;">
            <strong>Hóa đơn:</strong> {{ $order_id }}
            <a href="{{ url('/theo-doi-don-hang/' . $order_id . '?token=' . $token) }}"
            style="display: inline-block; margin-left: 10px; padding: 6px 12px;
                    font-size: 14px; color: #fff; background-color: #F28123;
                    border-radius: 4px; text-decoration: none; font-weight: 500;">
                Theo dõi đơn hàng
            </a>
        </div>
        <div class="info"><strong>Thời gian đặt hàng:</strong> {{ $order_time }}</div>
        <div class="info"><strong>Tên khách hàng:</strong> {{ $name }}</div>
        <div class="info"><strong>Số điện thoại:</strong> {{ $phone }}</div>
        <div class="info"><strong>Email:</strong> {{ $email }}</div>
        <div class="info">
            <strong>{{ $shippingMethod === 'pickup' ? 'Nhận tại cửa hàng' : 'Địa chỉ giao hàng' }}:</strong> {{ $address }}
        </div>
        <div class="info"><strong>Hình thức thanh toán:</strong>
            @if ($paymentMethod === 'COD') Tiền mặt @endif
            @if ($paymentMethod === 'NAPAS247') Chuyển khoản @endif
        </div>
        <div class="info"><strong>Trạng thái thanh toán:</strong> {{ $statusPayment }}</div>
        <div class="info"><strong>Trạng thái đơn hàng:</strong> {{ $status }}</div>

        <hr>

        <div class="info"><strong>Chi tiết đơn hàng:</strong></div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>SL</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['product_name'] .' - '.  $item['size_name']?? null}}</td>
                        <td>{{ $item['product_quantity'] }}</td>
                        <td>{{ number_format($item['product_price'] + $item['size_price'], 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="summary" style="margin-top: 30px;">
            <div style="font-size: 16px; margin-bottom: 6px;"><strong>Tạm tính:</strong> {{ number_format($subtotal, 0, ',', '.') }} đ</div>
            <div style="font-size: 16px; margin-bottom: 6px;"><strong>Giảm giá:</strong> -{{ number_format($giamGia, 0, ',', '.') }} đ</div>
            <div style="font-size: 16px; margin-bottom: 6px;"><strong>Phí ship:</strong> {{ number_format($tienShip, 0, ',', '.') }} đ</div>
            <div style="font-size: 18px; margin-top: 10px; font-weight: bold; color: #F28123;">
                Tổng thanh toán: {{ number_format($total, 0, ',', '.') }} đ
            </div>
        </div>
    </div>
    <div class="footer">
        Đây là email tự động. Vui lòng không phản hồi lại email này.<br>
        Cần hỗ trợ? Liên hệ CDMT Coffee & Tea qua
        <a href="https://www.facebook.com/profile.php?viewas=100000686899395&id=61563530795788">fanpage</a>
        hoặc hotline <strong>0901 318 766</strong>.
    </div>
</div>
</body>
</html>
