//kiểm tra cart
function checkCartCount() {
    console.log('checkCartCount được gọi');
    $.ajax({
        url: '/cart/count',
        method: 'GET',
        cache: false,
        success: function(response) {
            if (response.cartCount > 0) {
                $('#cart-section').show();        
                $('#empty-cart').hide();         
            } else {
                $('#cart-section').hide();        
                $('#empty-cart').show();         
            }
        },
        error: function() {
            console.error('Lấy số lượng sản phẩm trong giỏ thất bại');
        }
    });
}
$(document).ready(function() {
    checkCartCount();
});
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
//load cart
function loadCart() {
    $.ajax({
        url: '/cart/load',
        type: 'GET',
        success: function(res) {
            if (res.cartCount > 0) {
                $('#cart-section').html(res.html).show();
                $('#empty-cart').hide();
            } else {
                $('#empty-cart').html(res.html).show();
                $('#cart-section').hide();
            }
            $('.cart-count').text(res.cartCount);
        }
    });
}
// ví dụ gọi trong event khác:
$(document).on('click', '.some-button', function() {
    // làm gì đó xong gọi loadCart
    loadCart();
});
// add-to-cart
$('.add-to-cart').click(function(e){
    e.preventDefault();

    let url = $(this).data('url');
    let size = $('input[name="size"]:checked').val();
    let quantity = parseInt($('input[name="quantity"]').val()) || 1;
    let productId = url.split('/').pop(); // lấy ID sản phẩm từ URL

    let store = $('#selectedStoreId').val(); 
   
    if (!store) {
        openStoreModal();
        return;
    }

    //console.log('Selected store:', store); 
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
    if (quantity < 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng quá nhỏ',
            text: 'Số lượng phải ít nhất là 1 nhé.',
            confirmButtonText: 'OK'
        });
        return;
    } else if (quantity > 99) {
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng quá lớn',
            text: 'Số lượng không được vượt quá 99 Hãy liên hệ chúng tôi để đặt hàng với số lượng lớn.',
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
            quantity: quantity,
            _token: $('input[name="_token"]').val()       
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
                    store: store,
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
                        title: 'Không thể thêm vào giỏ hàng!',
                        text: xhr.responseJSON?.error || 'Đã xảy ra lỗi không xác định!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        },
        error: function(xhr) {
            let message = 'Đã xảy ra lỗi không xác định!';
            if(xhr.responseJSON && xhr.responseJSON.error){
                message = xhr.responseJSON.error;
            } else if(xhr.responseText){
                message = xhr.responseText;
            }
            Swal.fire({
                icon: 'warning',
                title: 'Thao tác không thực hiện',
                text: message,
                confirmButtonText: 'OK'
            });
        }
        
    });
});
// change size
$(document).on('change', '.change-size', function() {
    console.log('change-size triggered');
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
                    // load lại giỏ hàng
                    loadCart();
                });
            } else if (response.error) {
                Swal.fire('Không thể cập nhật size', response.error, 'error').then(()=>{
                    loadCart();
                });
            }
        },
        error: function(xhr) {
            // đây mới là nơi nhận lỗi trả về từ backend dạng 400
            let res = xhr.responseJSON;
            if (res && res.error) {
                Swal.fire('Thao tác không thực hiện', res.error, 'warning').then(()=>{
                    loadCart();
                });
            } else {
                Swal.fire('Lỗi', 'Đã xảy ra lỗi không xác định.', 'error').then(()=>{
                    loadCart();
                });
            }
        }
    });
});
//change quantity
$(document).on('click', '.qty-btn', function() {
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
            $('.label-cart-count').text(response.cartCount);
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: xhr.responseJSON?.error || 'Cập nhật không thành công.'
              }).then(() => {
                loadCart();
            });
        }
    });
}
// Hàm gọi xóa sản phẩm
function deleteProduct(productId, sizeId, showConfirm = true) {
    if (showConfirm) {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xoá sản phẩm này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                deleteProductAjax(productId, sizeId);
            }
        });
    } else {
        // Xóa không hỏi confirm (dùng khi quantity = 0)
        deleteProductAjax(productId, sizeId);
    }
}
// Hàm AJAX xóa sản phẩm
function deleteProductAjax(productId, sizeId) {
    $.ajax({
        url: '/cart/delete-product',
        method: 'POST',
        data: {
            product_id: productId,
            size_id: sizeId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(`#cart-item-${productId}-${sizeId}`).remove();

            $('#subtotal').text(response.subtotal_format);
            $('#shipping-fee').text(response.shipping_fee_format);
            $('#total').text(response.total_format);
            $('.cart-count').text(response.cartCount);

            if (response.cartCount > 0) {
                $('#cart-section').show();
                $('#empty-cart').hide();
            } else {
                $('#cart-section').hide();
                $('#empty-cart').show();
            }
            
            Swal.fire('Đã xoá!', 'Sản phẩm đã được xoá khỏi giỏ hàng.', 'success');
        },
        error: function() {
            Swal.fire('Lỗi!', 'Xoá sản phẩm thất bại.', 'error');
        }
    });
}
// Xử lý sự kiện click nút xóa
$(document).on('click', '.delete-product', function(e) {
    e.preventDefault();
    let productId = $(this).data('product-id');
    let sizeId = $(this).data('size-id');
    deleteProduct(productId, sizeId, true);
});
