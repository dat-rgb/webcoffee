@if($orders->isEmpty())
    <tr>
        <td colspan="9" class="text-center">
            Không có đơn hàng
            @if(request()->pt_thanh_toan || request()->tt_thanh_toan || request()->trang_thai)
                theo bộ lọc:
                <ul style="list-style:none; padding-left:0;">
                    @if(request()->pt_thanh_toan)
                        <li>PT. thanh toán: 
                            <strong>
                                @if( request()->pt_thanh_toan == "COD")
                                    Tiền mặt
                                @elseif (request()->pt_thanh_toan == "NAPAS247")
                                    Chuyển khoảng
                                @endif
                            </strong></li>
                    @endif
                    @if(request()->tt_thanh_toan !== null && request()->tt_thanh_toan !== '')
                        <li>TT. thanh toán: <strong>
                            @if(request()->tt_thanh_toan == 0) Chờ thanh toán
                            @elseif(request()->tt_thanh_toan == 1) Đã thanh toán
                            @endif
                        </strong></li>
                    @endif
                    @if(request()->trang_thai !== null && request()->trang_thai !== '')
                        <li>Trạng thái: <strong>
                            @switch(request()->trang_thai)
                                @case(0) Chờ xác nhận @break
                                @case(1) Đã xác nhận @break
                                @case(2) Hoàn tất đơn hàng @break
                                @case(3) Đang giao @break
                                @case(4) Đã nhận @break
                                @case(5) Đã hủy @break
                                @default Không xác định
                            @endswitch
                        </strong></li>
                    @endif
                </ul>
            @endif
        </td>
    </tr>
@else
    @foreach($orders as $order)
        <tr>
            <td class="text-start">{{ $loop->iteration }}</td>
            <td class="text-start">
                <a href="javascript:void(0);" 
                    class="order-detail-btn" 
                    data-id="{{ $order->ma_hoa_don }}" 
                    data-bs-toggle="tooltip" 
                    title="Xem chi tiết">
                    {{ $order->ma_hoa_don }}
                </a>
            </td>
            <td class="text-start">{{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}</td>
            <td class="text-start" style="min-width: 180px; white-space: nowrap;">
                <div>{{ optional($order->khachHang)->ho_ten_khach_hang ?: "Guest - " . $order->ten_khach_hang }}</div>
                <div style="font-size: 0.85em; color: #555;">{{ $order->so_dien_thoai }}</div>
                <div style="font-size: 0.85em; color: #555;">{{ $order->email }}</div>
            </td>
            <td class="text-start">
                @if( $order->phuong_thuc_thanh_toan === "COD")
                    Thanh toán khi nhận hàng (COD)
                @elseif ($order->phuong_thuc_thanh_toan === "NAPAS247")
                    Chuyển khoảng
                @endif
            </td>
            <td class="text-start">
                @php
                    $statuses = [
                        0 => 'Chờ xác nhận',
                        1 => 'Đã xác nhận',
                        2 => 'Hoàn tất đơn hàng',
                        3 => 'Đang giao',
                        4 => 'Đã nhận',
                        5 => 'Đã hủy',
                    ];
                @endphp

                @if ($order->trang_thai < 4 && isset($statuses[$order->trang_thai]))
                    <select name="order_status" class="form-select order-status-select" data-order-id="{{ $order->ma_hoa_don }}">
                        @foreach ($statuses as $key => $label)
                            @if ($key >= $order->trang_thai)
                                <option value="{{ $key }}" {{ $order->trang_thai == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                @else
                    <span class="{{ $order->trang_thai == 4 ? 'text-success fw-bold' : ($order->trang_thai == 5 ? 'text-danger fw-bold' : '') }}">
                        {{ $statuses[$order->trang_thai] ?? 'Không xác định' }}
                    </span>
                @endif
            </td>
        </tr>
    @endforeach
@endif  