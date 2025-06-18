@extends('layouts.app')
@section('title',$title)
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Kết quả thanh toán</p>
                    <h1>Cảm ơn bạn đã đặt hàng!</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Nội dung kết quả thanh toán -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            @if (isset($error))
                <div class="alert alert-danger p-4 rounded shadow-sm">
                    <h4 class="mb-2"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Thất bại</h4>
                    <p>{{ $error }}</p>
                    <a href="{{ route('home') }}" class="btn btn-outline-danger mt-3">
                        <i class="fas fa-home me-1"></i> Quay về trang chủ
                    </a>
                </div>
            @else
                <div class="card border-0 shadow-lg p-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h3 class="text-success">Thanh toán thành công!</h3>
                            <p>Đơn hàng của bạn đã được ghi nhận và đang được xử lý.</p>
                        </div>
                        <ul class="list-group text-start mb-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Mã hóa đơn:</strong>
                                <span>{{ $hoaDon->ma_hoa_don }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Trạng thái:</strong>
                             
                                <span>{{ $status }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Tổng tiền:</strong>
                                <span>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }}đ</span>
                            </li>
                        </ul>
                        <a href="{{ route('home') }}" class="btn btn-success">
                            <i class="fas fa-home me-1"></i> Quay về trang chủ
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
