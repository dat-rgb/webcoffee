//kiểm tra cart
function checkCartCount() {
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

// add-to-cart
$('.add-to-cart').click(function(e){
    e.preventDefault();

    let url = $(this).data('url');
    let size = $('input[name="size"]:checked').val();
    let quantity = parseInt($('input[name="quantity"]').val()) || 1;
    let store = $('#selectedStoreId').val(); 
    let loaiSanPham = $('#loai_san_pham').val();

    if (!store) {
        openStoreModal();
        return;
    }

    // Nếu là loại pha chế (0) thì phải bắt buộc chọn size
    if (loaiSanPham == 0 && !size) {
        Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng chọn size sản phẩm trước khi tiếp tục!',
            confirmButtonText: 'OK'
        });
        return;
    }

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
            text: 'Không được vượt quá 99. Hãy liên hệ để đặt hàng số lượng lớn!',
            confirmButtonText: 'OK'
        });
        return;
    }

    $.ajax({
        url: '/cart/check-cart-quantity',
        method: 'GET',
        data: {
            product_id: url.split('/').pop(),
            size_id: size, 
            quantity: quantity,
            _token: $('input[name="_token"]').val()
        },
        success: function(response){
            let totalQuantity = parseInt(response.quantity || 0) + quantity;

            if (totalQuantity > 99) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Vượt quá giới hạn đặt hàng',
                    text: 'Tối đa 99 sản phẩm trong giỏ hàng!',
                    confirmButtonText: 'OK'
                });
                return;
            }

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
                    if(res.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Thêm vào giỏ thành công!',
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
            Swal.fire({
                icon: 'warning',
                title: 'Thao tác không thực hiện',
                text: xhr.responseJSON?.error || xhr.responseText || 'Lỗi không xác định!',
                confirmButtonText: 'OK'
            });
        }
    });
});

// buy-now
$('.buy-now').click(function(e){
    e.preventDefault();

    let url = $(this).data('url');
    let size = $('input[name="size"]:checked').val();
    let quantity = parseInt($('input[name="quantity"]').val()) || 1;
    let productId = url.split('/').pop();
    let store = $('#selectedStoreId').val(); 
    let loaiSanPham = $('#loai_san_pham').val(); // lấy loại sản phẩm

    if (!store) {
        openStoreModal();
        return;
    }

    // Nếu là loại pha chế thì bắt buộc chọn size
    if (loaiSanPham == 0 && !size) {
        Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng chọn size sản phẩm trước khi tiếp tục!',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (quantity < 1 || quantity > 99) {
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng không hợp lệ',
            text: 'Số lượng phải từ 1 đến 99.',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Check số lượng hiện tại trong cart
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
                    title: 'Giới hạn số lượng',
                    text: 'Tổng số lượng vượt quá 99 sản phẩm cho mặt hàng này.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Gửi request thêm giỏ + chuyển hướng
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
                    if(res.success){
                        window.location.href = '/cart/check-out'; 
                    }
                },
                error: function(xhr){
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể mua ngay!',
                        text: xhr.responseJSON?.error || 'Đã có lỗi xảy ra!',
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
    const productId = select.attr('name').replace('size_update_', '');
    const newSizeId = select.val();
    const oldSizeId = select.data('old-size');
    const loaiSanPham = select.data('loai'); // 0: có size, 1: đóng gói

    // Nếu sản phẩm là đóng gói (loai_san_pham == 1) => không cho đổi size
    if (loaiSanPham == 1) {
        Swal.fire('Không thể đổi size', 'Sản phẩm này không có size để thay đổi.', 'warning');
        select.val(oldSizeId); // Reset lại về size cũ
        return;
    }

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
                    loadCart();
                });
            } else if (response.error) {
                Swal.fire('Không thể cập nhật size', response.error, 'error').then(()=>{
                    loadCart();
                });
            }
        },
        error: function(xhr) {
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
$(document).on('click', '.qty-btn', function () {
    const isIncrease = $(this).hasClass('increase');
    const input = $(this).siblings('input.update-cart-quantity');
    let current = parseInt(input.val()) || 1;
    const min = parseInt(input.attr('min')) || 1;
    const max = parseInt(input.attr('max')) || 99;
    const productId = input.data('id');
    const sizeId = input.data('size');
    const loaiSanPham = parseInt(input.data('loai')) || 0; // 0: có size, 1: đóng gói

    // Nếu là sản phẩm đóng gói thì bỏ size_id
    const finalSizeId = (loaiSanPham === 1) ? null : sizeId;

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
        updateQuantity(productId, finalSizeId, current);
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
                    deleteProduct(productId, finalSizeId);
                }
            });
        } else {
            current--;
            input.val(current);
            updateQuantity(productId, finalSizeId, current);
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
function deleteProduct(productId, sizeId, loai, showConfirm = true) {
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
                deleteProductAjax(productId, sizeId, loai);
            }
        });
    } else {
        deleteProductAjax(productId, sizeId, loai);
    }
}

// Hàm AJAX xóa sản phẩm
function deleteProductAjax(productId, sizeId, loai) {
    $.ajax({
        url: '/cart/delete-product',
        method: 'POST',
        data: {
            product_id: productId,
            size_id: sizeId,
            loai_san_pham: loai,
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
    const productId = $(this).data('product-id');
    const sizeId = $(this).data('size-id');
    const loai = $(this).data('loai');
    deleteProduct(productId, sizeId, loai, true);
});

