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

.icon-wrapper img.hot-icon.second {
    /* Nếu dùng position absolute, mới có tác dụng, ở đây để an toàn tạm giữ */
    /* Có thể bỏ nếu dùng flex trong .icon-wrapper */
}

/* Giữ nguyên Bootstrap grid padding - margin */
.product-lists {
    margin-left: -15px;  /* bù padding column Bootstrap */
    margin-right: -15px;
}

.product-lists > div[class*="col-"] {
    padding-left: 15px;
    padding-right: 15px;
}

/* Card sản phẩm */
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
    text-align: center; /* căn giữa text */
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
  padding: 4px 8px;      /* giảm padding lại */
  background-color: #ff6600;
  color: #fff;
  border-radius: 4px;
  font-weight: 500;       /* tăng font-weight cho nét đậm vừa phải */
  font-size: 0.85rem;     /* giảm kích thước chữ */
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.cart-btn i {
  margin-right: 4px;
  font-size: 0.85rem;      /* giảm kích thước icon */
}

.cart-btn:hover {
  background-color: #e65c00;
}

</style>
@endpush
@section('content')
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>Favorites</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- products -->
<div class="product-section mt-150 mb-150">
<div class="container">
    <div class="row">
        @include('clients.customers.sub_layout_customer')
        <div class="col-lg-7 col-md-4">
            <div class="row product-lists">
                @foreach ($favorites as $pro)
                <div class="col-lg-4 col-md-6 col-sm-6 text-center mb-4">  <!-- mỗi sản phẩm chiếm 1/3 hàng -->
                    <div class="single-product-item">
                        <div class="product-image position-relative">
                            <div class="icon-wrapper">
                                @if ($pro->sanPham->hot && $pro->sanPham->is_new)
                                    <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                    <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon second">
                                @elseif($pro->sanPham->hot)
                                    <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                @elseif($pro->sanPham->is_new)
                                    <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon">
                                @endif
                            </div>

                            <!-- Nút Xóa -->
                            <form method="POST" action="" class="remove-favorite-form" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi yêu thích?');" style="position:absolute; top:5px; left:5px; z-index: 3;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 2px 6px; font-size: 12px; border-radius: 50%;" title="Xóa khỏi yêu thích">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>

                            <a href="{{ route('product.detail',$pro->sanPham->slug) }}">
                                <img src="{{ $pro->sanPham->hinh_anh ? asset('storage/' . $pro->sanPham->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                            </a>
                        </div>
                        <p>{{ $pro->sanPham->ten_san_pham }}</p>
                        <a href="{{ route('product.detail',$pro->sanPham->slug) }}" class="cart-btn">
                            <i class="fas fa-shopping-cart"></i> Đặt mua
                        </a>
                    </div>  
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>



</div>
<!-- end products -->
@endsection
