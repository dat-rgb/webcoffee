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

document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const hideButton = document.getElementById('hide-products');
    const showButton = document.getElementById('show-products');
    const deleteButton = document.getElementById('delete-products');

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
            });
        });
    }

    function handleBulkButton(button, action, confirmMessage = null) {
        if (!button) return;

        button.addEventListener('click', () => {
            const selectedIds = getSelectedProductIds();

            if (selectedIds.length === 0) {
                return showWarning('Vui lòng chọn ít nhất 1 sản phẩm.');
            }

            if (confirmMessage) {
                Swal.fire({
                    title: confirmMessage.title,
                    text: confirmMessage.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: confirmMessage.confirmText,
                    cancelButtonText: 'Hủy'
                }).then(result => {
                    if (result.isConfirmed) performBulkAction(selectedIds, action);
                });
            } else {
                performBulkAction(selectedIds, action);
            }
        });
    }

    // Gán các hành động
    handleBulkButton(hideButton, 'hide');
    handleBulkButton(showButton, 'show');
    handleBulkButton(deleteButton, 'delete', {
        title: 'Bạn có chắc muốn xoá?',
        text: 'Thao tác này không thể hoàn tác!',
        confirmText: 'Xoá'
    });

    function getSelectedProductIds() {
        return Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
    }

    function showWarning(message) {
        Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: message });
    }

    function performBulkAction(selectedIds, action) {
        Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

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
                    Swal.fire({
                        icon: response.status === 'success' ? 'success' : 'error',
                        title: response.status === 'success' ? 'Thành công' : 'Lỗi',
                        text: response.message || 'Đã thực hiện thao tác.'
                    }).then(() => {
                        if (response.status === 'success') location.reload();
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi hệ thống',
                        text: 'Không thể thực hiện thao tác. Vui lòng thử lại sau.'
                    });
                }
            });
        }, 800);
    }
});



  