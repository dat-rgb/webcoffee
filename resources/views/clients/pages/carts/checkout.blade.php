@extends('layouts.app')
@section('title',$title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpush
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
        <form action="{{ route('payment') }}" id="check-out-form" method="POST">
            @csrf
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
                                <div id="collapseOne" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="billing-address-form">
                                            <div class="form-group mb-3">
                                                <label for="ho_ten_khach_hang">Họ và tên</label>
                                                <input type="text" id="ho_ten_khach_hang" name="ho_ten_khach_hang" class="form-control" placeholder="Họ và tên" value="{{ Auth::user()->khachHang->ho_ten_khach_hang ?? '' }}" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email"class="form-control" placeholder="Email" value="{{ Auth::user()->email ?? '' }}" required>
                                            </div>

                                            <!-- <div class="form-group mb-3">
                                                <label for="dia_chi">Địa chỉ</label>
                                                <input type="text" id="dia_chi" name="dia_chi" class="form-control" placeholder="Địa chỉ" value="{{ Auth::user()->khachHang->dia_chi ?? '' }}" required>
                                            </div> -->

                                            <div class="form-group mb-3">
                                                <label for="so_dien_thoai">Số điện thoại</label>
                                                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-control" placeholder="Số điện thoại" value="{{ Auth::user()->khachHang->so_dien_thoai ?? '' }}" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="ghi_chu">Ghi chú</label>
                                                <textarea id="ghi_chu" name="ghi_chu" class="form-control" rows="4" placeholder="Ghi chú"></textarea>
                                            </div>

                                            <!-- Lựa chọn phương thức nhận hàng -->
                                            <div class="mb-3 shipping-method-group">
                                                <label class="fw-bold">Phương thức nhận hàng:</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="shippingMethod" id="shipToHome" value="delivery" checked>
                                                    <label class="form-check-label" for="shipToHome">Giao hàng tận nơi</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="shippingMethod" id="pickupStore" value="pickup">
                                                    <label class="form-check-label" for="pickupStore">Đến lấy tại cửa hàng</label>
                                                </div>
                                            </div>

                                            <!-- Phần giao hàng -->
                                            <div class="shipping-address-form p-4 rounded-3 border shadow-sm bg-white" id="deliverySection">
                                                <div class="mb-3">
                                                    <label for="dia_chi" class="form-label fw-semibold">Số nhà, tên đường</label>
                                                    <input type="text" id="dia_chi" name="dia_chi" class="form-control" placeholder="Nhập số nhà, tên đường..." value="{{ Auth::user()->khachHang->dia_chi ?? '' }}">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                    <label for="provinceSelect" class="form-label fw-semibold">Tỉnh/Thành phố</label>
                                                    <select class="form-select" name="province" id="provinceSelect">
                                                        <option value="" selected>Chọn tỉnh/thành phố</option>
                                                    </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                    <label for="districtSelect" class="form-label fw-semibold">Quận/Huyện</label>
                                                    <select class="form-select" name="district" id="districtSelect" disabled>
                                                        <option value="" selected>Chọn quận/huyện</option>
                                                    </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                    <label for="wardSelect" class="form-label fw-semibold">Xã/Phường</label>
                                                    <select class="form-select" name="ward" id="wardSelect" disabled>
                                                        <option value="" selected>Chọn xã/phường</option>
                                                    </select>
                                                    </div>
                                                </div>

                                                <!-- Hidden fields -->
                                                <input type="hidden" id="provinceName" name="provinceName" value="">
                                                <input type="hidden" id="districtName" name="districtName" value="">
                                                <input type="hidden" id="wardName" name="wardName" value="">
                                            </div>

                                            <!-- Phần đến lấy tại cửa hàng -->
                                            <div class="store-info mt-3" id="pickupSection" style="display: none;">
                                                <div class="alert alert-info">
                                                    <strong>Nhận món tại địa chỉ:</strong><br>
                                                    Tên cửa hàng: <strong>{{ session('selected_store_name') }}</strong><br>
                                                    Địa chỉ: {{ session('selected_store_dia_chi') }}
                                                </div>
                                            </div>
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
                                <div id="collapseThree" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="card-details">
                                            <div class="d-flex flex-column gap-3">
                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodCOD" value="COD" required>
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodCOD">
                                                    <img src="{{ asset('images/cod.webp') }}" alt="COD" style="width:40px; height:auto;">
                                                    Thanh toán khi nhận hàng (COD)
                                                    </label>
                                                </div>

                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodNapas247" value="NAPAS247" required>
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodNapas247">
                                                    <img src="{{ asset('images/napas247.png') }}" alt="Napas247" style="width:40px; height:auto;">
                                                    Thanh toán trực tuyến Napas 247 (VietQR)
                                                    </label>
                                                </div>

                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodVNPAY" value="VNPAY" required>
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodVNPAY">
                                                    <img src="{{ asset('images/vnpay.webp') }}" alt="VNPAY" style="width:40px; height:auto;">
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
                                                <span>
                                                    {{ $item['size_name'] }} x {{ $item['product_quantity'] }} 
                                                    <a href="{{ route('cart') }}" class=""></a>
                                                </span>
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
                                    <td>Tạm tính: ({{ count(session('cart', [])) }} món)</td>
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
                        <div class="mt-4 text-end">
                            <a href="{{ route('cart') }}" class="boxed-btn">Giỏ hàng</a>
                            <button type="submit" class="btn boxed-btn">Thanh toán</button>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/check-out.js') }}"></script>
@endpush    