@extends('layouts.app')
@section('title', $title)
@push('styles')
<style>


</style>

@endpush
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                    <h1>Ưu đãi thành viên</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- orders section -->
<div class="contact-from-section mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 d-lg-none px-3 mb-2">
                <div class="toggle-menu-wrapper text-right">
                    <button class="btn btn-sm"
                            type="button"
                            data-toggle="collapse"
                            data-target="#accountMenu"
                            aria-expanded="false"
                            aria-controls="accountMenu">
                        <i class="fas fa-bars mr-1"></i> Menu
                    </button>
                </div>
            </div>

            @include('clients.customers.sub_layout_customer')
            <div class="col-lg-8">
                @if($orders->isEmpty())
                    <div class="text-center text-muted">
                        <p>Bạn chưa có ưu đãi nào. <a href="{{ route('product') }}">Mua sắm ngay</a></p>
                    </div>
                @else
                <div class="mb-4">
                    <input type="text" id="orderSearch" class="form-control rounded-pill shadow-sm" placeholder="Tìm kiếm theo mã đơn, số điện thoại, địa chỉ, tên sản phẩm...">
                </div>

                <div class="row">
                    @foreach ($orders as $order)
                        <div class="col-md-12 mb-4 order-item"
                            data-mahoa="{{ $order->ma_hoa_don }}"
                            data-sdt="{{ $order->giaoHang->so_dien_thoai ?? '' }}"
                            data-diachi="{{ $order->dia_chi }}"
                            data-sanpham="{{ implode(', ', $order->chiTietHoaDon->pluck('ten_san_pham')->toArray()) }}">

                            <div class="card shadow-sm border rounded-2 border-left-highlight mb-4">
                                <div class="card-body d-flex justify-content-between flex-wrap align-items-center">
                                    {{-- Phần trái --}}
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold text-dark">
                                            <a href="#" data-toggle="modal" data-target="#orderModal-{{ $order->id }}" class="text-decoration-none" style="color: #07212e;">
                                                Mã đơn: {{ $order->ma_hoa_don }}
                                            </a>
                                        </h5>
                                        <div class="small text-muted">Ngày đặt: {{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}</div>

                                        <div class="mt-2">
                                            <strong>Tổng tiền:</strong>
                                            <span class="fw-bold fs-5" style="color: #F28123;">
                                                {{ number_format($order->tong_tien, 0, ',', '.') }}đ
                                            </span>
                                        </div>

                                        {{-- Trạng thái --}}
                                        <div class="mt-2">
                                            <span class="badge badge-status status-{{ $order->trang_thai }}">
                                                {{
                                                    [
                                                        0 => 'Chờ xác nhận',
                                                        1 => 'Đã xác nhận',
                                                        2 => 'Đã hoàn tất',
                                                        3 => $order->phuong_thuc_nhan_hang === 'pickup' ? 'Chờ nhận hàng' : 'Đang giao',
                                                        4 => 'Đã nhận',
                                                        5 => 'Đã hủy'
                                                    ][$order->trang_thai] ?? 'Không rõ'
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Hành động bên phải --}}
                                    <div class="text-right mt-3 mt-md-0" style="min-width: 240px;">
                                        @if($order->trang_thai < 2)
                                            {{-- Nút hủy đơn --}}
                                            <form id="cancelOrderForm" action="{{ route('customer.orders.cancel', $order->ma_hoa_don) }}" method="POST" style="display: none;">
                                                @csrf
                                                <input type="hidden" name="cancel_reason" id="lyDoHuyInput">
                                            </form>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="showCancelPrompt()">
                                                <i class="bi bi-x-circle"></i> Hủy đơn hàng
                                            </button>

                                        @elseif($order->trang_thai == 3)
                                            {{-- Trạng thái giao hàng --}}
                                            @if($order->phuong_thuc_nhan_hang !== 'pickup')
                                                <div class="small">
                                                    <div><strong>Mã vận đơn:</strong> {{ $order->giaoHang->ma_van_don ?? 'Chưa cập nhật' }}</div>
                                                    <div><strong>Shipper:</strong> {{ $order->giaoHang->ho_ten_shipper ?? 'Chưa cập nhật' }}</div>
                                                    <div><strong>SDT:</strong> {{ $order->giaoHang->so_dien_thoai ?? 'Chưa cập nhật' }}</div>
                                                    <div>
                                                        <strong>Trạng thái:</strong> 
                                                        <span class="badge badge-info">
                                                            {{
                                                                [
                                                                    0 => 'Đang giao hàng',
                                                                    1 => 'Giao hàng thành công',
                                                                    2 => 'Giao hàng thất bại'
                                                                ][$order->giaoHang->trang_thai] ?? 'Chưa rõ'
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="small">
                                                    <strong>Nhận tại quầy</strong>
                                                    <p class="mb-0">{{ $order->dia_chi }}</p>
                                                </div>
                                            @endif
                                        @elseif ($order->trang_thai == 4)
                                            @php
                                                $hasPendingReview = false;
                                                foreach ($order->chiTietHoaDon as $item) {
                                                    $exists = \App\Models\Review::where('ma_san_pham', $item->ma_san_pham)
                                                        ->where('ma_hoa_don', $order->ma_hoa_don)
                                                        ->where('ma_khach_hang', Auth::user()->khachHang->ma_khach_hang)
                                                        ->doesntExist();

                                                    if ($exists) {
                                                        $hasPendingReview = true;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            @if ($hasPendingReview)
                                                @foreach ($order->chiTietHoaDon as $item)
                                                    @php
                                                        $hasReviewed = \App\Models\Review::where('ma_san_pham', $item->ma_san_pham)
                                                            ->where('ma_hoa_don', $order->ma_hoa_don)
                                                            ->where('ma_khach_hang', Auth::user()->khachHang->ma_khach_hang)
                                                            ->exists();
                                                    @endphp

                                                    @if (!$hasReviewed)
                                                        <button 
                                                            class="btn btn-warning btn-sm mb-1" 
                                                            onclick="showReviewPrompt('{{ $item->ma_san_pham }}', '{{ $item->ten_san_pham }}', '{{ $order->ma_hoa_don }}')">
                                                            Đánh giá: {{ $item->ten_san_pham }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            @else
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#reviewModal-{{ $order->ma_hoa_don }}">
                                                    Xem đánh giá của bạn
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush