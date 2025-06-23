<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu Yêu Cầu Nhập Nguyên Liệu</title>
    <style>
        @page { size: A4 landscape; margin: 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #000; }
        h2 { text-align: center; text-transform: uppercase; margin-bottom: 10px; }

        .store-info {
            margin-bottom: 10px;
        }

        .store-info table {
            width: 100%;
        }

        .store-info td {
            padding: 4px 8px;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.main-table th, table.main-table td {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: middle;
        }

        table.main-table th {
            background: #f0f0f0;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .footer-note {
            margin-top: 20px;
            font-style: italic;
        }

        .signature-section {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        .signature-section td {
            padding: 30px;
        }
    </style>
</head>
<body>
<!-- Thông tin cửa hàng -->
<div class="store-info">
    <table>
        <tr>
            <td>
                <strong>Mã phiếu:</strong> {{ $maPhieu ?? '---' }}<br>
                <strong>CỬA HÀNG:</strong> {{ $cuaHang->ten_cua_hang ?? '---' }}<br>
                <strong>Địa chỉ:</strong> {{ $cuaHang->dia_chi ?? '---' }}<br>
                <strong>Ngày lập phiếu:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
                <strong>Người lập:</strong> {{ $nguoiLap ?? '---' }}<br>
            </td>
        </tr>
    </table>
</div>

<h2>PHIẾU YÊU CẦU NHẬP NGUYÊN LIỆU</h2>

<table class="main-table">
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã NL</th>
            <th>Tên nguyên liệu + Định lượng</th>
            <th>Giá (VNĐ)</th>
            <th>SL Dự kiến</th>
            <th>SL Thực tế</th>
            <th>Đơn vị tính</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nguyenLieuNhap as $index => $nl)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $nl->ma_nguyen_lieu }}</td>
                <td>{{ $nl->ten_nguyen_lieu }} - {{ $nl->so_luong ?? 0 }} ({{ $nl->don_vi ?? '---' }})</td>
                <td class="text-right">{{ number_format($nl->gia, 0, ',', '.') }}</td>
                <td class="text-center">{{ $nl->so_luong_du_kien ?? 0 }}</td>
                <td class="text-center"></td>
                <td class="text-center">{{ $nl->don_vi_tinh ?? '---' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tr>
        <td colspan="6" class="text-right"><strong>Tổng tiền dự kiến:</strong></td>
        <td class="text-right">{{ number_format($tongTien ?? 0, 0, ',', '.') }} đ</td>
    </tr>
</table>

<!-- Ghi chú -->
<div class="footer-note">
    <strong>Lý do nhập:</strong><br>
    ..............................................................................................<br>
    ..............................................................................................<br>
    Ghi chú: Phiếu này được in để nhân viên kiểm tra và nhập kho. Sau khi hoàn tất, phiếu sẽ được lưu trữ lại tại cửa hàng.
</div>

<!-- Chữ ký -->
<table class="signature-section">
    <tr>
        <td><strong>Người lập phiếu</strong><br>(Ký và ghi rõ họ tên)</td>
        <td><strong>Người ghi nhận</strong><br>(Ký và ghi rõ họ tên)</td>
    </tr>
</table>

</body>
</html>
