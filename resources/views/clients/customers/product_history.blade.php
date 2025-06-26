@extends('layouts.app')
@section('title', $title)   

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

                    <div class="row product-lists">
                        @foreach ($productToHistory as $sp)
                            <div class="col-lg-4 col-md-6 col-sm-6 text-center mb-4">
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
                                            <img src="{{ isset($sp['anh_dai_dien']) && $sp['anh_dai_dien'] ? asset('storage/' . $sp['anh_dai_dien']) : asset('images/no_product_image.png') }}" alt="">
                                        </a>
                                    </div>
                                    <p>{{ \Illuminate\Support\Str::limit($sp['ten_san_pham'], 15) }}</p>
                                    <a href="{{ route('product.detail', $sp['slug']) }}" class="cart-btn">
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