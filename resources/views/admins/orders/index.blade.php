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
    #add-row th:nth-child(7),
    #add-row td:nth-child(7) {
    min-width: 220px; /* Rộng ngang từ trái qua phải */
    text-align: left;
    white-space: normal;
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
                @if(request()->routeIs('admin.products.hidden.list'))
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">xxx</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="row g-2 align-items-center">
                                {{-- Tìm kiếm --}}
                                <div class="col-12 col-lg-4">
                                    <div class="input-group">
                                        <input 
                                            type="text" 
                                            name="search" 
                                            class="form-control" 
                                            placeholder="Nhập mã đơn hàng để tìm kiếm..." 
                                            value="{{ request('search') }}" 
                                            autocomplete="off"
                                        >
                                        <button type="submit" class="input-group-text bg-white">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Thao tác nhanh --}}
                                <div class="col-6 col-lg-2">
                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            Cửa hàng 
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><button type="button" class="dropdown-item" id="show-products">Tên cửa hàng hoạt động 1</button></li>
                                        
                                            <li><button type="button" class="dropdown-item" id="hide-products">Tên cửa hàng hoạt động 2</button></li>
                                        
                                        </ul>
                                    </div>
                                </div>

                                {{-- Bộ lọc --}}
                                <div class="col-6 col-lg-2">
                                    @if(request()->routeIs('admin.products.hidden.list'))
                                        <a href="{{ route('admin.products.list') }}" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-eye-fill me-1"></i> Sản phẩm hiển thị
                                        </a>
                                    @else
                                        <a href="{{ route('admin.products.hidden.list') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-eye-slash-fill me-1"></i> Sản phẩm ẩn
                                        </a>
                                    @endif
                                </div>
                                {{-- Thêm mới --}}
                                <div class="col-6 col-lg-2">
                                    <a href="{{ route('admin.products.form') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-plus"></i> Thêm mới
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if($orders->isEmpty())
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có sản phẩm nào trong danh sách</h5>
                            <p>Hãy thêm sản phẩm mới để bắt đầu quản lý kho hàng.</p>
                            <a href="{{ route('admin.products.form') }}" class="btn btn-primary mt-3">
                                <i class="fa fa-plus"></i> Thêm sản phẩm mới
                            </a>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="add-row" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">
                                                <thead class="align-middle text-center">
                                                    <tr class="bg-light">
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>Mã HĐ</th>
                                                        <th>Ngày lập HĐ</th>
                                                        <th>Khách hàng</th>
                                                        <th>Phí ship</th>
                                                        <th>Tổng tiền</th>
                                                        <th>PT. nhận hàng</th>
                                                        <th>
                                                            PT. thanh toán<br>
                                                            <form method="POST" class="mt-1">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="pt_thanh_toan" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                    <option value="">Tất cả</option>
                                                                    <option value="COD">Tiền mặt</option>
                                                                    <option value="NAPAS247">Chuyển khoản</option>
                                                                </select>
                                                            </form>
                                                        </th>
                                                        <th>
                                                            TT. thanh toán<br>
                                                            <form method="POST" class="mt-1">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="tt_thanh_toan" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                    <option value="">Tất cả</option>
                                                                    <option value="0">Chờ thanh toán</option>
                                                                    <option value="1">Đã thanh toán</option>
                                                                </select>
                                                            </form>
                                                        </th>
                                                        <th>
                                                            Trạng thái<br>
                                                            <form method="POST" class="mt-1">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                    <option value="">Tất cả</option>
                                                                    <option value="0">Chờ xác nhận</option>
                                                                    <option value="1">Đã xác nhận</option>
                                                                    <option value="2">Đang chuẩn bị</option>
                                                                    <option value="3">Hoàn tất món</option>
                                                                    <option value="4">Đang giao</option>
                                                                    <option value="5">Đã nhận</option>
                                                                </select>
                                                            </form>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ( $orders as $order )
                                                        <tr role="" class="product-row">
                                                            <td>
                                                                <input type="checkbox" class="product-checkbox" value="{{ $order->ma_hoa_don }}">
                                                            </td> 
                                                            <td>
                                                                <a href="#" class="" data-bs-toggle="tooltip" title="Chi tiết">{{ $order->ma_hoa_don }}</a>
                                                            </td>
                                                            <td>{{ $order->ngay_lap_hoa_don }}</td>
                                                            <td>{{ $order->khachHang->ho_ten_khach_hang ?? 'Guest' }}</td>
                                                            <td>{{ number_format($order->tien_ship) }}</td>
                                                            <td>{{ number_format($order->tong_tien, 0, ',', '.') }}</td>
                                                            <td>
                                                                @if ($order->phuong_thuc_nhan_hang === 'pickup')
                                                                    <span>Tại quán</span>
                                                                @else
                                                                    <span>Giao hàng đến: {{ $order->dia_chi }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($order->phuong_thuc_thanh_toan === 'COD')
                                                                    <span>Tiền mặt</span>
                                                                @elseif($order->phuong_thuc_thanh_toan === 'NAPAS247')
                                                                    <span>Chuyển khoảng</span>
                                                                @endif  
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $payClass = $order->trang_thai_thanh_toan == 1 ? 'badge bg-success' : 'badge bg-warning text-dark';
                                                                @endphp
                                                                <span class="{{ $payClass }}">
                                                                    @if ($order->trang_thai_thanh_toan == 0)
                                                                    Chờ thanh toán
                                                                    @else
                                                                    Đã thanh toán
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <form action="" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                        <option value="0" {{ $order->trang_thai == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                                                                        <option value="1" {{ $order->trang_thai == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                                                                        <option value="2" {{ $order->trang_thai == 2 ? 'selected' : '' }}>Đang chuẩn bị</option>
                                                                        <option value="3" {{ $order->trang_thai == 3 ? 'selected' : '' }}>Hoàn tất món</option>
                                                                        <option value="4" {{ $order->trang_thai == 4 ? 'selected' : '' }}>Đang giao</option>
                                                                        <option value="5" {{ $order->trang_thai == 5 ? 'selected' : '' }}>Đã nhận</option>
                                                                    </select>
                                                                </form>
                                                            </td>

                                                            <td>
                                                                <div class="form-button-action">
                                                                    <form action="{{ route('admin.product.hidde-or-acctive', $order->ma_hoa_don) }}" method="POST" class="hidden-or-acctive">
                                                                        @csrf    
                                                                        <button type="button" class="btn btn-icon btn-round btn-black hidden-btn" data-bs-toggle="tooltip" title="Ẩn">
                                                                            <i class="fas fa-toggle-off text-white"></i>
                                                                        </button>   
                                                                    </form> 
                                                                    <form action="{{ route('admin.product.hidde-or-acctive', $order->ma_hoa_don) }}" method="POST" class="acctive-form">
                                                                        @csrf
                                                                        <button type="button" class="btn btn-icon btn-round btn-warning acctive-btn" data-bs-toggle="tooltip" title="Hiển thị">
                                                                            <i class="fas fa-toggle-on text-white"></i>
                                                                        </button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-icon btn-round btn-danger" data-bs-toggle="tooltip" title="Xóa">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                                <ul class="pagination">
                                                    

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end dataTables_wrapper -->
                            </div> <!-- end table-responsive -->
                        </div> <!-- end card-body -->   
                    @endif
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
    <script src="{{ asset('admins/js/admin-product.js') }}"></script>
@endpush
