@extends('layouts.app')
@section('title',$title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpus
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                    <h1>Check Out Product</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
<div class="checkout-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-accordion-wrap">
                    <div class="accordion" id="accordionExample">
                        <div class="card single-accordion">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Thông tin khách hàng
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="billing-address-form">
                                    <form action="#">
                                        <p>
                                            <input type="text" placeholder="Họ và tên" 
                                                value="{{ $khach_hang->ho_ten_khach_hang ?? '' }}">
                                        </p>
                                        <p>
                                            <input type="email" placeholder="Email" 
                                                value="{{ $email ?? '' }}">
                                        </p>
                                        <p>
                                            <input type="text" placeholder="Địa chỉ" 
                                                value="{{ $khach_hang->dia_chi ?? '' }}">
                                        </p>
                                        <p>
                                            <input type="tel" placeholder="Số điện thoại" 
                                                value="{{ $khach_hang->so_dien_thoai ?? '' }}">
                                        </p>
                                        <p>
                                            <textarea name="bill" id="bill" cols="30" rows="10" placeholder="Ghi chú"></textarea>
                                        </p>
                                    </form>

                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="card single-accordion">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Địa chỉ giao hàng
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="shipping-address-form">
                                    <div class="d-flex flex-column flex-lg-row gap-3">
                                        <div class="flex-grow-1">
                                        <label for="provinceSelect" class="form-label fw-bold">Tỉnh/Thành phố</label>
                                        <select class="form-select" name="province" id="provinceSelect" required>
                                            <option value="" selected>Chọn tỉnh/thành phố</option>
                                            <!-- options -->
                                        </select>
                                        </div>
                                        <div class="flex-grow-1">
                                        <label for="districtSelect" class="form-label fw-bold">Quận/Huyện</label>
                                        <select class="form-select" name="district" id="districtSelect" disabled required>
                                            <option value="" selected>Chọn quận/huyện</option>
                                        </select>
                                        </div>
                                        <div class="flex-grow-1">
                                        <label for="wardSelect" class="form-label fw-bold">Xã/Phường</label>
                                        <select class="form-select" name="ward" id="wardSelect" disabled required>
                                            <option value="" selected>Chọn xã/phường</option>
                                        </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="provinceName" name="provinceName">
                                    <input type="hidden" id="districtName" name="districtName">
                                    <input type="hidden" id="wardName" name="wardName">
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="card single-accordion">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Phương thức thanh toán
                                </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="card-details">
                                        <div class="justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodCOD" value="COD" required>
                                                <label class="form-check-label" for="paymentMethodCOD">
                                                    <img src="{{ asset('images/cod.webp') }}" alt="COD" class="payment-image">
                                                    Thanh toán khi nhận hàng (COD)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodVNPAY" value="VNPAY" required>
                                                <label class="form-check-label" for="paymentMethodVNPAY">
                                                    <img src="{{ asset('images/vnpay.webp') }}" alt="VNPAY" class="payment-image">
                                                    Thanh toán trực tuyến VNPAY
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-details-wrap">
                    <table class="order-details">
                        <thead>
                            <tr>
                                <th>Chi tiết đơn hàng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="order-details-body">
                        @foreach ($cart as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="product-img">
                                            <img src="{{ $item['product_image'] ? asset('storage/' . $item['product_image']) : asset('images/no_product_image.png') }}" style="width: 50px; height: auto;">
                                        </div>
                                        <div class="product-info">
                                            <span>{{ $item['product_name'] }} </span><br>
                                            <span>{{ $item['size_name'] }} x {{ $item['product_quantity'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ number_format($item['money'],0,',','.') }} đ
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tbody class="checkout-details">
                            <tr>
                                <td>Tạm tính: ({{ count(session('cart')) }} món)</td>
                                <td>
                                    {{ number_format($total, 0, ',', '.') }} đ
                                </td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td> 0 đ</td>
                            </tr>
                            <tr>
                                <td>Tổng cộng</td>
                                <td>
                                    {{ number_format($total, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('cart') }}" class="boxed-btn">Giỏ hàng</a>
                    <a href="#" class="boxed-btn">Thanh toán</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/check-out.js') }}"></script>
@endpush    