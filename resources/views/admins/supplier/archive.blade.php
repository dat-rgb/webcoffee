@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

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
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-supplier"><a href="{{ route('admins.supplier.index') }}">Nhà Cung Cấp</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-supplier"><a href="#">Danh sách nhà cung cấp bị xóa</a></li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="border-0 shadow-sm card rounded-4">
        <div class="flex-wrap gap-2 card-header d-flex justify-content-between align-items-center">
            <form action="{{ route('admins.supplier.archived') }}" method="GET" class="d-flex align-items-center" role="search">
                <div class="rounded shadow-sm input-group" style="min-width: 280px;">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder=" Tìm tên nhà cung cấp..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                        style="border: 1px solid #ced4da; border-right: none; border-radius: 0.375rem 0 0 0.375rem;"
                    >
                    <button type="submit"
                            class="btn btn-outline-dark"
                            style="border: 1px solid #ced4da; border-left: none; border-radius: 0 0.375rem 0.375rem 0;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <a href="{{ route('admins.supplier.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
        <form id="bulk-action-form" method="POST">
            @csrf
            <div class="card-body">
                @if($suppliers->count() > 0)
                    <div class="flex-wrap gap-2 mb-3 d-flex justify-content-end">
                        <button type="button" id="btn-restore" class="gap-2 px-3 shadow-sm btn btn-success d-flex align-items-center" style="border-radius: 0">
                            <i class="fas fa-undo"></i>
                            <span>Khôi phục</span>
                        </button>
                        <button type="button" id="btn-destroy" class="gap-2 px-3 shadow-sm btn btn-danger d-flex align-items-center" style="border-radius: 0">
                            <i class="fas fa-trash"></i>
                            <span>Xóa vĩnh viễn</span>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center align-middle table-striped table-hover">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="checkAll" class="form-check-input">
                                    </th>
                                    <th>Tên NCC</th>
                                    <th>Địa chỉ</th>
                                    <th>SĐT</th>
                                    <th>Email</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($suppliers as $index => $supplier)
                                    <tr class="table-row text-center align-middle" data-id="{{ $supplier->ma_nha_cung_cap }}">
                                        <td>
                                            <input type="checkbox" name="selected_ids[]" value="{{ $supplier->ma_nha_cung_cap }}" class="form-check-input check-item">
                                        </td>
                                        <td class="text-start">{{ $supplier->ten_nha_cung_cap }}</td>
                                        <td class="text-start">{{ $supplier->dia_chi }}</td>
                                        <td>{{ $supplier->so_dien_thoai }}</td>
                                        <td>{{ $supplier->mail ?? 'Không có' }}</td>
                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @else
                    <div class="py-5 text-center shadow-sm alert rounded-4">
                        <div class="mb-3">
                            <i class="fas fa-box-open fa-3x text-secondary"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Không có nhà cung cấp nào đã xóa</h4>
                        <p class="mb-0 text-muted">Tất cả nhà cung cấp hiện tại đều đang hoạt động.</p>
                        <a href="{{ route('admins.supplier.index') }}" class="mt-3 btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách nhà cung cấp
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bulk-action-form');
    const checkAllBox = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.check-item');

    // Check all logic
    if (checkAllBox) {
        checkAllBox.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            checkAllBox.checked = document.querySelectorAll('.check-item:checked').length === checkboxes.length;
        });
    });

    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() === 'input') return;
            const checkbox = this.querySelector('.check-item');
            checkbox.checked = !checkbox.checked;
            checkAllBox.checked = document.querySelectorAll('.check-item:checked').length === checkboxes.length;
        });
    });

    // Common confirm function
    function handleBulkAction(buttonId, title, text, actionUrl, confirmBtnText) {
        const btn = document.getElementById(buttonId);
        if (!btn) return;

        btn.addEventListener('click', function () {
            const selected = document.querySelectorAll('.check-item:checked');
            if (selected.length === 0) {
                Swal.fire('Chưa chọn!', 'Vui lòng chọn ít nhất một nhà cung cấp.', 'warning');
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng chờ trong giây lát',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    form.setAttribute('action', actionUrl);
                    form.submit();
                }
            });
        });
    }

    // Gọi hàm confirm cho từng hành động
    handleBulkAction('btn-restore', 'Khôi phục nhà cung cấp?', 'Bạn có chắc muốn khôi phục các nhà cung cấp đã chọn không?', '{{ route("admins.supplier.bulkRestore") }}', 'Khôi phục');
    handleBulkAction('btn-destroy', 'Xóa vĩnh viễn?', 'Bạn có chắc muốn xóa vĩnh viễn các nhà cung cấp đã chọn không?', '{{ route("admins.supplier.bulkDestroy") }}', 'Xóa');
});
</script>
@endpush



