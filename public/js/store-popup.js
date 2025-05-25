function openStoreModal() {
    $('#store-modal').modal('show');
}

function closeStoreModal() {
    $('#store-modal').modal('hide');
}

function selectStore(storeId) {
    $.ajax({
        url: '/select-store',
        type: 'POST',
        data: { store_id: storeId },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if (data.success) {
                closeStoreModal();
                $('#store-btn').html('<i class="fas fa-store-alt"></i> ' + data.store_name);
                $('#store-btn').css({
                    'background-color': '#F28123',
                    'color': '#fff',
                    'border-radius': '20px',
                    'padding': '6px 16px'
                });
                $('#selectedStoreId').val(data.store_id);
                location.reload();
            } else {
                // ❌ lỗi do thiếu hàng hoặc cửa hàng
                Swal.fire({
                    icon: 'warning',
                    title: 'Không thể chọn cửa hàng',
                    text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.',
                    confirmButtonColor: '#d33',
                });
            }
        },
        error: function() {
            // ❌ lỗi kết nối server
            Swal.fire({
                icon: 'error',
                title: 'Lỗi kết nối',
                text: 'Không thể kết nối tới máy chủ. Vui lòng thử lại sau.',
                confirmButtonColor: '#d33',
            });
        }
    });
}

  