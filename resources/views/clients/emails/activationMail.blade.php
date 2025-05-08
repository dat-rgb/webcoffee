<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích hoạt tài khoản</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
        }
        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
        }
        a.btn:hover {
            background-color: #218838;
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
        <h1>Xin chào, {{ $taiKhoan->name }}.</h1>
        <p>Cảm ơn bạn đã đăng ký tài khoản tại website của chúng tôi.</p>
        <p>Để kích hoạt tài khoản, vui lòng nhấn vào nút bên dưới:</p>
        <a href="{{ url('activate/' . $token) }}" class="btn">Kích hoạt tài khoản</a>

        <div class="footer">
            <p>Trân trọng,</p>
            <p>Đội ngũ hỗ trợ khách hàng.</p>
        </div>
    </div>
</body>
</html>
