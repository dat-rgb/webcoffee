<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu Yêu Cầu Xuất Nguyên Liệu</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
        h2 { text-align: center; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 6px; }
        th { background: #f0f0f0; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
<h2>Phiếu Yêu Cầu Xuất Nguyên Liệu</h2>
<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã NL</th>
            <th>Nguyên liệu</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Đơn vị tính</th>
        </tr>
    </thead>
    <tbody>
    @foreach($nguyenLieuXuat as $index => $nl)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $nl->ma_nguyen_lieu }}</td>
            <td>{{ $nl->ten_nguyen_lieu }} - {{ $nl->so_luong }} ({{ $nl->don_vi }})</td>
            <td>{{ number_format($nl->gia, 0, ',', '.') }}</td>
            <td>{{ $nl->so_luong }}</td>
            <td>{{ $nl->don_vi }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>