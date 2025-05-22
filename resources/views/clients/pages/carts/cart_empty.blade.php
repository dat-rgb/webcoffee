<!-- empty-cart -->
<img src="{{ asset('images/empty-cart.png') }}" alt="Giỏ hàng trống" style="width: 150px; padding:20px">
<div class="text-start">
    <h5 class="mb-2 text-muted">Giỏ hàng của bạn đang trống!</h5>
    <a href="{{ route('product') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm
    </a>
</div>
<!-- end empty-cart -->