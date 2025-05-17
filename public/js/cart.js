//thay đổi giá khi chọn size
$(document).ready(function () {
    let lastChecked = null;

    $('input[name="size"]').on('click', function (e) {
        const $this = $(this);
        const $label = $this.closest('label');

        if (this === lastChecked) {
            e.preventDefault(); // chặn radio tự xử lý
            this.checked = false;
            lastChecked = null;
            $label.removeClass('active');

            // Reset giá
            const basePrice = parseFloat($('#product-price').data('base'));
            $('#product-price').text(basePrice.toLocaleString('vi-VN') + ' đ');
        } else {
            $('input[name="size"]').each(function () {
                $(this).closest('label').removeClass('active');
            });

            this.checked = true;
            $label.addClass('active');
            lastChecked = this;

            // Update giá
            const giaSize = parseFloat($this.data('gia'));
            const giaBase = parseFloat($('#product-price').data('base'));
            const total = giaBase + giaSize;
            $('#product-price').text(total.toLocaleString('vi-VN') + ' đ');
        }
    });
});
// add-to-cart
$('.add-to-cart').click(function(e){
    e.preventDefault();
    let url = $(this).data('url');
    let size = $('input[name="size"]:checked').val();
    let quantity = parseInt($('input[name="quantity"]').val()) || 1;
    let productId = url.split('/').pop(); // lấy ID sản phẩm từ URL

    // Check size
    if (!size) {
        Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng chọn size sản phẩm trước khi tiếp tục!',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Check quantity hợp lệ
    if (quantity < 1 || quantity > 99) {
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng không hợp lệ',
            text: 'Vui lòng nhập số lượng từ 1 đến 99.',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Kiểm tra số lượng hiện tại trong cart (AJAX)
    $.ajax({
        url: '/cart/check-cart-quantity',
        method: 'GET',
        data: {
            product_id: productId,
            size_id: size
        },
        success: function(response){
            let currentQuantity = parseInt(response.quantity || 0);
            let totalQuantity = currentQuantity + quantity;

            if (totalQuantity > 99) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Vượt quá giới hạn đặt hàng',
                    text: 'Số lượng sản phẩm trong giỏ đã đạt giới hạn (99).',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Nếu hợp lệ => thêm vào giỏ
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    size: size,
                    quantity: quantity,
                    _token: $('input[name="_token"]').val()
                },
                success: function(res){
                    //console.log('cartCount from server:', res.cartCount);
                    if(res.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: res.success,
                            confirmButtonText: 'OK'
                        });
                        $('.cart-count').text(res.cartCount);
                    }
                },
                error: function(xhr){
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: xhr.responseJSON?.error || 'Đã xảy ra lỗi không xác định!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        },
        error: function(){
            Swal.fire({
                icon: 'error',
                title: 'Không thể kiểm tra giỏ hàng',
                text: 'Vui lòng thử lại sau.',
                confirmButtonText: 'OK'
            });
        }
    });
});
// change size
$('.change-size').change(function() {
    const select = $(this);
    const productId = select.attr('name').replace('size_update_', ''); // lấy product_id
    const newSizeId = select.val();
    const oldSizeId = select.data('old-size');

    if (newSizeId === oldSizeId) {
        return;
    }

    $.ajax({
        url: '/cart/update-size',  
        type: 'POST',
        data: {
            product_id: productId,
            old_size_id: oldSizeId,
            new_size_id: newSizeId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('Thành công', response.success, 'success').then(() => {
                    location.reload();
                });
            } else if (response.error) {
                Swal.fire('Lỗi', response.error, 'error');
            }
        
        },
        error: function(xhr) {
            Swal.fire('Lỗi', 'Có lỗi xảy ra, thử lại sau!', 'error');
        }
    });
});
//change quantity
$('.qty-btn').click(function() {
    const isIncrease = $(this).hasClass('increase');
    const input = $(this).siblings('input.update-cart-quantity');
    let current = parseInt(input.val()) || 1;
    const min = parseInt(input.attr('min')) || 1;
    const max = parseInt(input.attr('max')) || 99;
    const productId = input.data('id');
    const sizeId = input.data('size');

    if (isIncrease) {
        if (current >= max) {
            Swal.fire({
                icon: 'warning',
                title: 'Cảnh báo',
                text: 'Số lượng tối đa là 99!'
            });
            return;
        }
        current++;
        input.val(current);
        updateQuantity(productId, sizeId, current);
    } else {
        if (current <= min) {
            Swal.fire({
                title: 'Xoá sản phẩm?',
                text: "Bạn có chắc muốn xoá sản phẩm này khỏi giỏ hàng?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Huỷ'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteProduct(productId, sizeId);
                }
            });
        } else {
            current--;
            input.val(current);
            updateQuantity(productId, sizeId, current);
        }
    }
});
//update quanttity
function updateQuantity(productId, sizeId, quantity) {
    $.ajax({
        url: '/cart/update-quantity',
        method: 'POST',
        data: {
            product_id: productId,
            size_id: sizeId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //console.log('cartCount from server:', response.cartCount);
            $(`#money-${productId}-${sizeId}`).text(response.money_format);
            $('#subtotal').html(response.subtotal_format);
            $('#shipping-fee').html(response.shipping_fee_format);
            $('#total').html(response.total_format);
            $('.cart-count').text(response.cartCount);
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON?.error || 'Cập nhật không thành công.'
            });
        }
    });
}
//delete if quantity = 0
$(document).on('click', '.delete-product', function(e) {
    e.preventDefault();
    let productId = $(this).data('product-id');
    let sizeId = $(this).data('size-id');

    Swal.fire({
        title: 'Bạn có chắc chắn muốn xoá sản phẩm này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xoá',
        cancelButtonText: 'Huỷ',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/cart/delete-product',
                method: 'POST',
                data: {
                    product_id: productId,
                    size_id: sizeId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    //console.log('cartCount from server:', response.cartCount);
                    // Xóa dòng sản phẩm khỏi giao diện
                    $(`#cart-item-${productId}-${sizeId}`).remove();

                    // Cập nhật tổng tiền
                    $('#subtotal').text(response.subtotal_format);
                    $('#shipping-fee').text(response.shipping_fee_format);
                    $('#total').text(response.total_format);
                    $('.cart-count').text(response.cartCount);

                    // Kiểm tra giỏ hàng có còn sản phẩm không
                    if (response.cartCount > 0) {
                        $('.cart-section').show();
                        $('.empty-cart').hide();
                    } else {
                        location.reload();
                    }
                    Swal.fire('Đã xoá!', 'Sản phẩm đã được xoá khỏi giỏ hàng.', 'success');
                },
                error: function() {
                    Swal.fire('Lỗi!', 'Xoá sản phẩm thất bại.', 'error');
                }
            });
        }
    });
});
//delete products in cart
function deleteProduct(productId, sizeId) {
    $.ajax({
        url: '/cart/delete-product',
        method: 'POST',
        data: {
            product_id: productId,
            size_id: sizeId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
        
            //console.log('cartCount from server:', response.cartCount);
            // Xoá hàng khỏi giao diện
            $(`#cart-item-${productId}-${sizeId}`).remove();

            // Cập nhật tổng tiền sau xoá
            $('#subtotal').html(response.subtotal_format);
            $('#shipping-fee').html(response.shipping_fee_format);
            $('#total').html(response.total_format);

            $('.cart-count').text(response.cartCount);

            Swal.fire({
                icon: 'success',
                title: 'Đã xoá',
                text: 'Sản phẩm đã được xoá khỏi giỏ hàng.'
            });
            
            if (response.cartCount > 0) {
                $('.cart-section').show();
                $('.empty-cart').hide();
            } else {
                location.reload();
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể xoá sản phẩm.'
            });
        }
    });
}

