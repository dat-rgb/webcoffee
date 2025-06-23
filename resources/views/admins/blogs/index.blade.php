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
                @if(request()->input('trang_thai') == 0)
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

                                {{-- Lọc danh mục --}}
                                <div class="col-6 col-lg-2">
                                    <select class="form-select" name="ma_danh_muc" id="categoryFilter">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach ($danhMucBlogs as $dm)
                                            <option value="{{ $dm->ma_danh_muc_blog }}" {{ request('ma_danh_muc') == $dm->ma_danh_muc_blog ? 'selected' : '' }}>
                                                {{ $dm->ten_danh_muc_blog }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Thao tác nhanh --}}
                                <div class="col-6 col-lg-2">
                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(request()->input('trang_thai') == 0)
                                                <li><button type="button" class="dropdown-item" id="show-blogs">Hiển thị các Blog đã chọn</button></li>
                                            @else
                                                <li><button type="button" class="dropdown-item" id="hide-blogs">Ẩn các Blog đã chọn</button></li>
                                            @endif
                                            <li><button type="button" class="dropdown-item text-danger" id="delete-blogs">Xóa các Blog đã chọn</button></li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Bộ lọc trạng thái --}}
                                <div class="col-6 col-lg-2">
                                    @php
                                        $newTrangThai = request()->input('trang_thai') == 0 ? 1 : 0;
                                    @endphp
                                    <a href="{{ route('admin.blog.index', array_merge(request()->except('page'), ['trang_thai' => $newTrangThai])) }}"
                                        class="btn btn-outline-{{ $newTrangThai == 0 ? 'secondary' : 'danger' }} w-100">
                                        <i class="bi bi-eye{{ $newTrangThai == 0 ? '-slash' : '' }}-fill me-1"></i>
                                        {{ $newTrangThai == 0 ? 'Blog ẩn' : 'Blog hiển thị' }}
                                    </a>
                                </div>

                                {{-- Thêm mới --}}
                                <div class="col-6 col-lg-2">
                                    <a href="{{ route('admin.blog.add') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-plus"></i> Thêm mới
                                    </a>
                                </div>

                                {{-- Giữ trạng thái khi submit --}}
                                <input type="hidden" name="trang_thai" value="{{ request('trang_thai', 1) }}">
                            </div>
                        </form>
                    </div>
                    @if($blogs->isEmpty())
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-box-open fa-3x text-muted"></i>
                            <h5 class="text-muted">Không tìm thấy Blog nào phù hợp</h5>

                            @if(request()->has('search') || request()->has('ma_danh_muc') || request()->has('trang_thai'))
                                <p>Không có Blog nào khớp với bộ lọc, trạng thái hoặc từ khóa bạn đã nhập.</p>
                                <a href="{{ route('admin.blog.index') }}" class="mt-3 btn btn-outline-secondary">
                                    <i class="fa fa-undo"></i> Bỏ lọc và tìm kiếm
                                </a>
                            @else
                                <p>Hãy thêm Blog mới để bắt đầu quản lý.</p>
                                <a href="{{ route('admin.blog.add') }}" class="mt-3 btn btn-primary">
                                    <i class="fa fa-plus"></i> Thêm Blog mới
                                </a>
                            @endif
                        </div>
                    @else   
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="add-row" class="table table-striped table-hover align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" style="width: 40px;"><input type="checkbox" id="checkAll"></th>
                                                            <th style="min-width: 200px;">Tiêu đề</th>
                                                            <th>Trạng thái</th>
                                                            <th>Hình ảnh</th>
                                                            <th>Danh mục</th>
                                                            <th>Ngày xuất bản</th>
                                                            <th>Tác giả</th>
                                                            <th>Nội dung</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($blogs as $blog)
                                                            <tr class="blog-row">
                                                                <td class="text-center">
                                                                    <input type="checkbox" class="blog-checkbox" value="{{ $blog->ma_blog }}">
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('admin.blog.edit.show',$blog->ma_blog) }}" class="" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                                        <span title="{{ $blog->tieu_de }}">
                                                                            {{ \Illuminate\Support\Str::limit($blog->tieu_de, 60) }}
                                                                        </span>
                                                                    </a>
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
                                                                <a href="{{ route('admin.blog.edit.show',$blog->ma_blog) }}" class="" data-bs-toggle="tooltip" title="{{ $blog->tieu_de }}">
                                                                    <img src="{{ $blog->hinh_anh ? asset('storage/' . $blog->hinh_anh) : asset('images/coffee_tea.jpg') }}"
                                                                        alt="{{ $blog->tieu_de }}"
                                                                        width="70" height="70"
                                                                        class="rounded shadow-sm" loading="lazy"
                                                                        title="{{ $blog->tieu_de }}">
                                                                </a>
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
                                        <div class="col-sm-12 col-md-5">
                                            <div class="dataTables_info">
                                                Hiển thị {{ $blogs->firstItem() }} đến {{ $blogs->lastItem() }} của {{ $blogs->total() }} blog
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                                <ul class="pagination justify-content-end mb-0">
                                                    {{-- Previous Page --}}
                                                    <li class="paginate_button page-item {{ $blogs->onFirstPage() ? 'disabled' : '' }}">
                                                        <a href="{{ $blogs->previousPageUrl() ?? '#' }}" class="page-link">Trước</a>
                                                    </li>

                                                    {{-- Page Numbers --}}
                                                    @for ($i = 1; $i <= $blogs->lastPage(); $i++)
                                                        <li class="paginate_button page-item {{ $blogs->currentPage() == $i ? 'active' : '' }}">
                                                            <a href="{{ $blogs->url($i) }}" class="page-link">{{ $i }}</a>
                                                        </li>
                                                    @endfor

                                                    {{-- Next Page --}}
                                                    <li class="paginate_button page-item {{ !$blogs->hasMorePages() ? 'disabled' : '' }}">
                                                        <a href="{{ $blogs->nextPageUrl() ?? '#' }}" class="page-link">Kế tiếp</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    @endif
                </div> 
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('categoryFilter');
    if (select) {
        select.addEventListener('change', function () {
            this.form.submit(); 
        });
    }
});

document.getElementById('checkAll')?.addEventListener('click', function () {
    document.querySelectorAll('.blog-checkbox').forEach(cb => cb.checked = this.checked);
});

document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.blog-checkbox');
    const hideButton = document.getElementById('hide-blogs');
    const showButton = document.getElementById('show-blogs');
    const deleteButton = document.getElementById('delete-blogs');

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
            });
        });
    }

    function handleBulkButton(button, action, confirmMessage = null) {
        if (!button) return;
        button.addEventListener('click', () => {
            const selectedIds = getSelectedBlogIds();
            if (selectedIds.length === 0) return showWarning('Vui lòng chọn ít nhất 1 blog.');

            if (confirmMessage) {
                Swal.fire({
                    title: confirmMessage.title,
                    text: confirmMessage.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: confirmMessage.confirmText,
                    cancelButtonText: 'Hủy'
                }).then(result => {
                    if (result.isConfirmed) performBulkAction(selectedIds, action);
                });
            } else {
                performBulkAction(selectedIds, action);
            }
        });
    }

    handleBulkButton(hideButton, 'hide');
    handleBulkButton(showButton, 'show');
    handleBulkButton(deleteButton, 'delete', {
        title: 'Bạn có chắc muốn xoá?',
        text: 'Thao tác này không thể hoàn tác!',
        confirmText: 'Xoá'
    });

    function getSelectedBlogIds() {
        return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
    }

    function showWarning(message) {
        Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: message });
    }

    function performBulkAction(selectedIds, action) {
        Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        setTimeout(() => {
            $.ajax({
                url: '/admin/blog/bulk-action',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    selected_blogs: selectedIds,
                    action: action
                },
                success: function (response) {
                    Swal.fire({
                        icon: response.status === 'success' ? 'success' : 'error',
                        title: response.status === 'success' ? 'Thành công' : 'Lỗi',
                        text: response.message || 'Đã thực hiện thao tác.'
                    }).then(() => {
                        if (response.status === 'success') location.reload();
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi hệ thống',
                        text: 'Không thể thực hiện thao tác. Vui lòng thử lại sau.'
                    });
                }
            });
        }, 800);
    }
});

</script>
<script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush
