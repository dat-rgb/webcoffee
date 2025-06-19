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
                    <h1>Thông tin đơn hàng của bạn</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Thông báo -->
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
                <div class="cart-header text-left mb-3">
                    <h6 class="mb-0 font-weight-bold">{{ $hoaDon->cuaHang->ten_cua_hang }}</h6>
                    <small class="text-muted"><strong>Địa chỉ: </strong>{{ $hoaDon->cuaHang->dia_chi }}</small><br>
                    <small class="text-muted"><strong>Số điện thoại: </strong>{{ $hoaDon->cuaHang->so_dien_thoai }}</small><br>
                    <small class="text-muted"><strong>Giờ hoạt động: </strong>{{ $hoaDon->cuaHang->gio_mo_cua }} - {{ $hoaDon->cuaHang->gio_dong_cua }}</small>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                        <h3 class="text-danger">Thanh toán thất bại!</h3>
                        <p>Đơn hàng chưa được xử lý do bạn đã hủy hoặc gặp sự cố khi thanh toán.</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1"><strong>Mã hóa đơn:</strong> {{ $hoaDon->ma_hoa_don }}</p>
                        <p class="mb-1"><strong>Trạng thái:</strong>
                            @if($status === 'CANCELLED')
                                <span class="badge badge-danger"><i class="fas fa-ban mr-1"></i> Đã hủy</span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-light rounded p-3 mb-4">
                        <h6 class="font-weight-bold mb-3">Chi tiết thanh toán</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($hoaDon->tam_tinh, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($hoaDon->giam_gia, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí ship:</span>
                            <span>{{ number_format($hoaDon->tien_ship, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2 font-weight-bold text-danger">
                            <span>Tổng tiền:</span>
                            <span>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</span>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" class="btn btn-danger">
                        <i class="fas fa-arrow-left me-1"></i> Quay về trang chủ
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
