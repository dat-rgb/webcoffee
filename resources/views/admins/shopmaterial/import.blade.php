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
                <div class="shadow-sm card rounded-4">
                    <div class="card-header bg-light rounded-top-4">
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
                            <input type="hidden" name="soLo" value="{{ $soLo }}">
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
                        <div class="table-responsive rounded-3">
                            <table class="table text-center align-middle table-bordered table-hover" style="white-space: nowrap;">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="px-2 py-2">Mã nguyên liệu</th>
                                        <th class="px-2 py-2">Tên nguyên liệu</th>
                                        <th class="px-2 py-2">Định lượng</th>
                                        <th class="px-2 py-2" style="white-space: nowrap;">
                                            Số lượng <br>
                                            <small>(kg, lít, gói, túi, thùng)</small>
                                        </th>
                                        <th class="px-2 py-2">NSX</th>
                                        <th class="px-2 py-2">HSD</th>
                                        <th class="px-2 py-2" style="width: 200px;">Ghi chú</th>
                                        <th class="px-2 py-2">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $material)
                                    <tr>
                                        <td class="px-2 py-2">{{ $material->nguyenLieu->ma_nguyen_lieu }}</td>
                                        <td class="px-2 py-2">{{ $material->nguyenLieu->ten_nguyen_lieu }}</td>
                                        <td class="px-2 py-2">{{ $material->nguyenLieu->so_luong .' '. $material->nguyenLieu->don_vi }}</td>
                                        <td class="px-2 py-2">
                                            <input
                                                type="number"
                                                name="import[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                                class="form-control form-control-sm"
                                                step="any"
                                                min="0.01"

                                            >
                                        </td>
                                        <td>
                                            <input type="date"
                                            name="nsx[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                            class="form-control form-control-sm"
                                            value="{{ old('nsx.'.$material->ma_cua_hang.'.'.$material->ma_nguyen_lieu) }}">
                                        </td>
                                        <td>
                                            <input type="date"
                                            name="hsd[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                            class="form-control form-control-sm"
                                            value="{{ old('hsd.'.$material->ma_cua_hang.'.'.$material->ma_nguyen_lieu) }}">
                                        </td>
                                        <td class="px-2 py-2" style="width: 200px;">
                                            <input type="text" name="note[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]" class="form-control form-control-sm" placeholder="nhập...">
                                        </td>
                                        <td class="px-2 py-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row">Xóa</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success me-4">Xác nhận nhập hàng</button>
                        <a href="{{ route('admins.shopmaterial.index', ['ma_cua_hang' => $firstMaterial->ma_cua_hang]) }}" class="btn btn-secondary">Quay lại</a>
                    </div>
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
                        window.location.href = "{{ route('admins.shopmaterial.index', ['ma_cua_hang' => $firstMaterial->ma_cua_hang]) }}";
                    });
                }
            });
        });
    });
</script>
@endpush

