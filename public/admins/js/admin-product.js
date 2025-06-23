document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const productRows = document.querySelectorAll('.product-row');
    const hideButton = document.getElementById('hide-products');
    const showButton = document.getElementById('show-products');
    const deleteButton = document.getElementById('delete-products');

    // Check All
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

    // Click row để toggle checkbox
    productRows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (
                e.target.type === 'checkbox' ||
                e.target.closest('button') ||
                e.target.closest('form')
            ) return;

            const checkbox = this.querySelector('.product-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;

                checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
            }
        });
    });

    // Hành động hàng loạt
    function handleBulkButton(button, action, confirmMessage = null) {
        if (!button) return;
        button.addEventListener('click', () => {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (!selectedIds.length) {
                return Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: 'Vui lòng chọn ít nhất 1 sản phẩm.' });
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

    function performBulkAction(selectedIds, action) {
        Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

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
    }

    // Gắn hành động
    handleBulkButton(hideButton, 'hide');
    handleBulkButton(showButton, 'show');
    handleBulkButton(deleteButton, 'delete', {
        title: 'Bạn có chắc muốn xoá?',
        text: 'Thao tác này không thể hoàn tác!',
        confirmText: 'Xoá'
    });
});
