document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const productRows = document.querySelectorAll('.product-row');

    // Xử lý nút Check All
    checkAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = checkAll.checked);
    });

    // Cập nhật trạng thái nút Check All khi checkbox riêng thay đổi
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!cb.checked) {
                checkAll.checked = false;
            } else {
                const allChecked = Array.from(checkboxes).every(box => box.checked);
                checkAll.checked = allChecked;
            }
        });
    });

    // Toggle checkbox khi click vào hàng (tr)
    productRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Nếu click vào checkbox hoặc nút/form thì không toggle checkbox
            if (
                e.target.type === 'checkbox' ||
                e.target.closest('button') ||
                e.target.closest('form')
            ) return;

            const checkbox = this.querySelector('.product-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;

                // Cập nhật lại trạng thái nút Check All
                if (!checkbox.checked) {
                    checkAll.checked = false;
                } else {
                    const allChecked = Array.from(checkboxes).every(box => box.checked);
                    checkAll.checked = allChecked;
                }
            }
        });
    });
});

// 
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const hideButton = document.getElementById('hide-products');
    const showButton = document.getElementById('show-products');

    // Kiểm tra tồn tại nút "Chọn tất cả"
    if (checkAll) {
        // Sự kiện chọn tất cả
        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
        });

        // Tự động cập nhật trạng thái "Chọn tất cả" nếu người dùng chọn từng checkbox
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
            });
        });
    }

    // Nút ẩn sản phẩm
    if (hideButton) {
        hideButton.addEventListener('click', function () {
            const selectedIds = getSelectedProductIds();
            if (selectedIds.length === 0) {
                showWarning('Vui lòng chọn ít nhất 1 sản phẩm.');
                return;
            }
            performBulkAction(selectedIds, 'hide');
        });
    }

    // Nút hiển thị sản phẩm
    if (showButton) {
        showButton.addEventListener('click', function () {
            const selectedIds = getSelectedProductIds();
            if (selectedIds.length === 0) {
                showWarning('Vui lòng chọn ít nhất 1 sản phẩm.');
                return;
            }
            performBulkAction(selectedIds, 'show');
        });
    }

    // Lấy danh sách ID sản phẩm được chọn
    function getSelectedProductIds() {
        return Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value); // Sử dụng value thay vì data-id
    }

    // Hiển thị cảnh báo
    function showWarning(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo',
            text: message
        });
    }

    // Gửi request AJAX thực hiện hành động ẩn/hiện
    function performBulkAction(selectedIds, action) {
        // Hiển thị loading trước khi gửi AJAX
        Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        // Gửi AJAX sau một khoảng delay nhỏ (nếu bạn muốn rõ hiệu ứng loading)
        setTimeout(() => {
            $.ajax({
                url: '/admin/products/bulk-action',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    selected_products: selectedIds,
                    action: action
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message || 'Đã thực hiện thao tác thành công.'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: response.message || 'Thao tác không thành công.'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi hệ thống',
                        text: 'Không thể thực hiện thao tác. Vui lòng thử lại sau.'
                    });
                }
            });
        }, 800); // Chờ 800ms để hiệu ứng loading hiển thị rõ hơn
    }    
});
