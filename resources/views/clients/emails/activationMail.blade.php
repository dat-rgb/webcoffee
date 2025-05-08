<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích hoạt tài khoản</title>
</head>
<body>
    <h1>Xin chào! {{ $taiKhoan->name }}</h1>
    <p>Cảm ơn bạn đã đăng ký tài khoản tại wensite của chúng tôi. Để kích hoạt tài khoản, vui lòng nhấn vào liên kết dưới đây:</p>
    <a href="{{ url('activate/' . $token) }}" style="padding:10px; background-color: green; color:white;">Kích hoạt tài khoản</a>

    <p>Trân trọng,</p>
    <p>Đội ngũ hỗ trợ khách hàng.</p>
</body>
</html>