@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
    <style>
        .fas, .far {
            color: #f39c12;  /* Màu vàng cho sao */
            font-size: 18px;  /* Kích thước sao */
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
                    <a href="{{ route('admin.vouchers.list') }}">Vouchers</a>
                </li>
                @if(request()->routeIs('admin.vouchers.list-vouchers-off'))
                <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.vouchers.list-vouchers-off') }}">Vouchers ẩn</a>
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
                                            placeholder="Nhập tên hoặc mã sản phẩm để tìm kiếm..." 
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
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(request()->routeIs('admin.vouchers.list-vouchers-off'))
                                                <li><button type="button" class="dropdown-item" id="show-vouchers">Hiển thị các voucher đã chọn</button></li>
                                            @else
                                                <li><button type="button" class="dropdown-item" id="hide-vouchers">Ẩn các voucher đã chọn</button></li>
                                            @endif
                                            <li><button type="button" class="dropdown-item text-danger" id="delete-vouchers">Xóa các sản phẩm đã chọn</button></li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Bộ lọc --}}
                                <div class="col-6 col-lg-2">
                                    @if(request()->routeIs('admin.vouchers.list-vouchers-off'))
                                        <a href="{{ route('admin.vouchers.list') }}" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-eye-fill me-1"></i>Voucher hiển thị
                                        </a>
                                    @else
                                        <a href="{{ route('admin.vouchers.list-vouchers-off') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-eye-slash-fill me-1"></i> Voucher ẩn
                                        </a>
                                    @endif
                                </div>
                                {{-- Thêm mới --}}
                                <div class="col-6 col-lg-2">
                                    <a href="{{ route('admin.vouchers.form') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-plus"></i> Thêm mới
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        @if($vouchers->isEmpty())
                                            <div class="text-center my-5 py-5">
                                                <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Không có voucher nào trong danh sách</h5>
                                                <p>Hãy thêm voucher mới để bắt đầu quản lý.</p>
                                                <a href="{{ route('admin.vouchers.form') }}" class="btn btn-primary mt-3">
                                                    <i class="fa fa-plus"></i> Thêm voucher mới
                                                </a>
                                            </div>
                                        @else
                                            <table id="add-row" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>Ảnh</th>
                                                        <th>Mã Voucher</th>
                                                        <th>Tên Voucher</th>
                                                        <th>Số lượng</th>
                                                        <th>Bắt đầu</th>
                                                        <th>Kết thúc</th>
                                                        <th>Điều kiện</th>
                                                        <th>Giá trị giảm</th>
                                                        <th>Giảm tối đa</th>
                                                        <th>Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ( $vouchers as $vou )
                                                        <tr role="row" class="odd voucher-row">
                                                            <td>
                                                                <input type="checkbox" class="voucher-checkbox" value="{{ $vou->ma_voucher }}">
                                                            </td> 
                                                            <td>
                                                                <a href="{{ route('admin.vouchers.edit',$vou->ma_voucher) }}" class="" data-bs-toggle="tooltip" title="{{ $vou->ten_voucher }}">
                                                                    <img src="{{ asset('storage/' . ($vou->hinh_anh ?? 'vouchers/voucher-default.png')) }}" alt="{{ $vou->ten_voucher }}" style="width: 60px;">
                                                                </a>
                                                            </td>
                                                            <td>{{ $vou->ma_voucher }}</td>
                                                            <td>{{ $vou->ten_voucher }}</td>
                                                            <td>{{ $vou->so_luong }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($vou->ngay_bat_dau)->format('d/m/Y H:i') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($vou->ngay_ket_thuc)->format('d/m/Y H:i') }}</td>
                                                            <td>Hóa đơn từ {{ number_format($vou->dieu_kien_ap_dung, 0, ',', '.') }} </td>
                                                            <td>
                                                                Giảm
                                                                @if($vou->gia_tri_giam > 100)
                                                                    {{ number_format($vou->gia_tri_giam, 0, ',', '.') }} đ
                                                                @else
                                                                    {{ $vou->gia_tri_giam }} %
                                                                @endif
                                                            </td>
                                                            <td>Tối đa {{ number_format($vou->giam_gia_max, 0, ',', '.') }}</td>
                                                            <td>
                                                                @if ($vou->trang_thai == 1)
                                                                    <span class="badge badge-success">Hiển thị</span>
                                                                @elseif ($vou->trang_thai == 2)
                                                                    <span class="badge badge-danger">Ẩn</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Không xác định</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    @if($vou->trang_thai == 1)
                                                                        <form action="{{ route('admin.vouchers.edit',$vou->ma_voucher) }}" method="GET" class="archive-form">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-icon btn-round btn-info" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>
                                                                        </form>
                                                                        <form action="{{ route('admin.vouchers.on-or-off-voucher',$vou->ma_voucher) }}" method="POST" class="hidden-or-acctive">
                                                                            @csrf    
                                                                            <button type="button" class="btn btn-icon btn-round btn-black voucher-hidden-btn" data-bs-toggle="tooltip" title="ẩn">
                                                                                <i class="fas fa-toggle-off text-white"></i>
                                                                            </button>   
                                                                        </form>
                                                                    @elseif($vou->trang_thai == 2)

                                                                        <form action="{{ route('admin.vouchers.on-or-off-voucher',$vou->ma_voucher) }}" method="POST" class="acctive-form">
                                                                            @csrf
                                                                            <button type="button" class="btn btn-icon btn-round btn-warning voucher-acctive-btn" data-bs-toggle="tooltip" title="Hiển thị">
                                                                                <i class="fas fa-toggle-on text-white"></i>
                                                                            </button>
                                                                        </form>
                                                                    @elseif($vou->trang_thai == 3)
                                                                        <form action="{{ route('admin.vouchers.archive-voucher',$vou->ma_voucher) }}" method="POST" class="acctive-form">
                                                                            @csrf    
                                                                            <button type="button" class="btn btn-icon btn-round btn-success voucher-acctive-btn" data-bs-toggle="tooltip" title="Khôi phục">
                                                                                <i class="fas fa-undo text-white"></i>
                                                                            </button>
                                                                        </form>
                                                                        <form action="{{ route('admin.vouchers.delete', $vou->ma_voucher) }}" method="POST" class="voucher-delete-form">
                                                                            @csrf
                                                                            <button type="button" class="btn btn-icon btn-round btn-danger voucher-delete-btn" data-bs-toggle="tooltip" title="Xóa">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div> 
                                                            </td>
                                                        </tr>
                                                    @endforeach                                              
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                            <ul class="pagination">
                                            {{ $vouchers->links() }} {{-- pagination --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end dataTables_wrapper -->
                        </div> <!-- end table-responsive -->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- <script src="{{ asset('admins/js/alert.js') }}"></script> -->
    <script src="{{ asset('admins/js/admin-voucher.js') }}"></script>
@endpush
