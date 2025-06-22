<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phản hồi từ CDMT Coffee & Tea</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600&display=swap');

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #f5f5f5;
            padding: 40px 20px;
            color: #051922;
            margin: 0;
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

        .reply-content {
            background-color: #fef4ec;
            border-left: 4px solid #F28123;
            padding: 18px 20px;
            border-radius: 8px;
            line-height: 1.7;
            font-size: 15px;
            color: #333;
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
        <div class="title">Phản hồi từ CDMT</div>
        <p>Xin chào <strong>{{ $contact_name }}</strong>,</p>
        <p>Cảm ơn bạn đã liên hệ với <strong>CDMT Coffee & Tea</strong>. Chúng tôi đã tiếp nhận thông tin và phản hồi của bạn như sau:</p>
        <div class="reply-content">
            {!! nl2br(e($reply_message)) !!}
        </div>
        <p style="margin-top: 30px;">Nếu bạn có thêm câu hỏi, đừng ngần ngại liên hệ lại với chúng tôi nhé!</p>
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
