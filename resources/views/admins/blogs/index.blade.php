@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
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
            <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
            <ul class="mb-3 breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.blog.index') }}">Blog</a>
                </li>
                @if(request()->routeIs('admin.products.hidden.list'))
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="">Blog đã ẩn</a>
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
                                            placeholder="Nhập tên hoặc mã Blog để tìm kiếm..."
                                            value="{{ request('search') }}"
                                            autocomplete="off"
                                        >
                                        <button type="submit" class="bg-white input-group-text">
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
                                                <li><button type="button" class="dropdown-item" id="show-products">Hiển thị các Blog đã chọn</button></li>
                                            @else
                                                <li><button type="button" class="dropdown-item" id="hide-products">Ẩn các Blog đã chọn</button></li>
                                            @endif
                                            <li><button type="button" class="dropdown-item text-danger" id="delete-products">Xóa các Blog đã chọn</button></li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Bộ lọc --}}
                                <div class="col-6 col-lg-2">
                                    @if(request()->routeIs('admin.products.hidden.list'))
                                        <a href="" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-eye-fill me-1"></i> Blog hiển thị
                                        </a>
                                    @else
                                        <a href="" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-eye-slash-fill me-1"></i> Blog ẩn
                                        </a>
                                    @endif
                                </div>
                                {{-- Thêm mới --}}
                                <div class="col-6 col-lg-2">
                                    <a href="" class="btn btn-primary w-100">
                                        <i class="fa fa-plus"></i> Thêm mới
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if($blogs->isEmpty())
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-box-open fa-3x text-muted"></i>
                            <h5 class="text-muted">Không có Blog nào trong danh sách</h5>
                            <p>Hãy thêm Blog mới để bắt đầu quản lý.</p>
                            <a href="#" class="mt-3 btn btn-primary">
                                <i class="fa fa-plus"></i> Thêm Blog mới
                            </a>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="add-row" class="table table-striped table-hover align-middle">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="text-center" style="width: 40px;"><input type="checkbox" id="checkAll"></th>
                                                            <th style="width: 20%;">Tiêu đề</th>
                                                            <th style="width: 8%;">Trạng thái</th>
                                                            <th style="width: 9%;">Hình ảnh</th>
                                                            <th style="width: 10%;">Danh mục</th>
                                                            <th style="width: 10%;">Ngày xuất bản</th>
                                                            <th style="width: 8%;">Tác giả</th>
                                                            <th style="width: 30%;">Nội dung</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($blogs as $blog)
                                                            <tr class="product-row">
                                                                <td class="text-center">
                                                                    <input type="checkbox" class="product-checkbox" value="{{ $blog->ma_blog }}">
                                                                </td>
                                                                <td>
                                                                    <span title="{{ $blog->tieu_de }}">
                                                                        {{ \Illuminate\Support\Str::limit($blog->tieu_de, 60) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if ($blog->trang_thai == 1)
                                                                        <span class="badge bg-success">Hiển thị</span>
                                                                    @elseif ($blog->trang_thai == 0)
                                                                        <span class="badge bg-danger">Ẩn</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Không xác định</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <img src="{{ $blog->hinh_anh ? asset('storage/' . $blog->hinh_anh) : asset('images/coffee_tea.jpg') }}"
                                                                        alt="{{ $blog->tieu_de }}"
                                                                        width="70" height="70"
                                                                        class="rounded shadow-sm" loading="lazy"
                                                                        title="{{ $blog->tieu_de }}">
                                                                </td>
                                                                <td>{{ $blog->danhMuc->ten_danh_muc_blog }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($blog->ngay_dang)->format('d/m/Y') }}</td>
                                                                <td>{{ $blog->tac_gia }}</td>
                                                                <td>
                                                                    {!! \Illuminate\Support\Str::limit(strip_tags($blog->noi_dung), 100, '...') !!}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
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
