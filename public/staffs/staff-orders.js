$(function() {
    function filterOrders() {
        let ptThanhToan = $('#pt_thanh_toan').val();
        let trangThai = $('#trang_thai').val();
        let search = $('#searchInput').val();

        $.ajax({
            url: "{{ route('staff.orders.filter') }}", // thay bằng route đúng của bạn
            type: 'GET',
            data: {
                pt_thanh_toan: ptThanhToan,
                trang_thai: trangThai,
                search: search
            },
            success: function(res) {
                $('#order-tbody').html(res);
            },
            error: function() {
                alert('Lấy dữ liệu thất bại, thử lại nhé!');
            }
        });
    }

    // Lọc khi đổi select trong thead
    $('#pt_thanh_toan, #trang_thai').change(function() {
        filterOrders();
    });

    // Lọc khi bấm nút search hoặc enter trong input search
    $('#searchBtn').click(function() {
        filterOrders();
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which == 13) { // Enter key
            e.preventDefault(); // ngăn form submit reload trang
            filterOrders();
        }
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

