<style>
    .order-details {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        font-family: 'Segoe UI', sans-serif;
    }

    .order-details p {
        margin: 4px 0;
    }

    .order-details table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }

    .order-details th,
    .order-details td {
        padding: 10px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .order-details thead {
        background-color: #e9ecef;
    }

    .order-details td img {
        margin-right: 8px;
        vertical-align: middle;
    }

    .text-end {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .order-summary {
        width: 300px;
        float: right;
        margin-top: 20px;
        padding: 15px;
        font-size: 15px;
    }

    .order-summary p {
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
    }

    .order-summary p strong {
        font-weight: 600;
    }

    .status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: bold;
        color: white;
    }

    .status-0 { background-color: #ffc107; }
    .status-1 { background-color: #17a2b8; }
    .status-2 { background-color: #28a745; }
    .status-3 { background-color: #007bff; }
    .status-4 { background-color: #6f42c1; }
    .status-5 { background-color: #dc3545; }
    .status-default { background-color: #6c757d; }
</style>

<div class="order-details">
    <div style="max-width: 700px; margin: 0 auto; display: flex; gap: 30px; flex-wrap: wrap;">
        {{-- Left column --}}
        <div style="flex: 1 1 300px;">
            <p><strong>Mã Hóa Đơn:</strong> {{ $order->ma_hoa_don }}</p>
            <p><strong>Cửa hàng:</strong> {{ $order->ma_cua_hang }}</p>
            <p><strong>Nhân viên:</strong> {{ $order->ma_nhan_vien ?? '' }}</p>
            <p><strong>Ngày lập:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>SĐT:</strong> {{ $order->so_dien_thoai }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
        </div>

        {{-- Right column --}}
        <div style="flex: 1 1 300px;">
            <p><strong>Khách hàng:</strong>
                {{ $order->ma_khach_hang ? $order->ten_khach_hang : 'Guest - ' . $order->ten_khach_hang }}
            </p>
            @if($order->ma_khach_hang)
                <p>Điểm: {{ $order->khachHang->diem_thanh_vien }} | Hạng: {{ $order->khachHang->hang_thanh_vien }}</p>
            @endif
            <p><strong>PT thanh toán:</strong>
                {{ $order->phuong_thuc_thanh_toan === 'COD' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' }}
            </p>
            <p><strong>{{ $order->phuong_thuc_nhan_hang === 'pickup' ? 'Nhận hàng tại quầy' : 'Giao hàng đến' }}:</strong>
                {{ $order->dia_chi }}
            </p>

            @php
                $statusTexts = [0 => 'Chờ xác nhận', 1 => 'Đã xác nhận', 2 => 'Hoàn tất', 3 => 'Đang giao', 4 => 'Đã nhận', 5 => 'Đã hủy'];
                $colorClasses = [0 => 'status-0', 1 => 'status-1', 2 => 'status-2', 3 => 'status-3', 4 => 'status-4', 5 => 'status-5'];
                $st = $order->trang_thai;
            @endphp

            <p><strong>Trạng thái:</strong>
                <span class="status {{ $colorClasses[$st] ?? 'status-default' }}">{{ $statusTexts[$st] ?? 'Không xác định' }}</span>
            </p>

            @if ($order->trang_thai === 3)
                <p><strong>Shipper:</strong> {{ $order->giaoHang->ho_ten_shipper ?? 'Chưa có' }}</p>
                <p><strong>SĐT:</strong> {{ $order->giaoHang->so_dien_thoai ?? 'Chưa có' }}</p>
                <p><strong>Trạng thái:</strong>
                    @if($order->giaoHang->trang_thai === 0)
                        Đang giao hàng
                    @elseif($order->giaoHang->trang_thai === 1)
                        Giao thành công
                    @else
                        Không thành công
                    @endif
                </p>
            @endif

            @if ($order->trang_thai === 5 && $order->lichSuHuyDonHang->first())
                @php $huy = $order->lichSuHuyDonHang->first(); @endphp
                <p><strong>Người hủy:</strong>
                    {{ $huy->ma_khach_hang === null ? ($huy->ma_nhan_vien ? 'NV ' . $huy->ma_nhan_vien : 'Không xác định') : 'Khách hàng' }}
                </p>
                <p><strong>Lý do:</strong> {{ $huy->ly_do_huy }}</p>
            @endif
        </div>
    </div>

    {{-- Chi tiết sản phẩm --}}
    <h6 class="mt-4 mb-3"><strong>Chi tiết đơn hàng:</strong></h6>
    <div style="overflow-x:auto;">
        <table class="table table-striped align-middle" style="min-width: 600px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->chiTietDonHang as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->ten_san_pham }}</td>
                        <td class="text-center">{{ $item->so_luong }}</td>
                        <td class="text-end">{{ number_format($item->don_gia, 0, ',', '.') }}₫</td>
                        <td class="text-end">{{ number_format($item->don_gia * $item->so_luong, 0, ',', '.') }}₫</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
