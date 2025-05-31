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
    .status-0 { background-color: #ffc107; }  /* Chờ xác nhận - vàng */
    .status-1 { background-color: #17a2b8; }  /* Đã xác nhận - xanh dương nhạt */
    .status-2 { background-color: #28a745; }  /* Hoàn tất - xanh lá */
    .status-3 { background-color: #007bff; }  /* Đang giao - xanh dương */
    .status-4 { background-color: #6f42c1; }  /* Đã nhận - tím */
    .status-5 { background-color: #dc3545; }  /* Đã hủy - đỏ */
    .status-default { background-color: #6c757d; } /* Không xác định - xám */
</style>

<div class="order-details">
    <p><strong>Mã Hóa Đơn:</strong> {{ $order->ma_hoa_don }}</p>
    <p><strong>Cửa hàng:</strong> {{ $order->ma_cua_hang }}</p>
    <p><strong>Ngày lập:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>   
    @if($order->ma_khach_hang)
        <p><strong>Khách hàng:</strong> {{ $order->ten_khach_hang }}</p>
        <p><strong>Điểm hiện tại:</strong> {{ $order->khachHang->diem_thanh_vien }} - <strong>Hạng:</strong> {{$order->khachHang->hang_thanh_vien }}</p>
    @else   
        <p><strong>Khách hàng:</strong> {{ 'Guest - ' . $order->ten_khach_hang }}</p>
    @endif
    <p><strong>Số điện thoại:</strong> {{ $order->so_dien_thoai }}</p>
    <p><strong>Email:</strong> {{ $order->email }}</p>
    <p><strong>Phương thức thanh toán:</strong>
        @if ($order->phuong_thuc_thanh_toan === "COD")
            Tiền mặt
        @elseif ($order->phuong_thuc_thanh_toan === "NAPAS247")
            Chuyển khoản
        @endif
    </p>
    @if($order->phuong_thuc_nhan_hang === "pickup")
        <p><strong>Phương thức nhận hàng:</strong>
        {{ $order->dia_chi }}
        </p>
    @else 
        <p><strong>Giao hàng đến:</strong>
        {{ $order->dia_chi }}
        </p>
    @endif
    <p><strong>Trạng thái đơn hàng:</strong>
        @switch($order->trang_thai)
            @case(0)
                <span class="status status-0">Chờ xác nhận</span>
                @break
            @case(1)
                <span class="status status-1">Đã xác nhận</span>
                @break
            @case(2)
                <span class="status status-2">Hoàn tất đơn hàng</span>
                @break
            @case(3)
                <span class="status status-3">Đang giao</span>
                <p><strong>Thông tin giao hàng:</strong></p>
                <p>Người nhận: {{ $order->giaoHang->ho_ten_ ?? 'Chưa có' }}</p>
                <p>Số điện thoại: {{ $order->giaoHang->so_dien_thoai ?? 'Chưa có' }}</p>
                <p>Địa chỉ: {{ $order->giaoHang->dia_chi ?? 'Chưa có' }}</p>
                @break
            @case(4)
                <span class="status status-4">Đã nhận</span>
                @break
            @case(5)
                <span class="status status-5">Đã hủy</span>
                @break
            @default
                <span class="status status-default">Không xác định</span>
        @endswitch
    </p>
    <h6 class="mt-4"><strong>Chi tiết sản phẩm:</strong></h6>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">Sản phẩm</th>
                <th>Số lượng</th>
                <th class="text-end">Đơn giá</th>
                <th class="text-end">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->chiTietHoaDon as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">
                        <img src="{{ asset('storage/' . ($item->sanPham->hinh_anh ?? 'default.png')) }}" alt="Ảnh" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        {{ $item->ten_san_pham }} - {{ $item->ten_size }}
                    </td>
                    <td class="text-center">{{ $item->so_luong }}</td>
                    <td class="text-end">{{ number_format($item->don_gia + $item->gia_size, 0, ',', '.') }}đ</td>
                    <td class="text-end">{{ number_format($item->thanh_tien, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="order-summary">
        <p><strong>Tạm tính:</strong> <span>{{ number_format($order->tong_tien - $order->tien_ship - $order->giamn_gia,0,',','.') }} đ</span></p>
        <p><strong>Giảm giá:</strong> <span>{{ number_format($order->giam_gia,0,',','.') }} đ</span></p>
        <p><strong>Phí ship:</strong> <span>{{ number_format($order->tien_ship,0,',','.') }} đ</span></p>
        <p><strong>Thành tiền:</strong> <span class="text-danger fw-bold">{{ number_format($order->tong_tien,0,',','.') }} đ</span></p>
    </div>
</div>
