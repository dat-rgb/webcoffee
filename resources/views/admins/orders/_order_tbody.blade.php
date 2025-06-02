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
            <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}"></td>
            <td>
                <a href="javascript:void(0);" 
                    class="order-detail-btn" 
                    data-id="{{ $order->ma_hoa_don }}" 
                    data-bs-toggle="tooltip" 
                    title="Xem chi tiết">
                    {{ $order->ma_hoa_don }}
                </a>
            </td>
            <td>{{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}</td>
            <td>{{ optional($order->khachHang)->ho_ten_khach_hang ?: "Guest - " . $order->ten_khach_hang }}</td>
            <td>{{ number_format($order->tong_tien,0,',','.') }} đ</td>
            <td>
                @if( $order->phuong_thuc_thanh_toan === "COD")
                    Tiền mặt
                @elseif ($order->phuong_thuc_thanh_toan === "NAPAS247")
                    Chuyển khoảng
                @endif
            </td>
            <td>{{ $order->trang_thai_thanh_toan == 0 ? 'Chờ thanh toán' : 'Đã thanh toán' }}</td>
            <td>
                @switch($order->trang_thai)
                    @case(0) Chờ xác nhận @break
                    @case(1) Đã xác nhận @break
                    @case(2) Hoàn tất đơn hàng @break
                    @case(3) Đang giao @break
                    @case(4) Đã nhận @break
                    @case(5) Đã hủy @break
                    @default Không xác định
                @endswitch
            </td>
        </tr>
    @endforeach
@endif  