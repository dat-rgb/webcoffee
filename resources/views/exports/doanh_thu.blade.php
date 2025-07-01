<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Doanh thu</title>
    <style>
        @page { size: A4 landscape; margin: 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #000; }
        h2 { text-align: center; text-transform: uppercase; margin-bottom: 10px; }

        .store-info { margin-bottom: 10px; }
        .store-info table { width: 100%; }
        .store-info td { padding: 4px 8px; }

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

<h2>PHIẾU KIỂM KHO NGUYÊN LIỆU</h2>

<table class="main-table">
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã NL</th>
            <th>Nguyên liệu</th>
            <th>SL Tồn Tổng</th>
            <th>Đơn vị tính</th>
            <th>Lô hàng</th>
            <th>SL Tồn thực tế</th>
            <th>Tình trạng</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nguyenLieuList as $index => $nl)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $nl->ma_nguyen_lieu }}</td>
                <td>{{ $nl->ten_nguyen_lieu }}<br>
                    <small>Định lượng: {{ $nl->so_luong_goc ?? 0 }} {{ $nl->don_vi }}</small>
                </td>
                <td class="text-center">{{ number_format($nl->tong_ton, 0, ',', '.') }}</td>
                <td class="text-center">{{ $nl->don_vi_tinh ?? '-' }}</td>
                <td>
                    @if(!empty($nl->lo_hang))
                        @foreach($nl->lo_hang as $lo)
                            @if($lo['ton_lo'] > 0)
                                • {{ $lo['so_lo'] ?? '-' }} - {{ number_format($lo['ton_lo'], 0, ',', '.') }} {{ $nl->don_vi_tinh }} - {{ \Carbon\Carbon::parse($lo['han_su_dung'])->format('d/m/Y') }}<br>
                            @endif
                        @endforeach
                    @else
                        Không có lô hàng
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Ghi chú -->
<div class="footer-note">
    
    Ghi chú: Phiếu kiểm kho chỉ dùng để đối chiếu tồn thực tế và cập nhật dữ liệu khi có sai lệch.
</div>

<!-- Chữ ký -->
<table class="signature-section">
    <tr>
        <td><strong>Người lập phiếu</strong><br>(Ký và ghi rõ họ tên)</td>
        <td><strong>Người kiểm kho</strong><br>(Ký và ghi rõ họ tên)</td>
    </tr>
</table>

</body>
</html>
