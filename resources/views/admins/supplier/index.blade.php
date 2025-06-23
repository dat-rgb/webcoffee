@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
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
                <a href="{{ route('admin.dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-supplier">
                <a href="{{ route('admins.supplier.index') }}">Danh Sách Nhà Cung Cấp</a>
            </li>
        </ul>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row">
        <div class="mt-4 border-0 shadow-sm card rounded-4">
            <div class="flex-wrap gap-2 card-header d-flex justify-content-between align-items-center">
                <form action="{{ route('admins.supplier.index') }}" method="GET" class="d-flex align-items-center" role="search">
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

                <a href="{{ route('admins.supplier.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Thêm Nhà Cung Cấp
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display table-striped table-hover">
                        <thead>
                            <tr class="text-center align-middle">
                                <th>#</th>
                                <th>Tên <br>Nhà cung cấp</th>
                                <th>Địa chỉ</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $index => $supplier)
                                <tr>
                                    <td>{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $index + 1 }}</td>
                                    <td class="text-start">{{ $supplier->ten_nha_cung_cap }}</td>
                                    <td class="text-start">{{ $supplier->dia_chi }}</td>
                                    <td>{{ $supplier->so_dien_thoai }}</td>
                                    <td>{{ $supplier->mail ?? 'Không có' }}</td>
                                    <td>
                                        <span class="badge {{ $supplier->trang_thai == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $supplier->trang_thai == 1 ? 'Hoạt động' : 'Ngưng hoạt động' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="gap-2 d-flex justify-content-center">
                                            <form id="toggle-status-{{ $supplier->ma_nha_cung_cap }}"
                                                action="{{ route('admins.supplier.toggleStatus', $supplier->ma_nha_cung_cap) }}"
                                                method="POST" style="display: inline-block;">
                                                @csrf
                                                <button type="button"
                                                        class="btn btn-sm {{ $supplier->trang_thai == 1 ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        title="Đổi trạng thái"
                                                        onclick="confirmToggle('{{ $supplier->ma_nha_cung_cap }}', '{{ $supplier->trang_thai == 1 ? 'tắt' : 'bật' }}')">
                                                    <i class="fas {{ $supplier->trang_thai == 1 ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admins.supplier.edit', $supplier->ma_nha_cung_cap) }}"
                                                class="btn btn-sm btn-outline-warning btn-edit"
                                                data-url="{{ route('admins.supplier.edit', $supplier->ma_nha_cung_cap) }}"
                                                title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                            </a>
                                            <form id="archive-form-{{ $supplier->ma_nha_cung_cap }}"
                                                action="{{ route('admins.supplier.archive', $supplier->ma_nha_cung_cap) }}"
                                                method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-archive"
                                                        data-id="{{ $supplier->ma_nha_cung_cap }}"
                                                        title="Tạm xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Không có nhà cung cấp nào. <a href="{{ route('admins.supplier.create') }}">Thêm mới</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div>
        <div class="d-flex justify-content-center">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Toggle trạng thái
            window.confirmToggle = function(id, action) {
                Swal.fire({
                    title: 'Xác nhận thay đổi trạng thái',
                    text: `Bạn có chắc chắn muốn ${action} hoạt động nhà cung cấp này không?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Đang xử lý...',
                            text: 'Vui lòng chờ',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        setTimeout(() => {
                            document.getElementById('toggle-status-' + id).submit();
                        }, 600);
                    }
                });
            };

            // Chỉnh sửa
            document.querySelectorAll('.btn-edit').forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-url');
                    Swal.fire({
                        title: 'Xác nhận chỉnh sửa?',
                        text: "Bạn có chắc muốn chỉnh sửa nhà cung cấp này không?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Có, chỉnh sửa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Đang chuyển trang...',
                                text: 'Vui lòng chờ',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            setTimeout(() => {
                                window.location.href = url;
                            }, 600);
                        }
                    });
                });
            });

            // Tạm xóa
            document.querySelectorAll('.btn-archive').forEach(function (button) {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Xác nhận tạm xóa?',
                        text: "Bạn có chắc muốn tạm xóa nhà cung cấp này không?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Có, xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Đang xử lý...',
                                text: 'Vui lòng chờ',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            setTimeout(() => {
                                document.getElementById('archive-form-' + id).submit();
                            }, 600);
                        }
                    });
                });
            });
        });
    </script>
@endpush


