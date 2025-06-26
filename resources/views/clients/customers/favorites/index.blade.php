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
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>{{ $subtitle }}</h1>
                </div>
            </div>  
        </div>
    </div>
</div>
<!-- products -->
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
            <div class="col-lg-7 col-md-4">
                @if($favorites->isEmpty())
                    <div>
                        <p class="text-center text-muted">Chưa có sản phẩm trong danh sách yêu thích của bạn. <a href="{{ route('product') }}">Khám phá ngay</a></p>
                    </div>
                @else
                    <div class="row">
                        @foreach ($favorites as $pro)
                        <div class="col-lg-4 col-md-6 col-sm-6 text-center mb-4">  <!-- mỗi sản phẩm chiếm 1/3 hàng -->
                            <div class="single-product-item">
                                <div class="product-image position-relative">
                                    <!-- Nút Xóa -->
                                    <form method="POST" action="{{ route('favorite.toggle', $pro->ma_san_pham) }}"
                                        class="remove-favorite-form"
                                        data-name="{{ $pro->sanPham->ten_san_pham }}"
                                        style="position:absolute; top:5px; left:5px; z-index: 3;">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-danger btn-remove-favorite"
                                                data-id="{{ $pro->ma_san_pham }}"
                                                style="padding: 2px 6px; font-size: 12px; border-radius: 50%;"
                                                title="Xóa khỏi yêu thích">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('product.detail',$pro->sanPham->slug) }}">
                                        <img src="{{ $pro->sanPham->hinh_anh ? asset('storage/' . $pro->sanPham->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                                    </a>
                                </div>
                                <p>{{ \Illuminate\Support\Str::limit($pro->sanPham->ten_san_pham, 20) }}</p>
                                <a href="{{ route('product.detail',$pro->sanPham->slug) }}" class="cart-btn">
                                    <i class="fas fa-shopping-cart"></i> Đặt mua
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
<!-- end products -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const removeForms = document.querySelectorAll('.remove-favorite-form');
    removeForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const productName = form.getAttribute('data-product-name');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    localStorage.setItem('favorite_deleted', productName);
                    location.reload();
                } else {
                    throw new Error('Lỗi khi xoá');
                }
            })
            .catch(() => {
                toastr.error('Đã xảy ra lỗi khi xoá sản phẩm');
            });
        });
    });

    const deletedProduct = localStorage.getItem('favorite_deleted');
    if (deletedProduct) {
        toastr.success(`Đã xoá khỏi danh sách yêu thích`);
        localStorage.removeItem('favorite_deleted');
    }
});
</script>
@endpush