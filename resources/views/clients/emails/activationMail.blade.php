<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích hoạt tài khoản | CDMT Coffee & Tea</title>
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

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: #218838;
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
        <div class="title">Kích hoạt tài khoản của bạn</div>
        <p>Xin chào, <strong>{{ $name }}</strong>,</p>
        <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>CDMT Coffee & Tea</strong>.</p>
        <p>Vui lòng nhấn vào nút bên dưới để kích hoạt tài khoản của bạn:</p>
        <a href="{{ url('activate/' . $token) }}" class="btn">Kích hoạt tài khoản</a>
        <p style="margin-top: 30px;">Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
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
