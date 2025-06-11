@extends('layouts.app')
@section('title', $title)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/product_detail.css') }}">
@endpush
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
                    <div class="icon-wrapper">
                        @if ($product->hot && $product->is_new)
                            <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                            <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon second">
                        @elseif($product->hot)
                            <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                        @elseif($product->is_new)
                            <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon">
                        @endif
                    </div>
                    <img src="{{ $product->hinh_anh ? asset('storage/' . $product->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                </div>
            </div>
            <div class="col-md-7">
                <div class="single-product-content">
                    <h3>{{ $product->ten_san_pham }}</h3>
                    @if ($sizes->count() < 2)
                        @php
                            $size = $sizes->first();
                            $totalPrice = $product->gia + ($size->gia_size ?? 0);
                        @endphp
                        <p class="single-product-pricing">
                            {{ number_format($totalPrice, 0, ',', '.') }} đ
                        </p>
                    @else
                        <p id="product-price" class="single-product-pricing" data-base="{{ $product->gia }}">
                            {{ number_format($product->gia, 0, ',', '.') }} đ
                        </p>
                    @endif
                    <!-- Thêm size -->
                    <div class="single-product-form">
                        <form action="">
                            @csrf
                            <div>
                                <input type="hidden" name="store" id="selectedStoreId" value="{{ session('selected_store_id') ?? '' }}">
                                <input type="number" placeholder="1" name="quantity" min="1">
                                <!-- Chọn kích thước -->
                                <div class="product-size mb-3">
                                    <label><strong>Chọn Size (Bắt buộc):</strong></label>
                                    <div class="btn-group-toggle d-flex gap-2 mt-2 flex-wrap" data-toggle="buttons">
                                        @foreach ($sizes as $size)
                                            <label class="btn btn-outline-secondary btn-sm size-label">
                                                <input type="radio" name="size" value="{{ $size->ma_size }}" data-gia="{{ $size->gia_size }}">
                                                {{ $size->ten_size }} - {{ number_format($size->gia_size + $product->gia, 0, ',', '.') }} đ
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if ( $sizes->count() == 0)
                                <span style="color: #ff9900; font-weight: 600;">
                                    Sản phẩm đang cập nhật... Vui lòng quay lại sau nhé!
                                </span>
                            @else
                                <div>
                                    <a href="#" 
                                        data-url="{{ route('cart.addToCart', ['id' => $product->ma_san_pham]) }}"
                                        class="cart-btn add-to-cart">
                                        <i class="fas fa-shopping-cart"></i> 
                                        Thêm vào giỏ hàng
                                    </a>
                                    <a href="" class="cart-btn"><i class="fas fa-credit-card"></i> Mua ngay</a>
                                </div> 
                            @endif
                        </form>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <p style="margin: 0;">
                                <strong>Danh mục: </strong>{{ $product->danhMuc->ten_danh_muc }}
                            </p>
                            <span id="btnHeartToggle" role="button" tabindex="0" aria-label="Yêu thích sản phẩm"
                                style="cursor:pointer; font-size:36px; user-select:none;">
                                <i class="{{ $isFavorited ? 'fas text-danger' : 'far text-secondary' }} fa-heart"></i>
                            </span>

                        </div>

                        <p><strong>Mô tả: </strong>{{ $product->mo_ta }}</p>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $product->rating)
                                <i class="fas fa-star star-gold star-big"></i> <!-- Sao đầy -->
                            @elseif ($i - 0.5 == $product->rating)
                                <i class="fas fa-star-half-alt star-gold star-big"></i> <!-- Sao nửa -->
                            @else
                                <i class="far fa-star star-gold star-big"></i> <!-- Sao rỗng -->
                            @endif
                        @endfor
                        <!-- <p>Lượt xem</p> -->
                        <!--  -->
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
             @foreach ($productRelate as $pro)
                <div class="col-lg-3 col-md-4 col-sm-6 text-center">
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
<!-- end more products -->

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
document.addEventListener('DOMContentLoaded', function () {
    const btnHeart = document.getElementById('btnHeartToggle');

    function toggleFavorite() {
        const icon = btnHeart.querySelector('i');

        fetch('{{ route("favorite.toggle", ["id" => $product->ma_san_pham]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: "{{ $product->ma_san_pham }}" })
        })
        .then(response => response.json())
        .then(data => {
    if (data.success) {
        icon.classList.toggle('far', !data.favorited);
        icon.classList.toggle('fas', data.favorited);

        icon.classList.toggle('text-secondary', !data.favorited);
        icon.classList.toggle('text-danger', data.favorited);

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
        };

        toastr[data.favorited ? 'success' : 'info'](data.message);
    } else {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };
        toastr.error(data.message || 'Có lỗi xảy ra');
    }
})
        .catch(() => {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };
            toastr.error('Không thể kết nối đến máy chủ');
        });
    }

    btnHeart.addEventListener('click', toggleFavorite);
    btnHeart.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleFavorite();
        }
    });
});
</script>
@endpush
