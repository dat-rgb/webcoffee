@extends('layouts.app')
@section('title', $title)
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
                    <p class="single-product-pricing"><span></span> {{ number_format($product->gia, 0, ',', '.') }} đ</p>
                    <!-- Thêm size -->
                    <div class="single-product-form">
                        <form action="index.html">
                            
                            <input type="number" placeholder="0">
                           
                        </form>
                        <!-- Chọn kích thước -->
                    
                        <a href="" class="cart-btn"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng</a>
                        <a href="" class="cart-btn"><i class="fas fa-shopping-cart"></i> Mua ngay</a>
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