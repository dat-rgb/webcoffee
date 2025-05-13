@extends('layouts.app')
@section('title', $title)
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Fresh and Organic</p>
                    <h1>Shop</h1>
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
                        @foreach ($categorys as $cate)
                            @if (!empty($countCate[$cate->ma_danh_muc]) && $countCate[$cate->ma_danh_muc] > 0)
                                <li data-filter=".{{ $cate->ma_danh_muc }}">
                                    {{ $cate->ten_danh_muc }} ({{ $countCate[$cate->ma_danh_muc] }})
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
                            <a href="{{ route('sanpham.detail',$pro->slug) }}"><img src="{{ asset('storage/'. $pro->hinh_anh) }}" alt=""></a>
                        </div>
                        <h3>{{ $pro->ten_san_pham }}</h3>
                        <a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="pagination-wrap">
                    <ul>
                        <li><a href="#">Prev</a></li>
                        <li><a href="#">1</a></li>
                        <li><a class="active" href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">Next</a></li>
                    </ul>
                </div>
            </div>
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