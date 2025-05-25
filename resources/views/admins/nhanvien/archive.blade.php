@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
<style>
    .fas, .far {
        color: #f39c12;  /* Màu vàng cho sao */
        font-size: 18px;
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

                <form id="bulk-restore-form" action="{{ route('admins.nhanvien.restore.bulk') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="input-icon w-100 me-3">
                            <input type="text" class="form-control" placeholder="Tìm kiếm...">
                            <span class="input-icon-addon">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                            <button type="submit" class="btn btn-warning me-2" onclick="return confirm('Khôi phục các nhân viên đã chọn?')">
                                Khôi phục đã chọn
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
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                            <td>
                                                {{-- <form action="{{ route('admins.nhanvien.restore', $nv->ma_nhan_vien) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Khôi phục nhân viên này?')">
                                                        Khôi phục
                                                    </button>
                                                </form> --}}
                                                {{-- hiển thị số ngày còn lại sẽ bị xóa  --}}
                                                @php
                                                    $updatedAt = \Carbon\Carbon::parse($nv->updated_at);
                                                    $expiresAt = $updatedAt->copy()->addDays(30);
                                                    $now = now();
                                                    $diff = $now->diff($expiresAt);

                                                    if ($now->greaterThanOrEqualTo($expiresAt)) {
                                                        $remaining = 'Đã hết hạn';
                                                    } else {
                                                        $remaining = $diff->d . ' : ' . $diff->h . ' : ' . $diff->i . ' ';
                                                    }
                                                @endphp
                                                <span class="mt-1 text-muted d-block">
                                                    Tự xóa sau {{ $remaining }}
                                                </span>

                                            </td>
                                        </tr>
                                    @endforeach
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
</script>
@endpush
