@extends('layouts.app')

@section('title', $title)

@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>Customer</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- orders section -->
<div class="contact-from-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            @include('clients.customers.sub_layout_customer')
            <div class="col-lg-8">

                @if($orders->isEmpty())
                    <div>
                        <p class="text-center text-muted">Bạn chưa có đơn hàng nào. <a href="{{ route('product') }}">Mua sắm ngay</a></p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Mã hóa đơn</th>
                                    <th scope="col">Sản phẩm</th>
                                    <th scope="col">PT thanh toán</th>
                                    <th scope="col">TT thanh toán</th>
                                    <th scope="col">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle"><strong>#{{ $order->ma_hoa_don }}</strong></td>
                                    <td class="align-middle" style="min-width: 280px;">
                                        @if($order->chiTietHoaDon && $order->chiTietHoaDon->count())
                                            <ul class="list-unstyled mb-0">
                                                @foreach($order->chiTietHoaDon as $item)
                                                    <li class="d-flex align-items-start gap-3 py-2 border-bottom">
                                                        <img src="{{ asset('storage/' . $item->sanPham->hinh_anh) }}" 
                                                            alt="{{ $item->ten_san_pham }}" 
                                                            width="60" height="60">
                                                        <div class="flex-grow-1">
                                                            <div class="fw-semibold text-dark mb-1">{{ $item->ten_san_pham }} <span class="text-muted">- {{ $item->ten_size }}</span></div>
                                                            <div class="small">{{ $item->so_luong }} x {{ number_format($item->don_gia, 0, ',', '.') }}đ</div>
                                                            <div class="small text-muted">Tổng: {{ number_format($item->so_luong * $item->don_gia, 0, ',', '.') }}đ</div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <em class="text-muted">Không có món</em>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($order->phuong_thuc_thanh_toan === 'COD')
                                            <span class="badge bg-secondary text-white">Tiền mặt</span>
                                        @elseif($order->phuong_thuc_thanh_toan === 'NAPAS247')
                                            <span class="badge bg-info text-white">Chuyển khoản</span>
                                        @else
                                            <span class="badge bg-light text-muted">Chưa chọn</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($order->transaction)
                                            @switch($order->transaction->trang_thai)
                                                @case('SUCCESS')
                                                    <span class="badge bg-success">Thành công</span>
                                                    @break
                                                @case('CANCELLED')
                                                    <span class="badge bg-warning text-dark">Đã hủy</span>
                                                    @break
                                                @case('FAILED')
                                                    <span class="badge bg-danger text-white">Thất bại</span>
                                                    @if($order->trang_thai == 1)
                                                        <div><a href="#" class="text-decoration-underline">Thanh toán lại?</a></div>
                                                    @endif
                                                    @break
                                                @case('PENDING')
                                                    <span class="badge bg-info text-white">Đang xử lý</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary text-white">Chưa xử lý</span>
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary text-white">Chưa xử lý</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @switch($order->trang_thai)
                                            @case(0)
                                                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                                @break
                                            @case(1)
                                                <span class="badge bg-primary text-white">Đã xác nhận</span>
                                                @break
                                            @case(2)
                                                <span class="badge bg-info text-dark">Đang chuẩn bị</span>
                                                @break
                                            @case(3)
                                                <span class="badge bg-info text-white">Đang giao</span>
                                                @break
                                            @case(4)
                                                <span class="badge bg-success text-white">Hoàn tất</span>
                                                @break
                                            @case(5)
                                                <span class="badge bg-danger text-white">Đã hủy</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary text-white">Không rõ</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- end orders section -->
@endsection
