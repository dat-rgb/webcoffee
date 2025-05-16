@extends('layouts.app')
@section('title', $title)
@section('subtitle', $subtitle)
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

<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li class="active" data-filter="*">All</li>
                        @foreach ($categories as $cate)
                            @if (!empty($countCate[$cate->ma_danh_muc]) && $countCate[$cate->ma_danh_muc] > 0)
                                <li data-filter=".{{ $cate->ma_danh_muc }}" >
                                    {{ $cate->ten_danh_muc }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="row product-lists">
            @foreach ( $products as  $pro)
                <div class="col-lg-3 col-md-4 col-sm-6 text-center {{ $pro->danhMuc->ma_danh_muc ?? '' }}">
                    <div class="single-product-item">
                        <div class="product-image">
                            <a href="{{ route('product.detail',$pro->slug) }}">
                                <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                            </a>
                        </div>
                        <h3>{{ $pro->ten_san_pham }}</h3>
                        <a href="{{ route('product.detail',$pro->slug) }}" class="cart-btn"><i class="fas fa-shopping-cart"></i> Đặt mua</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- end products -->

<!-- logo carousel -->
<div class="logo-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo-carousel-inner">
                    <div class="single-logo-item">
                        <img src="img/company-logos/1.png" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="img/company-logos/2.png" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="img/company-logos/3.png" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="img/company-logos/4.png" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="img/company-logos/5.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end logo carousel -->

@endsection