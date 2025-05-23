@extends('layouts.app')
@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
<style>
.product-image {
  position: relative;
  display: inline-block;
}

.product-image .icon-wrapper {
  position: absolute;
  top: 5px;
  right: 5px;
  z-index: 10;
  display: flex;
  gap: 5px; 
}

.product-image .hot-icon {
  width: 40px;
  height: 40px;
}
.product-lists {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* khoảng cách giữa các item */
}

.single-product-item {
    background: #fff;
    border: 1px solid #eee;
    padding: 15px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.single-product-item {
    min-height: 100%;
}
.product-image {
    position: relative;
}

.hot-icon {
    position: absolute;
    top: 5px;
    left: 5px;
    width: 40px;
}
.hot-icon.second {
    left: 50px;
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
                            <div class="icon-wrapper">
                                @if ($pro->hot && $pro->is_new)
                                    <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                    <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon second">
                                @elseif($pro->hot)
                                    <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                @elseif($pro->is_new)
                                    <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon">
                                @endif
                            </div>
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