@extends('layouts.app')
@section('title',$title)
@section('subtitle',$subtitle)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
<style>
.voucher-item:hover {
    border-color: #ff4d4f;
    box-shadow: 0 0 0.5rem rgba(255, 77, 79, 0.2);
}


</style>
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
                                                <div class="row g-4">
                                                    <div class="col-12 col-md-4">
                                                        <label for="so_nha" class="form-label fw-semibold">Số nhà, Hẻm,</label>
                                                        <input type="text" id="so_nha" name="so_nha" class="form-control" 
                                                            placeholder="VD: 123/4 Hẻm 5" 
                                                            value="{{ old('so_nha', Auth::user()->khachHang->so_nha ?? '') }}">
                                                    </div>
                                                    <div class="col-12 col-md-8">
                                                        <label for="ten_duong" class="form-label fw-semibold">Tên đường</label>
                                                        <input type="text" id="ten_duong" name="ten_duong" class="form-control" 
                                                            placeholder="VD: Đường Lê Lợi" 
                                                            value="{{ old('ten_duong', Auth::user()->khachHang->ten_duong ?? '') }}">
                                                    </div>
                                                </div>

                                                {{-- Chọn khu vực --}}
                                                <div class="row g-4">
                                                    <div class="col-12 col-md-4">
                                                        <label for="provinceSelect" class="form-label fw-semibold">Tỉnh/Thành</label>
                                                        <select class="form-select w-100" name="province" id="provinceSelect">
                                                            <option value="" selected>Chọn tỉnh/thành</option>
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
                                            <div id="manualVoucherContainer"></div>

                                            @php
                                                $isGuest = !Auth::check() || !Auth::user()->khachHang;
                                                $maKH = $isGuest ? null : Auth::user()->khachHang->ma_khach_hang;
                                                $diemHienTai = $isGuest ? 0 : Auth::user()->khachHang->diem_thanh_vien;
                                            @endphp


                                            @if($isGuest)
                                                <div class="alert alert-warning">
                                                    <i class="fa fa-exclamation-circle"></i> Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để sử dụng voucher.
                                                </div>
                                            @endif

                                            @foreach($vouchers as $voucher)
                                                @php
                                                    $isUsed = false;

                                                    if (!$isGuest) {
                                                        $isUsed = \App\Models\HoaDon::where('ma_khach_hang', $maKH)
                                                            ->where('ma_voucher', $voucher->ma_voucher)
                                                            ->exists();
                                                    }

                                                    if ($isUsed) {
                                                        continue;
                                                    }

                                                    $diemToiThieu = $voucher->diem_toi_thieu ?? 0;
                                                    $soDiemThieu = max(0, $diemToiThieu - $diemHienTai);

                                                    $isDisabled = $isGuest || 
                                                                $subTotal < $voucher->dieu_kien_ap_dung || 
                                                                $soDiemThieu > 0;

                                                    $reason = null;
                                                    if ($isGuest) {
                                                        $reason = 'Bạn cần đăng nhập để sử dụng voucher này.';
                                                    } elseif ($subTotal < $voucher->dieu_kien_ap_dung) {
                                                        $reason = 'Đơn hàng chưa đủ điều kiện áp dụng.';
                                                    } elseif ($soDiemThieu > 0) {
                                                        $reason = "Bạn cần thêm $soDiemThieu điểm để dùng voucher này.";
                                                    }
                                                @endphp

                                                @if($voucher->doi_tuong_ap_dung === 'hoa_don')
                                                <div class="voucher-item mb-3 p-3 rounded border shadow-sm d-flex align-items-center {{ $isDisabled ? 'bg-light text-muted' : 'bg-white' }}" style="transition: all 0.3s ease;">
                                                    <input type="checkbox"
                                                        class="form-check-input voucher-checkbox"
                                                        name="voucher"
                                                        id="voucher{{ $voucher->ma_voucher }}"
                                                        value="{{ $voucher->ma_voucher }}"
                                                        data-gia-tri-giam="{{ $voucher->gia_tri_giam }}"
                                                        data-giam-gia-max="{{ $voucher->giam_gia_max }}"
                                                        data-dieu-kien="{{ $voucher->dieu_kien_ap_dung }}"
                                                        style="width: 20px; height: 20px;"
                                                        {{ $isDisabled ? 'disabled' : '' }}>

                                                    <img src="{{ asset('storage/' . ($voucher->hinh_anh ?? 'vouchers/voucher-default.png')) }}"
                                                        alt="{{ $voucher->ten_voucher }}"
                                                        class="rounded me-3"
                                                        style="width: 80px; height: 80px; object-fit: cover; {{ $isDisabled ? 'opacity: 0.5;' : '' }}">

                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="fw-semibold {{ $isDisabled ? 'text-muted' : 'text-dark' }}" style="font-size: 1rem;">
                                                                {{ $voucher->ten_voucher }}
                                                            </span>
                                                            <span class="badge px-3 py-2" style="background-color: #ff4d4f; color: #fff; font-size: 0.9rem;">
                                                                {{ $voucher->gia_tri_giam < 100 ? $voucher->gia_tri_giam . '%' : number_format($voucher->gia_tri_giam, 0, ',', '.') . 'đ' }}
                                                            </span>
                                                        </div>

                                                        <small class="text-secondary d-block">
                                                            Đơn từ <strong>{{ number_format($voucher->dieu_kien_ap_dung, 0, ',', '.') }}đ</strong> 
                                                            | HSD: {{ \Carbon\Carbon::parse($voucher->ngay_ket_thuc)->format('d/m/Y') }}
                                                        </small>

                                                        @if($isDisabled && $reason)
                                                            <small class="text-danger d-block mt-1">
                                                                <i class="fa fa-exclamation-circle me-1"></i>{{ $reason }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Patyment method -->
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
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodNapas247" value="NAPAS247" >
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodNapas247">
                                                    <img src="{{ asset('images/napas247.png') }}" alt="Napas247" style="width:40px; height:auto;">
                                                    Thanh toán trực tuyến Napas 247 (VietQR)
                                                    </label>
                                                </div>

                                                <!-- <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodVNPAY" value="VNPAY" >
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodVNPAY">
                                                    <img src="{{ asset('images/vnpay.webp') }}" alt="VNPAY" style="width:40px; height:auto;">
                                                    Thanh toán trực tuyến VNPAY
                                                    </label>
                                                </div> -->
                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethodCOD" value="COD" >
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="paymentMethodCOD">
                                                    <img src="{{ asset('images/cod.webp') }}" alt="COD" style="width:40px; height:auto;">
                                                    Thanh toán khi nhận hàng (COD)
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
                <!--  -->
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

                const v = res.voucher;
                const html = `
                <div class="voucher-item mb-3 p-3 rounded border shadow-sm d-flex align-items-center bg-white">
                    <input type="checkbox"
                        class="form-check-input voucher-checkbox"
                        name="voucher"
                        id="manualVoucher"
                        value="${v.ma_voucher}"
                        data-gia-tri-giam="${v.gia_tri_giam}"
                        data-giam-gia-max="${v.giam_gia_max}"
                        data-dieu-kien="${v.dieu_kien_ap_dung}"
                        style="width: 20px; height: 20px;">

                    <img src="/storage/${v.hinh_anh || 'vouchers/voucher-default.png'}"
                        alt="${v.ten_voucher}"
                        class="rounded me-3"
                        style="width: 80px; height: 80px; object-fit: cover;">

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-dark" style="font-size: 1rem;">
                                ${v.ten_voucher}
                            </span>
                            <span class="badge px-3 py-2" style="background-color: #ff4d4f; color: #fff; font-size: 0.9rem;">
                                ${v.gia_tri_giam < 100 ? v.gia_tri_giam + '%' : new Intl.NumberFormat().format(v.gia_tri_giam) + 'đ'}
                            </span>
                        </div>
                        <small class="text-secondary d-block">
                            Đơn từ <strong>${new Intl.NumberFormat().format(v.dieu_kien_ap_dung)}đ</strong> |
                            HSD: ${v.ngay_ket_thuc}
                        </small>
                    </div>
                </div>`;

                $('#manualVoucherContainer').html(html);
                $('#voucherCodeInput').val('');

                // Trigger lại event nếu cần
                $('.voucher-checkbox').trigger('change');
            },
            error: function () {
                Swal.fire('Lỗi server', 'Vui lòng thử lại sau.', 'error');
            }
        });
    });
});
</script>

@endpush    