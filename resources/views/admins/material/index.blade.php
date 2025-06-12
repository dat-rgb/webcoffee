@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
    <style>
        .fas, .far {
            color: #f39c12;  /* Màu vàng cho sao */
            font-size: 18px;  /* Kích thước sao */
        }
        .pagination-info,
        .small.text-muted {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
            <ul class="mb-3 breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admins.material.index') }}">Danh sách nguyên liệu</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center justify-content-between">
                            <!-- Tìm kiếm bên trái -->
                            <div class="mb-2 col-12 col-md-6 col-lg-6 mb-md-0">
                                <form method="GET" action="{{ url()->current() }}">
                                    <div class="shadow-sm input-group">
                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control"
                                            placeholder="Tìm theo mã, tên, nhà cung cấp..."
                                            value="{{ request('search') }}"
                                            autocomplete="off"
                                        >
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Nút Thêm nguyên liệu bên phải -->
                            <div class="col-12 col-md-6 col-lg-6 text-md-end">
                                <a href="{{ route('admins.material.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i> Thêm nguyên liệu
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="add-row" class="table display table-striped table-hover dataTable responsive" role="grid" aria-describedby="add-row_info">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Mã nguyên liệu</th>
                                                    <th>Tên nguyên liệu</th>
                                                    <th>Nhà cung cấp</th>
                                                    <th>Định lượng</th>
                                                    <th>Giá(VNĐ)</th>
                                                    <th>Loại</th>
                                                    <th>Trạng thái</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($materials->isEmpty())
                                                    <tr>
                                                        <td colspan="9" class="text-center">Không có sản phẩm nào. <a href="{{ route('admin.products.form') }}">Thêm mới.</a></td>
                                                    </tr>
                                                @else
                                                    @foreach($materials as $item)
                                                    <tr>
                                                        <td>{{ ($materials->currentPage() - 1) * $materials->perPage() + $loop->iteration }}</td>
                                                        <td>{{ $item->ma_nguyen_lieu }}</td>
                                                        <td>{{ $item->ten_nguyen_lieu }}</td>
                                                        <td>{{ $item->nhaCungCap->ten_nha_cung_cap ?? 'Chưa có' }}</td>
                                                        <td style="white-space: nowrap;">{{ $item->so_luong . ' ' . $item->don_vi }}</td>

                                                        <td>{{ number_format($item->gia) }}</td>
                                                        <td>{{ $item->loai_nguyen_lieu == 0 ? 'nguyên liệu' : 'vật liệu' }}</td>
                                                        <td style="text-align: center;">
                                                            @if($item->trang_thai == 1)
                                                                <span class="badge bg-success">Hoạt động</span>
                                                            @else
                                                                <span class="badge bg-danger">Không hoạt động</span>
                                                            @endif
                                                        </td>
                                                        <td style="min-width: 120px;">
                                                            <div class="d-flex align-items-center justify-content-start">
                                                                {{-- Bật / Tắt hoạt động --}}
                                                                <form action="{{ route('admins.material.toggleStatus', $item->id) }}" method="POST" style="margin-right: 8px;">
                                                                    @csrf
                                                                    <div class="m-0 form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" id="statusToggle{{ $item->id }}"
                                                                            {{ $item->trang_thai == 1 ? 'checked' : '' }}
                                                                            onclick="this.form.submit();">
                                                                    </div>
                                                                </form>
                                                                {{-- chỉnh sửa --}}
                                                                <a href="{{ route('admins.material.edit', $item->ma_nguyen_lieu) }}" class="p-0 btn btn-sm me-4">
                                                                    <i class="fas fa-cog text-warning"></i>
                                                                </a>
                                                                {{-- Xóa tạm --}}
                                                                <form action="{{ route('admins.material.archive', $item->id) }}" method="POST" class="form-archive d-inline">
                                                                    @csrf
                                                                    <button type="button" class="p-0 btn btn-sm btn-archive" style="border: none; background: none;">
                                                                        <i class="fas fa-archive text-secondary" title="Lưu trữ"></i>
                                                                    </button>
                                                                </form>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end dataTables_wrapper -->
                        </div> <!-- end table-responsive -->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                            {!! $materials->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const archiveButtons = document.querySelectorAll('.btn-archive');

            archiveButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Xác nhận tạm xóa nguyên liệu',
                        text: 'Bạn có chắc chắn muốn tạm xóa nguyên liệu này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

@endpush

