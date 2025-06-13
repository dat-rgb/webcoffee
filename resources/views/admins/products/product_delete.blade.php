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
                    <a href="{{ route('admin.products.list') }}">Sản phẩm</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.list.delete') }}">Sản phẩm đã xóa</a>
                </li>
                
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group mb-3">
                            <div class="row g-2 align-items-center">
                                <form action="{{ url()->current() }}" method="GET" class="col-12 col-md-6 col-lg-5">
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
                                </form>

                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <button type="button" class="dropdown-item" id="restore-products">Khôi phục sản phẩm đã xóa</button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" id="force-delete-products">Xóa vĩnh viễn</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($products->isEmpty())
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có sản phẩm nào trong danh sách xóa</h5>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-striped table-hover align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>Ảnh</th>
                                                        <th>Mã SP</th>
                                                        <th>Tên SP</th>
                                                        <th>Danh mục</th>
                                                        <th>Giá (vnd)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ( $products as $pro )
                                                        <tr role="" class=" product-row">
                                                            <td>
                                                                <input type="checkbox" class="product-checkbox" value="{{ $pro->ma_san_pham }}">
                                                            </td> 
                                                            <td>
                                                                <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="{{ $pro->ten_san_pham }}" width="80">                                                    
                                                            </td>
                                                            <td>{{ $pro->ma_san_pham }}</td>
                                                            <td>{{ $pro->ten_san_pham }}</td>
                                                            <td>{{ $pro->danhMuc->ten_danh_muc }}</td>
                                                            <td>{{ number_format($pro->gia, 0, ',', '.') }}</td>
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
