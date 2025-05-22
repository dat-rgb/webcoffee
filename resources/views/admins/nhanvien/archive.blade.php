@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
<style>
    .fas, .far {
        color: #f39c12;  /* Màu vàng cho sao */
        font-size: 18px; /* Kích thước sao */
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
                <a href="{{ route('admins.nhanvien.archived') }}">Danh sách nhân viên nghỉ</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="input-icon w-100">
                        <input type="text" class="form-control" placeholder="Tìm kiếm...">
                        <span class="input-icon-addon">
                            <i class="fa fa-search"></i>
                        </span>
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
                                            <form action="{{ route('admins.nhanvien.restore', $nv->ma_nhan_vien) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Bạn có chắc muốn khôi phục nhân viên này?')">
                                                    Khôi phục
                                                </button>
                                            </form>
                                            <span class="text-muted">| Tự xóa sau thời gian</span>
                                            <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- table-responsive -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admins/js/alert.js') }}"></script>
<script>
    // Xử lý chọn tất cả checkbox
    document.getElementById('select-all').addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.select-nhanvien').forEach(cb => cb.checked = checked);
    });
</script>
@endpush
