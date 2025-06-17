@extends('layouts.app')
@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
<style>

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
@if (session('selected_store_id') === null && $products->isEmpty())
    <div class="contact-from-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="form-title">
                        <h2>Sản phẩm đang được cập nhật...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('selected_store_id') && $products->isEmpty())
    <div class="contact-from-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="form-title">
                        <h2>Sản phẩm tại cửa hàng {{ session('selected_store_name') }} đang được cập nhật...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li class="active" data-filter="*">Tất cả</li>
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
        <div class="row"  id="product-list">
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
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="pagination-wrap">
                            <ul>
                                <li><a href="{{ $products->previousPageUrl() ?? '#' }}">Prev</a></li>
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    <li>
                                        <a href="{{ $products->url($i) }}"
                                            class="{{ $products->currentPage() == $i ? 'active' : '' }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor
                                <li><a href="{{ $products->nextPageUrl() ?? '#' }}">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end products -->
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
@endif
<!-- logo carousel -->
<div class="logo-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo-carousel-inner">
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h1.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h2.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h3.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h4.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h5.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end logo carousel -->
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

