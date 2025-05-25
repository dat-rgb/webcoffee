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
                    <a href="#">Đơn hàng</a>
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
                                            Thao tác 
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(request()->routeIs('admin.products.hidden.list'))
                                                <li><button type="button" class="dropdown-item" id="show-products">Hiển thị các sản phẩm đã chọn</button></li>
                                            @else
                                                <li><button type="button" class="dropdown-item" id="hide-products">Ẩn các sản phẩm đã chọn</button></li>
                                            @endif
                                            <li><button type="button" class="dropdown-item text-danger" id="delete-products">Xóa các sản phẩm đã chọn</button></li>
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
                    @if($products->isEmpty())
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
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>Mã HĐ</th>
                                                        <th>Ngày lặp HĐ</th>
                                                        <th>Khách hàng</th>
                                                        <th>Khuyến mãi</th>
                                                        <th>Phí ship</th>
                                                        <th>Tổng tiền</th>
                                                        <th>TT.thanh toán</th>
                                                        <th>PT.thanh toán</th>
                                                        <th>T.thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ( $products as $pro )
                                                        <tr role="" class="product-row">
                                                            <td>
                                                                <input type="checkbox" class="product-checkbox" value="{{ $pro->ma_san_pham }}">
                                                            </td> 
                                                            <td>
                                                                <a href="{{ route('admin.product.edit.form',$pro->ma_san_pham) }}" class="" data-bs-toggle="tooltip" title="{{ $pro->ten_san_pham }}">
                                                                    <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="{{ $pro->ten_san_pham }}" width="80">
                                                                </a>                                                            
                                                            </td>
                                                            <td>{{ $pro->ma_san_pham }}</td>
                                                            <td>{{ $pro->ten_san_pham }}</td>
                                                            <td>{{ $pro->danhMuc->ten_danh_muc }}</td>
                                                            <td>{{ number_format($pro->gia, 0, ',', '.') }}</td>
                                                            @php
                                                                $sizes = $sizesMap[$pro->ma_san_pham] ?? collect(); // dùng collect() để đảm bảo có thể count()
                                                            @endphp
                                                            <td style="min-width: 150px; max-width: 200px; width: 100px;">
                                                                @if ($sizes->count())
                                                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                                                        @foreach ($sizes as $size)
                                                                            <span style="background: #e0f7fa; color: #00796b;
                                                                                        padding: 4px 8px; border-radius: 6px;
                                                                                        font-size: 11px; font-weight: 500;
                                                                                        white-space: nowrap;">
                                                                                {{ $size->ten_size }}
                                                                            </span>
                                                                        @endforeach
                                                                        <a href="{{ route('admin.products.ingredients.show',$pro->ma_san_pham) }}" class="" data-bs-toggle="tooltip" title="Xem chi tiết thành phần">chi tiết</a>
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">
                                                                        <a href="{{ route('admin.products.ingredients.form', $pro->slug) }} " data-bs-toggle="tooltip" title="Thêm thành phần sản phẩm">Thêm size.</a>
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($pro->trang_thai == 1)
                                                                    <span class="badge badge-success">Hiển thị</span>
                                                                @elseif ($pro->trang_thai == 2)
                                                                    <span class="badge badge-danger">Ẩn</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Không xác định</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div style="display: inline-flex; gap: 2px;">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($i <= $pro->rating)
                                                                            <i class="fas fa-star" style="color: gold;"></i>
                                                                        @elseif ($i - 0.5 == $pro->rating)
                                                                            <i class="fas fa-star-half-alt" style="color: gold;"></i>
                                                                        @else
                                                                            <i class="far fa-star" style="color: gold;"></i>
                                                                        @endif
                                                                    @endfor
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    @if($pro->trang_thai == 1)
                                                                        <a href="{{ route('admin.product.edit.form', $pro->ma_san_pham) }}" class="btn btn-icon btn-round btn-info" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <form action="{{ route('admin.product.hidde-or-acctive', $pro->ma_san_pham) }}" method="POST" class="hidden-or-acctive">
                                                                            @csrf    
                                                                            <button type="button" class="btn btn-icon btn-round btn-black hidden-btn" data-bs-toggle="tooltip" title="Ẩn">
                                                                                <i class="fas fa-toggle-off text-white"></i>
                                                                            </button>   
                                                                        </form> 

                                                                    @elseif($pro->trang_thai == 2)

                                                                        <form action="{{ route('admin.product.hidde-or-acctive', $pro->ma_san_pham) }}" method="POST" class="acctive-form">
                                                                            @csrf
                                                                            <button type="button" class="btn btn-icon btn-round btn-warning acctive-btn" data-bs-toggle="tooltip" title="Hiển thị">
                                                                                <i class="fas fa-toggle-on text-white"></i>
                                                                            </button>
                                                                        </form>
                                                                        <button type="button" class="btn btn-icon btn-round btn-danger" data-bs-toggle="tooltip" title="Xóa">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    @endif
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
                                                    {{ $products->appends(request()->query())->links() }}

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
