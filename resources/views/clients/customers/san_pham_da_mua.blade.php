@extends('layouts.app')
@section('title', $title)
@push('styles')
<style>
.product-image {
    position: relative;
    margin-bottom: 15px;
    overflow: hidden;
    border-radius: 6px;
}

.product-lists {
    margin-left: -15px;  
    margin-right: -15px;
}

.product-lists > div[class*="col-"] {
    padding-left: 15px;
    padding-right: 15px;
}

.single-product-item {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    background: #fff;
    transition: box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center; 
}

.single-product-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.single-product-item p {
    font-weight: 600;
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 15px;
}
.cart-btn {
  display: inline-block;
  padding: 4px 8px;      
  background-color: #ff6600;
  color: #fff;
  border-radius: 4px;
  font-weight: 500;       
  font-size: 0.85rem;     
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.cart-btn i {
  margin-right: 4px;
  font-size: 0.85rem;      
}

.cart-btn:hover {
  background-color: #e65c00;
}
</style>
@endpush
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>Sản phẩm đã mua</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb -->

<!-- sản phẩm đã mua -->
<div class="contact-from-section mt-5 mb-5">
    <div class="container">
        <div class="row">
            <!-- Toggle menu (mobile) -->
            <div class="col-12 d-lg-none px-3 mb-2">
                <div class="toggle-menu-wrapper text-right">
                    <button class="btn btn-sm"
                            type="button"
                            data-toggle="collapse"
                            data-target="#accountMenu"
                            aria-expanded="false"
                            aria-controls="accountMenu">
                        <i class="fas fa-bars mr-1"></i> Menu
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            @include('clients.customers.sub_layout_customer')

            <!-- Nội dung chính -->
            <div class="col-lg-8">
                @if($sanPhamDaMua->isEmpty())
                    <p class="text-center text-muted">Bạn chưa mua sản phẩm nào. <a href="{{ route('product') }}">Khám phá ngay</a></p>
                @else
                    <div class="row product-lists">
                        @foreach ($sanPhamDaMua as $sp)
                            <div class="col-lg-4 col-md-6 col-sm-6 text-center mb-4">
                                <div class="single-product-item">
                                    <div class="product-image position-relative">
                                        <a href="{{ route('product.detail', $sp->slug) }}">
                                            <img src="{{ $sp->hinh_anh ? asset('storage/' . $sp->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                                        </a>
                                    </div>
                                    <p>{{ \Illuminate\Support\Str::limit($sp['ten_san_pham'], 15) }}</p>
                                    <a href="{{ route('product.detail', $sp->slug) }}" class="cart-btn">
                                        <i class="fas fa-shopping-cart"></i> Mua lại
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
