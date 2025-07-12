@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
    .fas, .far {
        color: #f39c12;  /* Màu vàng cho sao */
        font-size: 18px;  /* Kích thước sao */
    }
    th {
        white-space: nowrap;
        font-size: 14px;
        padding: 8px 10px;
        text-align: center;
    }
</style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">{{ $subtitle }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.list') }}">Đơn hàng</a>
                </li>

                @if(request('ma_cua_hang'))
                    @php
                        $cuahangSelected = $cuaHang->firstWhere('ma_cua_hang', request('ma_cua_hang'));
                    @endphp
                    @if($cuahangSelected)
                        <li class="separator">
                            <i class="icon-arrow-right"></i>
                        </li>
                        <li class="nav-item">
                            <span>{{ $cuahangSelected->ten_cua_hang }}</span>
                        </li>
                    @endif
                @endif
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                            <div class="col-12 col-lg-6"> 
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        id="searchInput"
                                        name="search" 
                                        class="form-control" 
                                        placeholder="Nhập mã đơn hàng hoặc tên khách hàng..." 
                                        autocomplete="off"
                                    >
                                     <button type="button" class="btn btn-outline-secondary" id="searchBtn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                  
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <select name="ma_cua_hang" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Chọn cửa hàng --</option>
                                    @foreach($cuaHang as $ch)
                                        <option value="{{ $ch->ma_cua_hang }}" {{ request('ma_cua_hang') == $ch->ma_cua_hang ? 'selected' : '' }}>
                                            {{ $ch->ten_cua_hang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    @if(!request()->filled('ma_cua_hang'))
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chọn cửa hàng để xem đơn hàng.</h5>
                        </div>
                    @else
                        @if($orders->isEmpty())
                            <div class="text-center my-5 py-5">
                                <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có đơn hàng trong danh sách</h5>
                            </div>
                        @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Mã HĐ</th>
                                            <th>Ngày lập HĐ</th>
                                            <th>Thông tin khách hàng</th>
                                            <th>
                                                Trạng thái đơn hàng<br>
                                                <select id="trang_thai" class="form-select form-select-sm mt-1">
                                                    <option value="">Tất cả</option>
                                                    <option value="0">Chờ xác nhận</option>
                                                    <option value="1">Đã xác nhận</option>
                                                    <option value="2">Hoàn tất đơn hàng</option>
                                                    <option value="3">Đang giao/Chờ nhận hàng</option>
                                                    <option value="4">Đã nhận</option>
                                                    <option value="5">Đã hủy</option>
                                                </select>
                                            </th>
                                            <th>
                                                Trạng thái thanh toán<br>
                                                <select id="tt_thanh_toan" class="form-select form-select-sm mt-1">
                                                    <option value="">Tất cả</option>
                                                    <option value="0">Chưa thanh toán</option>
                                                    <option value="1">Đã thanh toán</option>
                                                    <option value="2">Đang hoàn tiền</option>
                                                    <option value="3">Đã hoàn tiền</option>
                                                </select>
                                            </th>
                                            <th>
                                                Phương thức thanh toán<br>
                                                <select id="pt_thanh_toan" class="form-select form-select-sm mt-1">
                                                    <option value="">Tất cả</option>
                                                    <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                                                    <option value="NAPAS247">Chuyển khoản</option>
                                                </select>
                                            </th>
                                        </tr>
                                    </thead>    
                                    <tbody id="order-tbody">
                                        @include('admins.orders._order_tbody', ['orders' => $orders])
                                        <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true"> 
                                            <div class="modal-dialog modal-lg"> 
                                                <div class="modal-content">
                                                    <div id="order-detail-content"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @endif
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
function bindOrderStatusEvents() {
    document.querySelectorAll('.order-status-select').forEach(select => {
        select.addEventListener('change', function () {
            const orderId = this.dataset.orderId;
            const newStatus = parseInt(this.value);
            const pt_nhan_hang = this.dataset.ptNhanHang;

            Swal.fire({
                title: 'Xác nhận thay đổi trạng thái?',
                text: 'Bạn có chắc muốn cập nhật trạng thái đơn hàng này không?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
            }).then(result => {
                if (result.isConfirmed) {
                    if (newStatus === 3) {
                        if (pt_nhan_hang === 'pickup') {
                            updateOrderStatus(orderId, newStatus, {}, this);
                        } else {
                            showDeliverInfoModal(orderId, newStatus, this);
                        }
                    } else if (newStatus === 5) {
                        showCancelReasonModal(orderId, newStatus, this);
                    } else {
                        updateOrderStatus(orderId, newStatus, {}, this);
                    }
                } else {
                    this.value = this.getAttribute('data-previous');
                }
            });
        });
    });
}

function updateOrderStatus(orderId, status, extraData = {}, selectElement = null) {
    Swal.fire({
        title: 'Đang cập nhật...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    fetch('/admin/orders/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            order_id: orderId,
            status: status,
            ...extraData,
        }),
    })
    .then(async res => {
        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(errorText);
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: 'Cập nhật trạng thái đơn hàng thành công!',
            }).then(() => window.location.reload());
        } else {
            if (selectElement) selectElement.value = selectElement.getAttribute('data-previous');
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: data.message || 'Cập nhật thất bại!',
            });
        }
    })
    .catch(async (err) => {
        const errorText = await err?.response?.text?.() ?? err.message;
        console.error('Lỗi chi tiết:', errorText);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi Server',
            html: `<pre>${errorText}</pre>`,
        });
    })
    .finally(() => {
        if (selectElement) {
            selectElement.setAttribute('data-previous', status);
        }
    });
}

function showDeliverInfoModal(orderId, newStatus, selectElement) {
    Swal.fire({
        title: 'Nhập thông tin giao hàng',
        html: `
            <p><strong>Mã đơn hàng:</strong> ${orderId}</p>
            <input type="text" id="shipperName" class="swal2-input" placeholder="Họ tên shipper">
            <input type="text" id="shipperPhone" class="swal2-input" placeholder="SĐT shipper">
            <textarea id="note" class="swal2-textarea" placeholder="Ghi chú (nếu có)"></textarea>
        `,
        confirmButtonText: 'Xác nhận',
        focusConfirm: false,
        preConfirm: () => {
            const name = document.getElementById('shipperName').value.trim();
            const phone = document.getElementById('shipperPhone').value.trim();
            const note = document.getElementById('note').value.trim();

            if (!name || !phone) {
                Swal.showValidationMessage(`Vui lòng nhập đầy đủ thông tin`);
                return false;
            }
            if (name.length < 2 || name.length > 255) {
                Swal.showValidationMessage(`Tên phải từ 2 đến 255 ký tự`);
                return false;
            }
            if (!/^0\d{9}$/.test(phone)) {
                Swal.showValidationMessage(`Số điện thoại phải đủ 10 số và bắt đầu bằng số 0`);
                return false;
            }
            if (note.length > 255) {
                Swal.showValidationMessage(`Ghi chú không vượt quá 255 ký tự`);
                return false;
            }

            return { name, phone, note };
        }
    }).then(result => {
        if (result.isConfirmed) {
            updateOrderStatus(orderId, newStatus, {
                shipper_name: result.value.name,
                shipper_phone: result.value.phone,
                note: result.value.note,
            }, selectElement);
        } else {
            if (selectElement) selectElement.value = selectElement.getAttribute('data-previous');
        }
    });
}

function showCancelReasonModal(orderId, newStatus, selectElement) {
    Swal.fire({
        title: 'Lý do hủy đơn hàng',
        html: `
            <p><strong>Mã đơn hàng:</strong> ${orderId}</p>
            <textarea id="cancelReason" class="swal2-textarea" placeholder="Nhập lý do hủy đơn hàng"></textarea>
        `,
        confirmButtonText: 'Xác nhận',
        focusConfirm: false,
        preConfirm: () => {
            const reason = document.getElementById('cancelReason').value.trim();
            if (!reason) {
                Swal.showValidationMessage(`Vui lòng nhập lý do hủy`);
            }
            return { reason };
        }
    }).then(result => {
        if (result.isConfirmed) {
            updateOrderStatus(orderId, newStatus, {
                cancel_reason: result.value.reason,
            }, selectElement);
        } else {
            if (selectElement) selectElement.value = selectElement.getAttribute('data-previous');
        }
    });
}

function showRefundModal(orderId, transaction) {
    const accountName = transaction.counter_account_name || 'Không rõ';
    const accountNumber = transaction.counter_account_number || 'Không rõ';
    const bankId = transaction.counter_account_bank_id || '';

    fetch("/data/vietnam-banks.json")
        .then((res) => res.json())
        .then((banks) => {
            const bank = banks.find(b => String(b.atmBin) === String(bankId));
            const bankName = bank 
                ? `${bank.vn_name} - ${bank.shortName}` 
                : `Mã ngân hàng: ${bankId}`;

            Swal.fire({
                title: 'Xử lý hoàn tiền',
                html: `
                    <p><strong>Mã đơn hàng:</strong> ${orderId}</p>
                    <p><strong>Chủ tài khoản:</strong> ${accountName}</p>
                    <p><strong>Số tài khoản:</strong> ${accountNumber}</p>
                    <p><strong>Ngân hàng:</strong> ${bankName}</p>
                `,
                showCancelButton: true,
                confirmButtonText: 'Xác nhận hoàn tiền',
                cancelButtonText: 'Hủy',
                focusConfirm: false,
            }).then(result => {
                if (result.isConfirmed) {
                    processRefund(orderId);
                }
            });
        })
        .catch((err) => {
            console.error("Lỗi khi lấy danh sách ngân hàng:", err);
            Swal.fire('Lỗi', 'Không thể lấy thông tin ngân hàng. Vui lòng thử lại sau.', 'error');
        });
}

function processRefund(orderId) {
    fetch(`/admin/orders/refund/${orderId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Thành công', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Lỗi', data.message, 'error');
        }
    })
    .catch(err => {
        console.error('Lỗi xử lý hoàn tiền:', err);
        Swal.fire('Lỗi', 'Đã có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
    });
}

$(document).ready(function () {
    bindOrderStatusEvents(); // chạy khi DOM ready

    const maCuaHang = "{{ request('ma_cua_hang') }}";

    function fetchOrders() {
        $.ajax({
            url: "{{ route('admin.orders.filter') }}",
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
                bindOrderStatusEvents(); // gắn lại sự kiện sau khi load ajax
            },
            error: function () {
                alert('Có lỗi xảy ra khi tìm kiếm hoặc lọc đơn hàng.');
            }
        });
    }

    $('#pt_thanh_toan, #tt_thanh_toan, #trang_thai').on('change', fetchOrders);
    $('#searchInput').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            fetchOrders();
        }
    });
    $('#searchBtn').on('click', function () {
        fetchOrders();
    });
});

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

    fetch(`/admin/orders/${orderId}/detail`)
        .then(response => response.text())
        .then(html => {
            modalBody.html(html);
        })
        .catch(() => {
            modalBody.html(`<p class="text-danger">Lỗi tải dữ liệu chi tiết!</p>`);
        });
});
</script>
@endpush
