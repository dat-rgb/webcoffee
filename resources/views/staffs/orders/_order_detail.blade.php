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
    <div style="max-width: 700px; margin: 0 auto; font-family: Arial, sans-serif; line-height: 1.5; display: flex; gap: 30px; flex-wrap: wrap;">
    
    <!-- Cột trái -->
    <div style="flex: 1 1 300px;">
        <div style="margin-bottom: 12px;">
            <strong>Mã Hóa Đơn:</strong> <span>{{ $order->ma_hoa_don }}</span>
        </div>
        <div style="margin-bottom: 12px;">
            <strong>Cửa hàng:</strong> <span>{{ $order->ma_cua_hang }}</span>
        </div>
        <div style="margin-bottom: 12px;">
            <strong>Nhân viên:</strong> <span>{{ $order->ma_nhan_vien ?? '' }}</span>
        </div>
        <div style="margin-bottom: 12px;">
            <strong>Ngày lập:</strong> <span>{{ $order->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div style="margin-bottom: 12px;">
            <strong>Số điện thoại:</strong> <span>{{ $order->so_dien_thoai }}</span>
        </div>
        <div style="margin-bottom: 12px;">
            <strong>Email:</strong> <span>{{ $order->email }}</span>
        </div>
    </div>

    <!-- Cột phải -->
    <div style="flex: 1 1 300px;">
        @if($order->ma_khach_hang)
        <div style="margin-bottom: 6px;">
            <strong>Khách hàng:</strong> <span>{{ $order->ten_khach_hang }}</span>
        </div>
        <div style="margin-bottom: 12px; font-size: 0.9em; color: #555; display: flex; gap: 15px;">
            <span>Điểm hiện tại: {{ $order->khachHang->diem_thanh_vien }}</span>
            <span>Hạng: {{ $order->khachHang->hang_thanh_vien }}</span>
        </div>
        @else
        <div style="margin-bottom: 12px;">
            <strong>Khách hàng:</strong> <span>Guest - {{ $order->ten_khach_hang }}</span>
        </div>
        @endif

        <div style="margin-bottom: 12px;">
        <strong>Phương thức thanh toán:</strong>
        <span>
            @if ($order->phuong_thuc_thanh_toan === "COD")
            Thanh toán khi nhận hàng (COD)
            @elseif ($order->phuong_thuc_thanh_toan === "NAPAS247")
            Chuyển khoản
            @endif
        </span>
        </div>

        <div style="margin-bottom: 12px;">
        <strong>{{ $order->phuong_thuc_nhan_hang === "pickup" ? 'Phương thức nhận hàng:' : 'Giao hàng đến:' }}</strong>
        <span>{{ $order->dia_chi }}</span>
        </div>

        <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
            <strong>Trạng thái đơn hàng:</strong>
            @php
                $statusColors = [
                0 => '#ff9800', 1 => '#2196f3', 2 => '#4caf50',
                3 => '#03a9f4', 4 => '#2e7d32', 5 => '#f44336',
                'default' => '#9e9e9e',
                ];
                $statusTexts = [
                0 => 'Chờ xác nhận', 1 => 'Đã xác nhận', 2 => 'Hoàn tất đơn hàng',
                3 => $order->phuong_thuc_nhan_hang === 'pickup' ? 'Chờ nhận hàng' : 'Đang giao', 4 => 'Đã nhận', 5 => 'Đã hủy',
                ];
                $st = $order->trang_thai;
                $color = $statusColors[$st] ?? $statusColors['default'];
                $text = $statusTexts[$st] ?? 'Không xác định';
            @endphp
            <span style="padding: 6px 14px; border-radius: 20px; background-color: {{ $color }}; color: white; font-weight: 600;">
                {{ $text }}
            </span>
        </div>
        @if ($order->trang_thai === 3 && $order->phuong_thuc_nhan_hang !== 'pickup')
            <div style="margin-bottom: 6px;">
                <strong>Shipper: </strong> <span>{{ $order->giaoHang->ho_ten_shipper ?? 'Chưa có' }}</span>
            </div>
            <div style="margin-bottom: 12px; font-size: 0.9em; color: #555; display: flex; gap: 15px;">
                <span>Số điện thoại: {{ $order->giaoHang->so_dien_thoai ?? 'Chưa có' }}</span>
                <span>Trạng thái: 
                    @if ( $order->giaoHang->trang_thai === 0)
                        Đang giao hàng    
                    @elseif ( $order->giaoHang->trang_thai === 1)       
                        Giao hàng thàng công
                    @elseif ( $order->giaoHang->trang_thai === 2)   
                        Giao hàng không thành công
                    @endif
                </span>
            </div>
        @elseif($order->trang_thai === 5)
            @php
                $huy = $order->lichSuHuyDonHang->first();
            @endphp

            @if ($order->trang_thai === 5 && $huy)
                <div style="margin-bottom: 6px;">
                    <strong>Người hủy: </strong> <span>
                        @if ($huy->ma_khach_hang === null)
                            {{ $huy->ma_nhan_vien ? 'Nhân viên ' . $huy->ma_nhan_vien : 'Không xác định' }}
                        @else
                            Khách hàng
                        @endif
                    </span>
                </div>
                <div style="margin-bottom: 12px; font-size: 0.9em; color: #555; display: flex; gap: 15px;">
                    <span>Lý do hủy: {{ $huy->ly_do_huy }}</span>
                </div>
            @endif
        @endif
    </div>
    </div>
    <h6 class="mt-4 mb-3"><strong>Chi tiết đơn hàng:</strong></h6>
    <div style="overflow-x:auto;">
        <table class="table table-striped align-middle" style="min-width: 600px;">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Sản phẩm</th>
                    <th style="width: 80px;" class="text-center">Số lượng</th>
                    <th style="width: 120px;" class="text-end">Đơn giá</th>
                    <th style="width: 140px;" class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->chiTietHoaDon as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/' . ($item->sanPham->hinh_anh ?? 'default.png')) }}" alt="Ảnh" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                            <div>
                                <div>{{ $item->ten_san_pham }} - {{ $item->ten_size }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">{{ $item->so_luong }}</td>
                    <td class="text-end">{{ number_format($item->don_gia + $item->gia_size, 0, ',', '.') }} đ</td>
                    <td class="text-end">{{ number_format($item->thanh_tien, 0, ',', '.') }} đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="order-summary mt-3" style="max-width: 320px; margin-left: auto; font-size: 1rem;">
        <p class="d-flex justify-content-between"><strong>Tạm tính:</strong> <span>{{ number_format($order->tong_tien - $order->giamn_gia, 0, ',', '.') }} đ</span></p>
        <p class="d-flex justify-content-between"><strong>Giảm giá:</strong> <span>- {{ number_format($order->giam_gia, 0, ',', '.') }} đ</span></p>
        <p class="d-flex justify-content-between"><strong>Phí ship:</strong> <span>{{ number_format($order->tien_ship, 0, ',', '.') }} đ</span></p>
        <hr>
        <p class="d-flex justify-content-between fw-bold fs-5 text-danger"><strong>Thành tiền:</strong> <span>{{ number_format($order->tong_tien, 0, ',', '.') }} đ</span></p>
    </div>
</div>
