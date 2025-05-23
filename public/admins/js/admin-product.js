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
    const restoreButton = document.getElementById('restore-products');
    const forceDeleteButton = document.getElementById('force-delete-products'); // nút xóa vĩnh viễn

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
            if (selectedIds.length === 0) return showWarning('Vui lòng chọn ít nhất 1 sản phẩm.');

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

    handleBulkButton(hideButton, 'hide');
    handleBulkButton(showButton, 'show');
    handleBulkButton(deleteButton, 'delete', {
        title: 'Bạn có chắc muốn xoá?',
        text: 'Thao tác này không thể hoàn tác!',
        confirmText: 'Xoá'
    });
    handleBulkButton(restoreButton, 'restore', {
        title: 'Khôi phục sản phẩm?',
        text: 'Các sản phẩm đã chọn sẽ được khôi phục!',
        confirmText: 'Khôi phục'
    });
    handleBulkButton(forceDeleteButton, 'force-delete', {
        title: 'Bạn có chắc muốn xóa vĩnh viễn?',
        text: 'Thao tác này không thể hoàn tác!',
        confirmText: 'Xóa vĩnh viễn'
    });

    function getSelectedProductIds() {
        return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
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


// sort-delete
$(document).ready(function() {
    $('.acctive-form').on('submit', function(e) {
      e.preventDefault(); // chặn form submit mặc định
  
      const form = this;
  
      Swal.fire({
        title: 'Bạn có chắc muốn xóa tạm không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Hủy',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
  
          // Delay 800ms trước khi gọi ajax, để loading hiện rõ
          setTimeout(() => {
            $.ajax({
              url: $(form).attr('action'),
              method: $(form).find('input[name="_method"]').val() || $(form).attr('method'),
              data: $(form).serialize(),
              success: function(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Thành công',
                  text: response.message || 'Đã thực hiện thao tác thành công.'
                }).then(() => {
                  location.reload();
                });
              },
              error: function(xhr) {
                Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: xhr.responseJSON?.message || 'Có lỗi xảy ra, thử lại nhé!'
                });
              }
            });
          }, 800); 
        }
      });
    });
});
  