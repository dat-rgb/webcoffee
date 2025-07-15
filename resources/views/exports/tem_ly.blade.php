<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        margin: 5px;
    }

    .tem {
        margin-bottom: 20px;
        page-break-after: always;
    }

    .header {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: bold;
    }

    .index-total {
        text-align: right;
        width: 50px;
    }

    .product {
        font-size: 13px;
        font-weight: bold;
        margin: 4px 0;
    }

    .line {
        border-top: 1px dashed #000;
        margin: 5px 0;
    }

    .price {
        text-align: right;
        font-size: 13px;
        font-weight: bold;
    }

    .note {
        margin-top: 3px;
        font-style: italic;
        font-size: 11px;
    }
</style>

</head>
<body>
@foreach ($temList as $tem)
<div class="tem">
    <div class="header">
        <span>{{ $tem['ma_hoa_don'] }} - </span>
        <span class="index-total">{{ $tem['index'] }}/{{ $tem['total'] }}</span>
    </div>
    <div class="product"><strong>{{ $tem['ten_san_pham'] }}</strong> - {{ $tem['ten_size'] }}</div>
    <div class="line"></div>
    <div class="price">{{ number_format($tem['gia_size'] + $tem['don_gia'], 0, ',', '.') }} đ</div>
    @if (!empty($tem['ghi_chu']))
        <div class="note">Ghi chú: {{ $tem['ghi_chu'] }}</div>
    @endif
</div>
@endforeach

</body>
</html>
