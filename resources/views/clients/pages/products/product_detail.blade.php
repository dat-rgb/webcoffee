@extends('layouts.app')
@section('title', $title)

@push('styles')
<style>
.size-label {
    background-color: #fff;
    color: #333;
    border: 1px solid #ccc;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-block;
    margin-right: 10px; /* thêm khoảng cách ngang */
    margin-top: 10px;
    margin-bottom: 10px; /* thêm khoảng cách dọc */
    max-width: 100%;
    box-sizing: border-box;
    white-space: normal; /* cho phép xuống dòng */
}

/* Thu nhỏ font trên màn hình bé hơn 480px */
@media (max-width: 480px) {
    .size-label {
        font-size: 14px;
        padding: 8px 12px;
    }
}
.single-product-img {
    position: relative;
    display: inline-block;
}

.single-product-img img.hot-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 50px;       /* tăng size */
    height: 50px;      /* tăng size */
    z-index: 10;
}

.single-product-img img.hot-icon.second {
    right: 60px;       /* tăng khoảng cách để rộng hơn theo size mới */
}
.star-big {
    font-size: 20px; /* hoặc bạn muốn lớn cỡ nào thì chỉnh */
}
.star-gold {
    color: gold;
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
                    @if ($product->hot && $product->is_new)
                        <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                        <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon second">
                    @elseif($product->hot)
                        <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                    @elseif($product->is_new)
                        <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon">
                    @endif
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
                            @if (($product->san_pham_pha_che == 0 && $sizes->count() == 0))
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
                        <p><strong>Tag: </strong>{{ $product->danhMuc->ten_danh_muc }}</p>
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
             @foreach ( $productRelate as  $pro)
                <div class="col-lg-3 col-md-4 col-sm-6 text-center">
                    <div class="single-product-item">
                        <div class="product-image">
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
@endsection
@push('scripts')

@endpush