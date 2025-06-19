@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
<style>
    .swal2-actions button {
        margin: 0 6px;
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
                <a href="{{ route('admins.nhanvien.index') }}">Danh sách nhân viên</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admins.nhanvien.archived') }}">Danh sách nhân viên nghỉ</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="input-icon w-100 me-3">
                        <form method="get" action="{{ url()->current() }}" class="gap-2 d-flex align-items-center" onsubmit="showSearchLoading(this)">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Tìm kiếm..."
                                class="form-control"
                            >
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                </div>
                <form id="bulk-restore-form" action="{{ route('admins.nhanvien.restore.bulk') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                            <button type="submit" class="btn btn-warning me-2 nhanvien-btn-update" >
                                Khôi phục
                            </button>
                            <a href="{{ route('admins.nhanvien.index') }}" class="btn btn-success">Danh sách nhân viên</a>
                        </div>
                    </div>

                    <div class="card-body"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all" /></th>
                                        <th>#</th>
                                        <th>Mã nhân viên</th>
                                        <th>Họ tên</th>
                                        <th>Chức vụ</th>
                                        <th>Cửa hàng</th>
                                        <th>SĐT</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày bị xóa</th>
                                        <th>Tự xóa sau</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($nhanViens->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                @if (request('search'))
                                                    <i class="mr-1 fas fa-exclamation-circle text-warning"></i>
                                                    Không tìm thấy nhân viên nào với từ khóa <strong>{{ $search }}</strong>.
                                                @else
                                                    <i class="mr-2 fas fa-exclamation-circle text-warning"></i>
                                                    Hiện danh sách nhân viên nghỉ trống.
                                                    <br>
                                                    <a href="{{ route('admins.nhanvien.index') }}" class="mt-3 btn btn-primary">
                                                        <i class="fa fa-arrow-alt-circle-right"></i> Quay lại danh sách nhân viên
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($nhanViens as $index => $nv)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="select-nhanvien" name="selected_nhanviens[]" value="{{ $nv->ma_nhan_vien }}">
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $nv->ma_nhan_vien }}</td>
                                                <td>{{ $nv->ho_ten_nhan_vien }}</td>
                                                <td>{{ $nv->chucVu->ten_chuc_vu ?? 'N/A' }}</td>
                                                <td>{{ $nv->cuaHang->ten_cua_hang ?? 'N/A' }}</td>
                                                <td>{{ $nv->so_dien_thoai }}</td>
                                                <td>{{ $nv->dia_chi }}</td>
                                                <td>{{ $nv->updated_at }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $updatedAt = \Carbon\Carbon::parse($nv->updated_at);
                                                        $expiresAt = $updatedAt->copy()->addDays(30);
                                                        $now = now();
                                                        $diff = $now->diff($expiresAt);

                                                        if ($now->greaterThanOrEqualTo($expiresAt)) {
                                                            $remaining = 'Đã hết hạn';
                                                        } else {
                                                            $remaining = $diff->d . ' ngày : ' . $diff->h . ' giờ : ' . $diff->i . ' phút';
                                                        }
                                                    @endphp

                                                    <span class="d-inline-flex align-items-center small">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $remaining }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->
                    </div> <!-- card-body -->
                </form>

            </div> <!-- card -->
        </div> <!-- col -->
    </div> <!-- row -->
</div> <!-- page-inner -->
@endsection

@push('scripts')
<script src="{{ asset('admins/js/alert.js') }}"></script>
<script>
    // Kiểm tra nếu không chọn nhân viên nào thì cảnh báo
    document.getElementById('bulk-restore-form').addEventListener('submit', function (e) {
        const selected = document.querySelectorAll('.select-nhanvien:checked');
        if (selected.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một nhân viên để khôi phục.');
        }
    });

    // Xử lý chọn tất cả
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.select-nhanvien');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            // Nếu click thẳng vào checkbox thì không làm gì thêm
            if (e.target.type === 'checkbox') {
                return;
            }

            // Tìm checkbox trong hàng hiện tại
            const checkbox = this.querySelector('.select-nhanvien');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;  // toggle trạng thái checkbox
            }
        });
    });
</script>
@endpush
