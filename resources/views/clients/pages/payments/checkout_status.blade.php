@extends('layouts.app')

@section('title', $title)

@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- single article section -->
<div class="mt-150 mb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                @if(session('status') === 'success')
                    <h2 class="text-success"><i class="fas fa-check-circle"></i> Đặt hàng thành công!</h2>
                    <p>Cảm ơn bạn đã đặt hàng. Đơn hàng đang được xử lý.</p>
                @elseif(session('status') === 'cancel')
                    <h2 class="text-danger"><i class="fas fa-times-circle"></i>  Bạn đã hủy thanh toán.</h2>
                    <p>Đơn hàng đã bị hủy. Nếu cần hỗ trợ vui lòng liên hệ.</p>
                @else
                    <h2>Thông tin thanh toán</h2>
                    <p>Không có thông tin thanh toán hoặc trạng thái không xác định.</p>
                @endif
                <div class="mt-3">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end single article section -->
@endsection
