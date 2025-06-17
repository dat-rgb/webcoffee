@extends('layouts.app')
@section('title',$title)
@section('subtitle',$subtitle)
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
                    <h1>{{ $subtitle }}</h1>
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
                                                <input type="text" id="ho_ten_khach_hang" name="ho_ten_khach_hang" class="form-control" placeholder="Họ và tên" value="{{ Auth::user()->khachHang->ho_ten_khach_hang ?? '' }}" >
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email"class="form-control" placeholder="Email" value="{{ Auth::user()->email ?? '' }}" >
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="so_dien_thoai">Số điện thoại</label>
                                                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-control" placeholder="Số điện thoại" value="{{ Auth::user()->khachHang->so_dien_thoai ?? '' }}" >
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="ghi_chu">Ghi chú</label>
                                                <textarea id="ghi_chu" name="ghi_chu" class="form-control" rows="4" placeholder="Ghi chú"></textarea>
                                            </div>

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

                                            <div class="shipping-address-form p-4 rounded-3 border shadow-sm bg-white" id="deliverySection">
                                                <div class="mb-3">
                                                    <label for="dia_chi" class="form-label fw-semibold">Số nhà, tên đường</label>
                                                    <input type="text" id="dia_chi" name="dia_chi" class="form-control" placeholder="Nhập số nhà, tên đường..." value="{{ Auth::user()->khachHang->dia_chi ?? '' }}">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label for="provinceSelect" class="form-label fw-semibold">Tỉnh/Thành phố</label>
                                                        <select class="form-select w-100" name="province" id="provinceSelect">
                                                            <option value="" selected>Chọn tỉnh/thành phố</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-4">
                                                        <label for="districtSelect" class="form-label fw-semibold">Quận/Huyện</label>
                                                        <select class="form-select w-100" name="district" id="districtSelect" disabled>
                                                            <option value="" selected>Chọn quận/huyện</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-4">
                                                        <label for="wardSelect" class="form-label fw-semibold">Xã/Phường</label>
                                                        <select class="form-select w-100" name="ward" id="wardSelect" disabled>
                                                            <option value="" selected>Chọn xã/phường</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="provinceName" name="provinceName" value="">
                                                <input type="hidden" id="districtName" name="districtName" value="">
                                                <input type="hidden" id="wardName" name="wardName" value="">
                                            </div>

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
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodCOD" value="COD" >
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodCOD">
                                                    <img src="{{ asset('images/cod.webp') }}" alt="COD" style="width:40px; height:auto;">
                                                    Thanh toán khi nhận hàng (COD)
                                                    </label>
                                                </div>

                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodNapas247" value="NAPAS247" >
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodNapas247">
                                                    <img src="{{ asset('images/napas247.png') }}" alt="Napas247" style="width:40px; height:auto;">
                                                    Thanh toán trực tuyến Napas 247 (VietQR)
                                                    </label>
                                                </div>

                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodVNPAY" value="VNPAY" >
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
                            <!-- Voucher -->
                            <div class="card single-accordion">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseVoucher" aria-expanded="false" aria-controls="collapseVoucher">
                                       Voucher
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseVoucher" aria-labelledby="headingVoucher" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="card-details">
                                            <h5 class="mb-3">
                                                <i class="fa fa-ticket text-danger"></i> Chọn hoặc nhập mã giảm giá
                                            </h5>
                                            <!-- Nhập mã voucher -->
                                            <div class="input-group mb-4">
                                                <input type="text" class="form-control" id="voucherCodeInput" placeholder="Nhập mã voucher...">
                                                <div class="input-group-append">
                                                    <button type="button" id="checkVoucherBtn" class="btn btn-outline-primary">Áp dụng</button>
                                                </div>
                                            </div>

                                            <!-- Khu vực để append voucher nếu hợp lệ -->
                                            <div id="manualVoucherContainer"></div>

                                            <!-- Danh sách voucher -->
                                            @foreach($vouchers as $voucher)
                                                @php
                                                    $isDisabled = $subTotal < $voucher->dieu_kien_ap_dung;
                                                    $reason = null;

                                                    if ($isDisabled) {
                                                        $reason = 'Không thể áp dụng, đơn hàng chưa đủ điều kiện';
                                                    }
                                                @endphp

                                                <div class="custom-control custom-radio mb-2 p-2 border rounded d-flex align-items-center {{ $isDisabled ? 'bg-light text-muted' : 'bg-white' }}">
                                                    <input type="radio"
                                                        class="custom-control-input voucher-radio"
                                                        name="voucher"
                                                        id="voucher{{ $voucher->ma_voucher }}"
                                                        value="{{ $voucher->ma_voucher }}"
                                                        data-gia-tri-giam="{{ $voucher->gia_tri_giam }}"
                                                        data-giam-gia-max="{{ $voucher->giam_gia_max }}"
                                                        data-dieu-kien="{{ $voucher->dieu_kien_ap_dung }}"
                                                        {{ $isDisabled ? 'disabled' : '' }}>
                                                    <label class="custom-control-label d-flex align-items-center w-100" for="voucher{{ $voucher->ma_voucher }}">
                                                        <span class="radio-custom mr-3"></span>
                                                        <img src="{{ asset('storage/' . ($voucher->hinh_anh ?? 'vouchers/voucher-default.png')) }}"
                                                            alt="{{ $voucher->ten_voucher }}"
                                                            style="width: 60px; height: 60px; object-fit: cover; {{ $isDisabled ? 'opacity: 0.5;' : '' }}">
                                                        <div class="d-flex flex-column">
                                                            <span class="font-weight-bold {{ $isDisabled ? 'text-muted' : 'text-dark' }}">{{ $voucher->ten_voucher }}</span>
                                                            <small>
                                                                Giảm 
                                                                @if($voucher->gia_tri_giam < 100)
                                                                    {{ $voucher->gia_tri_giam }}%
                                                                @else
                                                                    {{ number_format($voucher->gia_tri_giam, 0, ',', '.') }}đ
                                                                @endif
                                                                (Tối đa {{ number_format($voucher->giam_gia_max, 0, ',', '.') }}đ) | 
                                                                ĐH từ {{ number_format($voucher->dieu_kien_ap_dung, 0, ',', '.') }}đ | 
                                                                HSD: {{ \Carbon\Carbon::parse($voucher->ngay_ket_thuc)->format('d/m/Y') }}
                                                            </small>
                                                            @if($isDisabled && $reason)
                                                                <small class="text-danger mt-1">{{ $reason }}</small>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
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
                                    <td id="subtotal">{{ number_format($subTotal, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td>Shipping:</td>
                                    <td id="shippingFeeText">{{ number_format($shippingFee, 0, ',', '.') }} đ</td>
                                    <input type="hidden" id="shippingFeeInput" name="shippingFee" value="{{ $shippingFee }}" data-original-fee="{{ $shippingFee }}">
                                </tr>
                                <tr>
                                    <td>Giảm giá:</td>
                                    <td id="discount" name="discount">0 đ</td> 
                                </tr>
                                <tr>
                                    <td>Tổng cộng:</td>
                                    <td id="total">{{ number_format($total, 0, ',', '.') }} đ</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4 text-end">
                            <a href="{{ route('cart') }}" class="boxed-btn">
                                <i class="fas fa-arrow-left me-1"></i> Giỏ hàng
                            </a>
                            <button type="submit" class="boxed-btn black btn-check-out">
                                <i class="fas fa-credit-card"></i> Đặt hàng
                            </button>
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
<script>
$(document).ready(function () {
    $('#checkVoucherBtn').on('click', function () {
        //e.preventDefault(); 
        let code = $('#voucherCodeInput').val().trim();
        if (!code) return Swal.fire('Lỗi', 'Vui lòng nhập mã voucher', 'warning');

        $.ajax({
            url: '/cart/voucher/check',
            type: 'GET',
            data: { code: code },
            success: function (res) {
                if (!res.success) {
                    Swal.fire('Không hợp lệ', res.message || 'Mã không tồn tại hoặc đã hết hạn', 'error');
                    return;
                }
                // Nếu hợp lệ → render như một voucher bình thường
                const v = res.voucher;
                const isDisabled = false;
                const reason = null;

                const html = `
                <div class="custom-control custom-radio mb-2 p-2 border rounded d-flex align-items-center bg-white">
                    <input type="radio"
                        class="custom-control-input voucher-radio"
                        name="voucher"
                        id="voucher${v.ma_voucher}"
                        value="${v.ma_voucher}"
                        data-gia-tri-giam="${v.gia_tri_giam}"
                        data-giam-gia-max="${v.giam_gia_max}"
                        data-dieu-kien="${v.dieu_kien_ap_dung}">
                    <label class="custom-control-label d-flex align-items-center w-100" for="voucher${v.ma_voucher}">
                        <span class="radio-custom mr-3"></span>
                        <img src="/storage/${v.hinh_anh || 'vouchers/voucher-default.png'}"
                            alt="${v.ten_voucher}" style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold text-dark">${v.ten_voucher}</span>
                            <small>
                                Giảm ${v.gia_tri_giam < 100 ? v.gia_tri_giam + '%' : new Intl.NumberFormat().format(v.gia_tri_giam) + 'đ'}
                                (Tối đa ${new Intl.NumberFormat().format(v.giam_gia_max)}đ) |
                                ĐH từ ${new Intl.NumberFormat().format(v.dieu_kien_ap_dung)}đ |
                                HSD: ${v.ngay_ket_thuc}
                            </small>
                        </div>
                    </label>
                </div>`;

                $('#manualVoucherContainer').html(html);
                $('#voucherCodeInput').val('');

                // Kích hoạt lại sự kiện tính toán
                $('.voucher-radio').off('change').on('change', function () {
                    $('.voucher-radio').trigger('change'); // hoặc gọi lại hàm xử lý bạn đã viết
                });
                //Swal.fire('Thành công', 'Áp dụng voucher thành công!', 'success');
            },
            error: function () {
                Swal.fire('Lỗi server', 'Vui lòng thử lại sau.', 'error');
            }
        });
    });
});
</script>
@endpush    