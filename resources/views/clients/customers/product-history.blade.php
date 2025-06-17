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
                    <h1>Thông tin khách hàng</h1>
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
            @include('clients.customers.sub_layout_customer')
            <div class="col-lg-8">
                <!-- product history -->
                @if (!empty($productToHistory)) 
                    <div class="more-products mb-150">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2 text-center">
                                    <div class="section-title">
                                        <h3><span class="orange-text"></span>Sản phẩm đã xem</h3>
                                        <p>
                                            Các sản phẩm bạn đã xem gần đây
                                            <form method="POST" action="{{ route('history.clearAll') }}" style="display: inline;">
                                                @csrf
                                                <button type="submit"
                                                    style="background: none; border: none; padding: 0; font: inherit; cursor: pointer; color: red; text-decoration: underline;">
                                                    Xóa lịch sử
                                                </button>
                                            </form>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($productToHistory as $pro)
                                    <div class="col-lg-3 col-md-4 col-sm-6 text-center">
                                        <div class="single-product-item">
                                            <div class="product-image">
                                                <div class="icon-wrapper">
                                                    @if (isset($pro['hot']) && $pro['hot'] && isset($pro['is_new']) && $pro['is_new'])
                                                        <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                                        <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon second">
                                                    @elseif(isset($pro['hot']) && $pro['hot'])
                                                        <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                                    @elseif(isset($pro['is_new']) && $pro['is_new'])
                                                        <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon">
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('history.removeProduct', $pro['ma_san_pham']) }}"
                                                    class="remove-history-product-form"
                                                    data-name="{{ $pro['ten_san_pham'] }}"
                                                    style="position:absolute; top:5px; left:5px; z-index: 3;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger btn-remove-favorite"
                                                            data-id="{{ $pro['ma_san_pham'] }}"
                                                            style="padding: 2px 6px; font-size: 12px; border-radius: 50%;"
                                                            title="Xóa khỏi lịch sử đã xem">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('product.detail',$pro['slug']) }}">
                                                    <img src="{{ isset($pro['anh_dai_dien']) && $pro['anh_dai_dien'] ? asset('storage/' . $pro['anh_dai_dien']) : asset('images/no_product_image.png') }}" alt="">
                                                </a>
                                            </div>
                                            <h3>{{ $pro['ten_san_pham'] }}</h3>
                                            <a href="{{ route('product.detail',$pro['slug']) }}" class="cart-btn"><i class="fas fa-shopping-cart"></i> Đặt mua</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <!-- end product history -->
            </div>
        </div>
    </div>
</div>
<!-- end customer info section -->
@endsection

@push('scripts')

@endpush