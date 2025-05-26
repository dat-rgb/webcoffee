@extends('layouts.app')

@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
<style>
.product-image {
    position: relative;
}

.icon-wrapper {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    z-index: 2;
}

.hot-icon {
    width: 35px; 
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #fff;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: transform 0.2s ease-in-out;
}

.hot-icon:hover {
    transform: scale(1.1);
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
                    <h1>Kết quả tìm kiếm cho từ khóa "{{ $search }}"</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- products -->
@if (!$products->isEmpty())
    <div class="product-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            @foreach ($categories as $cate)
                                @if (!empty($countCate[$cate->ma_danh_muc]) && $countCate[$cate->ma_danh_muc] > 0)
                                    <li data-filter=".{{ $cate->ma_danh_muc }}">{{ $cate->ten_danh_muc }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row product-lists">
                @foreach ($products as $pro)
                    <div class="col-lg-3 col-md-4 col-sm-6 text-center {{ $pro->danhMuc->ma_danh_muc ?? '' }}">
                        <div class="single-product-item">
                            <div class="product-image">
                                <div class="icon-wrapper">
                                    @if ($pro->hot && $pro->is_new)
                                        <img src="{{ asset('images/product_hot.png') }}" alt="Hot" class="hot-icon">
                                        <img src="{{ asset('images/product_new.png') }}" alt="New" class="hot-icon second">
                                    @elseif ($pro->hot)
                                        <img src="{{ asset('images/product_hot.png') }}" alt="Hot" class="hot-icon">
                                    @elseif ($pro->is_new)
                                        <img src="{{ asset('images/product_new.png') }}" alt="New" class="hot-icon">
                                    @endif
                                </div>
                                <a href="{{ route('product.detail', $pro->slug) }}">
                                    <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="{{ $pro->ten_san_pham }}">
                                </a>
                            </div>
                            <h3>{{ $pro->ten_san_pham }}</h3>
                            <a href="{{ route('product.detail', $pro->slug) }}" class="cart-btn"><i class="fas fa-shopping-cart"></i> Đặt mua</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="contact-from-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="form-title">
                        <h2>Không có sản phẩm nào cho từ khóa "{{ $search }}"</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- end products -->
@endsection
