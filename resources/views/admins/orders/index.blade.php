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
                    <a href="{{ route('admin') }}">
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
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Mã HĐ</th>
                                                <th>Ngày lập HĐ</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>
                                                    PT. thanh toán<br>
                                                    <select id="pt_thanh_toan" class="form-select form-select-sm mt-1">
                                                        <option value="">Tất cả</option>
                                                        <option value="COD">Tiền mặt</option>
                                                        <option value="NAPAS247">Chuyển khoản</option>
                                                    </select>
                                                </th>
                                                <th>
                                                    TT. thanh toán<br>
                                                    <select id="tt_thanh_toan" class="form-select form-select-sm mt-1">
                                                        <option value="">Tất cả</option>
                                                        <option value="0">Chờ thanh toán</option>
                                                        <option value="1">Đã thanh toán</option>
                                                    </select>
                                                </th>
                                                <th>
                                                    Trạng thái<br>
                                                    <select id="trang_thai" class="form-select form-select-sm mt-1">
                                                        <option value="">Tất cả</option>
                                                        <option value="0">Chờ xác nhận</option>
                                                        <option value="1">Đã xác nhận</option>
                                                        <option value="2">Hoàn tất đơn hàng</option>
                                                        <option value="3">Đang giao</option>
                                                        <option value="4">Đã nhận</option>
                                                        <option value="5">Đã hủy</option>
                                                    </select>
                                                </th>
                                            </tr>
                                        </thead>    
                                        <tbody id="order-tbody">
                                            @include('admins.orders._order_tbody', ['orders' => $orders])
                                            <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
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
       $(document).ready(function () {
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
