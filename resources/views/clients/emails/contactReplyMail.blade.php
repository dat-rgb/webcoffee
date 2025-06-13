<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phản hồi từ CDMT Coffee & Tea</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #fdfaf6;
            padding: 40px 20px;
            color: #4e342e;
        }

        .container {
            max-width: 680px;
            background: #fff;
            padding: 35px;
            border-radius: 10px;
            margin: auto;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        }

        h2 {
            color: #6d4c41;
            margin-bottom: 10px;
        }

        .info {
            font-size: 15px;
            margin-bottom: 15px;
        }

        .reply-content {
            background: #fef4ec;
            border-left: 4px solid #d7ccc8;
            padding: 15px 20px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 15px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #9e9e9e;
            line-height: 1.6;
        }

        .footer a {
            color: #6d4c41;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xin chào {{ $contact_name }},</h2>
        <div class="info">
            Cảm ơn bạn đã liên hệ với <strong>CDMT Coffee & Tea</strong>. Chúng tôi đã nhận được phản hồi của bạn và xin gửi lại lời phản hồi như sau:
        </div>
        <div class="reply-content">
            {!! nl2br(e($reply_message)) !!}
        </div>
        <div class="footer">
            Nếu bạn cần hỗ trợ thêm, hãy liên hệ qua
            <a href="https://www.facebook.com/profile.php?viewas=100000686899395&id=61563530795788">fanpage CDMT Coffee & Tea</a>
            hoặc gọi <strong>0901 318 766</strong>.<br>
            Trân trọng,<br>
            <strong>CDMT Team</strong>
        </div>
    </div>
</body>
</html>
