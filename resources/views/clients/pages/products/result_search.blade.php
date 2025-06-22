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

@php
    $storeName = session('selected_store_name');
    $hasStore = session('selected_store_id') !== null;
@endphp

@if ($products->isEmpty())
    <div class="contact-from-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="form-title">
                        <h2>
                            @if ($hasStore)
                                Không có sản phẩm cho từ khóa "{{ $search }}" tại cửa hàng {{ $storeName }}
                            @else
                                Không có sản phẩm nào cho từ khóa "{{ $search }}"
                            @endif
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <!-- title -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3>Sản phẩm</h3>
                    <p>Các sản phẩm theo từ khóa "{{ $search }}"</p>
                </div>
            </div>
        </div>
        <!-- filters -->
        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li class="active" data-filter="*">Tất cả</li>
                        @foreach ($categories as $cate)
                            @if (!empty($countCate[$cate->ma_danh_muc]) && $countCate[$cate->ma_danh_muc] > 0)
                                <li data-filter=".{{ $cate->ma_danh_muc }}">{{ $cate->ten_danh_muc }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- product list -->
        <div class="row" id="product-list">
            @foreach ($products as $pro)
                <div class="col-lg-3 col-md-4 col-sm-6 text-center {{ $pro->danhMuc->ma_danh_muc ?? '' }}">
                    <div class="single-product-item">
                        <div class="product-image">
                            <div class="icon-wrapper">
                                @if ($pro->hot && $pro->is_new)
                                    <img src="{{ asset('images/product_hot.png') }}" class="hot-icon">
                                    <img src="{{ asset('images/product_new.png') }}" class="hot-icon second">
                                @elseif($pro->hot)
                                    <img src="{{ asset('images/product_hot.png') }}" class="hot-icon">
                                @elseif($pro->is_new)
                                    <img src="{{ asset('images/product_new.png') }}" class="hot-icon">
                                @endif
                            </div>
                            <a href="{{ route('product.detail',$pro->slug) }}">
                                <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                            </a>
                        </div>
                        <h5>{{ \Illuminate\Support\Str::limit($pro['ten_san_pham'], 20) }}</h5>
                        <a href="{{ route('product.detail',$pro->slug) }}" class="cart-btn"><i class="fas fa-shopping-cart"></i> Đặt mua</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- end products -->
@endif

<!-- blog section -->
@if (!empty($blogs) && $blogs->count())
    <div class="latest-news mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="section-title">
                        <h3>Blog</h3>
                        <p>Các blogs theo từ khóa "{{ $search }}"</p>
                    </div>
                </div>
            </div>
            <!-- Blog list -->
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-latest-news">
                            <a href="{{ route('blog.detail', $blog->slug) }}">
                                <div class="latest-news-bg" style="background-image: url('{{ asset('storage/' . $blog->hinh_anh) }}'); height: 250px; background-size: cover; background-position: center;"></div>
                            </a>
                            <div class="news-text-box">
                                <h3><a href="{{ route('blog.detail', $blog->slug) }}">{{ $blog->tieu_de }}</a></h3>
                                <p class="blog-meta">
                                    <span class="author"><i class="fas fa-user"></i> {{ $blog->tac_gia }}</span>
                                    <span class="date"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($blog->ngay_dang)->format('d/m/Y') }}</span>
                                </p>
                                <p class="excerpt">{!! \Str::limit(strip_tags($blog->noi_dung), 100) !!}</p>
                                <a href="{{ route('blog.detail', $blog->slug) }}" class="read-more-btn">Xem thêm <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    $(window).on('load', function () {
        var $grid = $('#product-list').isotope({
            itemSelector: '.col-lg-3',
            layoutMode: 'fitRows'
        });

        $('.product-filters li').on('click', function () {
            $('.product-filters li').removeClass('active');
            $(this).addClass('active');
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({ filter: filterValue });
        });
    });
</script>
@endpush
