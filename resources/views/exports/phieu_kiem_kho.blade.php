
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu Kiểm Kho</title>
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
<h2>Phiếu Kiểm Kho Cuối Tuần</h2>
<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã NL</th>
            <th>Tên + Định lượng</th>
            <th>Đơn vị</th>
            <th>Lô hàng</th>
            <th>SL tồn</th>
            <th>HSD</th>
            <th>SL Thực tế</th>
            <th>Tình trạng</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
    @foreach($nguyenLieuKiemKho as $index => $nl)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $nl->ma_nguyen_lieu }}</td>
            <td>{{ $nl->ten_nguyen_lieu }} - {{ $nl->so_luong }} ({{ $nl->don_vi }})</td>
            <td>{{ $nl->don_vi }}</td>
            <td>{{ $nl->so_lo ?? '---' }}</td>
            <td>{{ $nl->so_luong_ton }}</td>
            <td>{{ $nl->han_su_dung ?? '---' }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>