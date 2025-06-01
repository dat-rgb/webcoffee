@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)
@section('content')
<div class="page-inner">
    @if ($materials->isEmpty())
        <div class="alert alert-warning">
            Không có nguyên liệu nào để nhập.
        </div>
    @else
    <!-- Đặt ngay đây, trước <form> -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admins.shopmaterial.import') }}" method="POST">
            @csrf
            <div class="card">
            <div class="card-header">
                    @php
                        $firstMaterial = $materials->first();
                    @endphp
                    @if (optional($firstMaterial->cuaHang)->ten_cua_hang)
                        <h3 class="fw-bold">
                            Nhập nguyên liệu vào kho cửa hàng {{ $firstMaterial->cuaHang->ten_cua_hang }}
                        </h3>
                        <h5>
                            Ngày nhập: {{ $today }}
                        </h5>
                        <h5>
                            Số lô: {{ $soLo }}
                        </h5>
                    @else
                        <h3 class="fw-bold">
                            Nhập nguyên liệu vào kho (Không xác định)
                        </h3>
                        <h5>
                            Ngày hiện tại: {{ $today }}
                        </h5>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                {{-- <th><input type="checkbox" id="select-all"></th> --}}
                                <th>Mã nguyên liệu</th>
                                <th>Tên nguyên liệu</th>
                                <th>Định lương</th>
                                <th>Số lượng tồn</th>
                                <th>Số lượng tối đa</th>
                                <th>Số lượng nhập(kg, lít, gói, túi, thùng)</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                            <tr>
                                <td>{{ $material->nguyenLieu->ma_nguyen_lieu }}</td>
                                <td>{{ $material->nguyenLieu->ten_nguyen_lieu }}</td>
                                <td>{{ $material->nguyenLieu->so_luong .' '. $material->nguyenLieu->don_vi }}</td>
                                <td>{{ $material->so_luong_ton .' '. $material->don_vi}}</td>
                                <td>{{ $material->so_luong_ton_max .' '. $material->don_vi}}</td>
                                <td>
                                    @php
                                        $maxImport = $material->so_luong_ton_max - $material->so_luong_ton;
                                        if ($maxImport < 1) $maxImport = 0;
                                    @endphp
                                    <input
                                        type="number"
                                        name="import[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                        class="form-control"
                                        min="1"
                                        max="{{ $maxImport }}"
                                        value="{{ $maxImport == 0 ? 0 : '' }}"
                                        {{ $maxImport == 0 ? 'readonly' : '' }}
                                    >
                                    {{-- @if ($maxImport == 0)
                                        <input type="hidden" name="import[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]" value="0">
                                    @endif
                                    <input
                                        type="number"
                                        name="import[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                        class="form-control"
                                        min="1"
                                        max="{{ $maxImport }}"
                                        {{ $maxImport == 0 ? 'disabled' : '' }}
                                    > --}}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Xóa</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-success">Xác nhận nhập hàng</button>
                    <a href="{{ route('admins.shopmaterial.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
@push('scripts')
<script src="{{ asset('admins/js/alert.js') }}"></script>
{{--<script src="{{ asset('admins/js/admin-category.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-row').forEach(button => {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                row.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Đã xóa nguyên liệu!',
                    showConfirmButton: false,
                    timer: 1000
                });
                const tbody = document.querySelector('table tbody');
                if (tbody.children.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Không còn nguyên liệu!',
                        text: 'Bạn sẽ được chuyển về trang danh sách.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('admins.shopmaterial.index') }}";
                    });
                }
            });
        });
    });
</script>
@endpush

