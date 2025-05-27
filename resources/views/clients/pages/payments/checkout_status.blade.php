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
                    <h2 class="text-success">🎉 Thanh toán thành công!</h2>
                    <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được xử lý.</p>
                @elseif(session('status') === 'cancel')
                    <h2 class="text-danger">❌ Bạn đã hủy thanh toán.</h2>
                    <p>Nếu cần hỗ trợ vui lòng liên hệ với chúng tôi.</p>
                @else
                    <h2>Thông tin thanh toán</h2>
                    <p>Không có thông tin thanh toán hoặc trạng thái không xác định.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- end single article section -->
@endsection
