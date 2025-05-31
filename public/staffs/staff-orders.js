$(document).ready(function () {
    const maCuaHang = "{{ request('ma_cua_hang') }}";

    function fetchOrders() {
        $.ajax({
            url: "{{ route('staff.orders.filter') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                ma_cua_hang: maCuaHang,
                pt_thanh_toan: $('#pt_thanh_toan').val(),
                tt_thanh_toan: $('#tt_thanh_toan').val(),
                trang_thai: $('#trang_thai').val(),
                search: $('#searchInput').val()
            },
            success: function (res) {
                $('#order-tbody').html(res);
            },
            error: function () {
                alert('Có lỗi xảy ra khi tìm kiếm hoặc lọc đơn hàng.');
            }
        });
    }

    // Bắt sự kiện lọc
    $('#pt_thanh_toan, #tt_thanh_toan, #trang_thai').on('change', fetchOrders);

    // Bắt sự kiện Enter
    $('#searchInput').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            fetchOrders();
        }
    });

    // Bắt sự kiện click nút tìm
    $('#searchBtn').on('click', function () {
        fetchOrders();
    });

});

// order-detail-btn
$(document).on('click', '.order-detail-btn', function () {
    const orderId = $(this).data('id');
    const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
    const modalBody = $('#order-detail-content');

    modalBody.html(`<div class="text-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>`);

    modal.show();

    fetch(`/staff/orders/${orderId}/detail`)
        .then(response => response.text())
        .then(html => {
            modalBody.html(html);
        })
        .catch(() => {
            modalBody.html(`<p class="text-danger">Lỗi tải dữ liệu chi tiết!</p>`);
        });
    });

