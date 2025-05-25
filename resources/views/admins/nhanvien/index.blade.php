@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)


@push('styles')
    <style>
        .fas, .far {
            color: #f39c12;  /* Màu vàng cho sao */
            font-size: 18px;  /* Kích thước sao */
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
                        <a href="{{ route('admins.nhanvien.index') }}">Danh sách nhân viên cửa hàng</a>
                    </li>
                </ul>
            </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="bulk-archive-form" action="{{ route('admins.nhanvien.archive.bulk') }}" method="POST">
                        @csrf
                        @method('PATCH')
                            <div class="card-header">
                                <div class="form-group d-flex justify-content-between align-items-center">
                                    <div class="input-icon">
                                        <input type="text" class="form-control" placeholder="Search for...">
                                        <span class="input-icon-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                    <a href="{{ route('admins.nhanvien.create') }}" class="ml-2 btn btn-primary">
                                        <i class="mr-1 fa fa-plus"></i> Thêm Nhân Viên Cho Cửa Hàng
                                    </a>
                                </div>
                                <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                                    <a href="{{ route('admins.nhanvien.lich.showForm') }}" class="btn btn-info">Phân công lịch làm việc</a>
                                    <a href="{{ route('admins.nhanvien.lich.tuan') }}" class="btn btn-success">Lịch làm việc</a>
                                    <a href="#" class="btn btn-outline-secondary">Đổi ca làm</a>

                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Bạn có chắc muốn chuyển trạng thái nhân viên sang Tạm nghỉ?')">
                                        Tạm nghỉ
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="add-row" class="table display table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">

                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" id="select-all" />
                                                            </th>
                                                            <th>#</th>
                                                            <th>Mã nhân viên</th>
                                                            <th>Họ tên</th>
                                                            <th>Chức vụ</th>
                                                            <th>Cửa hàng</th>
                                                            <th>SĐT</th>
                                                            <th>Địa chỉ</th>
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
                                                                <td>
                                                                    <a href="{{ route('admins.nhanvien.edit', $nv->ma_nhan_vien) }}" class="btn btn-sm btn-warning">
                                                                        Sửa
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> <!-- end dataTables_wrapper -->
                                </div> <!-- end table-responsive -->
                            </div>
                    </form>    <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.select-nhanvien').forEach(cb => {
                cb.checked = checked;
            });
        });

        document.getElementById('bulk-archive-form').addEventListener('submit', function (e) {
            const selected = document.querySelectorAll('.select-nhanvien:checked');
            if (selected.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một nhân viên để tạm nghỉ.');
            }
        });
    </script>
@endpush
