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
    let productId = url.split('/').pop(); // lấy id từ URL
    let size = $('input[name="size"]:checked').val();
    let quantity = $('input[name="quantity"]').val() || 1;

    let checkQuantityItemInCart = 0;
    if (!size) {
        e.preventDefault(); // chặn submit hay hành động tiếp theo
        Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng chọn size sản phẩm trước khi tiếp tục!',
            confirmButtonText: 'OK'
        });
        return;
    }
    if (quantity < 1) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng không hợp lệ',
            text: 'Vui lòng nhập số lượng sản phẩm hợp lệ.',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (quantity > 99) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Vượt quá giới hạn đặt hàng',
            text: 'Bạn đang cố đặt số lượng vượt giới hạn. Vui lòng liên hệ chúng tôi để đặt hàng số lượng lớn.',
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
            _token: $('input[name="_token"]').val()
        },
        success: function(response){
            if(response.success){
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: response.success,
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr){
            alert(xhr.responseJSON.error || 'Lỗi rồi!');
        }
    });
});
