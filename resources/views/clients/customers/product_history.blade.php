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
.toast-success {
    background-color: #38a169 !important; 
    color: #fff !important;
}

.toast-error {
    background-color: #e53e3e !important; 
    color: #fff !important;
}

.toast-warning {
    background-color: #dd6b20 !important;
    color: #fff !important;
}

.toast-info {
    background-color: #3182ce !important;
    color: #fff !important;
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
                    <p>Coffee & Tea</p>
                    <h1>Sản phẩm đã xem</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb -->

<!-- customer info section -->
<div class="contact-from-section mt-5 mb-5">
    <div class="container">
        <div class="row">   
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

            @include('clients.customers.sub_layout_customer')
                  
            <!-- Nội dung chính -->
            <div class="col-lg-8">
                @if (!empty($productToHistory))
                    <div class="section-title text-center">
                        <form method="POST" action="{{ route('history.clearAll') }}" style="display: inline;">
                            @csrf
                            <button type="submit"
                                    style="background: none; border: none; padding: 0; font: inherit; cursor: pointer; color: red; text-decoration: underline;">
                                Xóa lịch sử
                            </button>
                        </form>
                    </div>

                    <div class="row"  id="product-list">
                        @foreach ($productToHistory as $sp)
                            <div class="col-md-4 col-6 text-center mb-4">
                                <div class="single-product-item">
                                    <div class="product-image position-relative">
                                        <form method="POST" action="{{ route('history.removeProduct', $sp['ma_san_pham']) }}"
                                            class="remove-history-product-form"
                                            data-name="{{ $sp['ten_san_pham'] }}"
                                            style="position:absolute; top:5px; left:5px; z-index: 3;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger btn-remove-favorite"
                                                    style="padding: 2px 6px; font-size: 12px; border-radius: 50%;"
                                                    title="Xóa khỏi lịch sử đã xem">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('product.detail', $sp['slug']) }}">
                                            <img src="{{ isset($sp['anh_dai_dien']) && $sp['anh_dai_dien'] 
                                                ? asset('storage/' . $sp['anh_dai_dien']) 
                                                : asset('images/no_product_image.png') }}" 
                                                alt="" />
                                        </a>
                                    </div>
                                    <h5 class="mt-2">{{ \Illuminate\Support\Str::limit($sp['ten_san_pham'], 15) }}</h5>
                                    <a href="{{ route('product.detail', $sp['slug']) }}" class="cart-btn mt-1">
                                        <i class="fas fa-shopping-cart"></i> Đặt mua
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted">Bạn chưa xem sản phẩm nào. <a href="{{ route('product') }}">Khám phá ngay</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- end customer info section -->
@endsection
@push('scripts')

@endpush