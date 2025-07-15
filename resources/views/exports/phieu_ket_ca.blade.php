<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PHIẾU KẾT CA</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0 5px;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .title {
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .section {
            margin: 5px 0;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .label {
            display: inline-block;
            width: 50%;
        }

        .value {
            display: inline-block;
            width: 48%;
            text-align: right;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="center title">PHIẾU KẾT CA</div>

    <div class="section">
        <div><span class="label">NV:</span> <span class="value">{{ $nhanVien->ho_ten_nhan_vien }}</span></div>
        <div><span class="label">Mã NV:</span> <span class="value">{{ $nhanVien->ma_nhan_vien }}</span></div>
        <div><span class="label">Vào ca:</span> <span class="value">{{ $ca->thoi_gian_vao->format('H:i d/m/Y') }}</span></div>
        <div><span class="label">Ra ca:</span> <span class="value">{{ $ca->thoi_gian_ra->format('H:i d/m/Y') }}</span></div>
    </div>

    <div class="line"></div>

    <div class="section">
        <div><span class="label">Tổng hóa đơn:</span> <span class="value">{{ $ca->tong_don_xac_nhan }} đơn</span></div>
        <div><span class="label">Tiền đầu ca:</span> <span class="value">{{ number_format($ca->tien_dau_ca, 0, ',', '.') }}đ</span></div>
        <div><span class="label">Tiền COD:</span> <span class="value">{{ number_format($ca->tong_tien_cod, 0, ',', '.') }}đ</span></div>
        <div><span class="label">Tiền Online:</span> <span class="value">{{ number_format($ca->tong_tien_online, 0, ',', '.') }}đ</span></div>
        <div><span class="label"><strong>Tổng tiền:</strong></span> <span class="value"><strong>{{ number_format($ca->tong_tien, 0, ',', '.') }}đ</strong></span></div>
    </div>

    <div class="line"></div>

    <div class="section">
        <div><span class="label">Tiền nhận:</span> <span class="value">{{ number_format($ca->tien_thuc_nhan, 0, ',', '.') }}đ</span></div>
        @php
            $chenh = $ca->tien_chenh_lech;
            $sign = $chenh < 0 ? '-' : '+';
        @endphp
        <div><span class="label">Chênh lệch:</span> 
            <span class="value">{{ $sign }}{{ number_format(abs($chenh), 0, ',', '.') }}đ</span>
        </div>
    </div>

    @if ($ca->ghi_chu)
        <div class="line"></div>
        <div class="section">
            <div><span class="label">Ghi chú:</span> <span class="value">{{ $ca->ghi_chu }}</span></div>
        </div>
    @endif

    <div class="line"></div>

    <div class="footer">
        <p>Ca làm việc kết thúc lúc {{ $ca->thoi_gian_ra->format('H:i d/m/Y') }}</p>
        <p>Cảm ơn và hẹn gặp lại!</p>
    </div>

</body>
</html>
