@extends('layouts.app')
@section('title', $title)

@push('styles')
<style>
    .btn-group-toggle .btn input[type="radio"]:checked + label,
    .btn-group-toggle .btn.active {
        background-color: #f28123;
        color: #fff;
        border-color: #f28123;
    }

    .btn-group-toggle .btn {
        cursor: pointer;
        padding: 10px 20px; /* tăng xíu */
        font-size: 16px;     /* tăng nhẹ */
        border-radius: 6px;
    }

    .btn-group-toggle {
        gap: 10px;
        flex-wrap: wrap;
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
                    <p>Chi tiết sản phẩm</p>
                    <h1>{{ $product->ten_san_pham }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- single product -->
<div class="single-product mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="single-product-img">
                    <img src="{{ asset('storage/' . $product->hinh_anh) }}" alt="">
                </div>
            </div>
            <div class="col-md-7">
                <div class="single-product-content">
                    <h3>{{ $product->ten_san_pham }}</h3>
                    <p id="product-price" class="single-product-pricing" data-base="{{ $product->gia }}">
                        {{ number_format($product->gia, 0, ',', '.') }} đ
                    </p>
                    <!-- Thêm size -->
                    <div class="single-product-form">
                        <form action="">
                            <div>
                                <input type="number" placeholder="1" min="1" max="1000000">
                                <!-- Chọn kích thước -->
                                @if(count($sizes) >= 2)
                                    <div class="product-size mb-3">
                                        <label><strong>Chọn Size:</strong></label>
                                        <div class="btn-group-toggle d-flex gap-2 mt-2 flex-wrap" data-toggle="buttons">
                                            @foreach ($sizes as $size)
                                                <label class="btn btn-outline-secondary btn-sm size-label">
                                                    <input type="radio" name="size" value="{{ $size->ma_size }}" data-gia="{{ $size->gia_size }}">
                                                    {{ $size->ten_size }} - {{ number_format($size->gia_size + $product->gia, 0, ',', '.') }} đ
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <a href="" class="cart-btn"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng</a>
                                <a href="" class="cart-btn"><i class="fas fa-shopping-cart"></i> Mua ngay</a>
                            </div>
                        </form>
                        <p><strong>Tag: </strong>{{ $product->danhMuc->ten_danh_muc }}</p>
                        <p>{{ $product->mo_ta }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end single product -->

<!-- more products -->
<div class="more-products mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">	
                    <h3><span class="orange-text"></span>Sản phẩm liên quan</h3>
                    <p>{{ $product->danhMuc->mo_ta }}</p>
                </div>
            </div>
        </div>
        <div class="row">
             @foreach ( $productRelate as  $pro)
                <div class="col-lg-3 col-md-4 col-sm-6 text-center">
                    <div class="single-product-item">
                        <div class="product-image">
                            <a href="{{ route('sanpham.detail',$pro->slug) }}"><img src="{{ asset('storage/'. $pro->hinh_anh) }}" alt=""></a>
                        </div>
                        <h3>{{ $pro->ten_san_pham }}</h3>
                        <a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- end more products -->
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $('input[name="size"]').on('change', function() {
        var giaSize = parseFloat($(this).data('gia')); // Lấy giá size
        var giaBase = parseFloat($('#product-price').data('base')); // Lấy giá cơ bản
        var total = giaBase + giaSize; // Tính tổng giá

        // Cập nhật giá hiển thị
        $('#product-price').text(total.toLocaleString('vi-VN') + ' đ');
    });
});
</script>

@endpush