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
            <li class="nav-item">
                <a href="{{ route('admins.material.index') }}">Danh sách nguyên liệu</a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">
                <a href="{{ route('admins.material.archive.index') }}">Danh sách nguyên liệu tạm xóa</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="flex-wrap gap-2 card-header d-flex justify-content-between align-items-center">
                    {{-- Form tìm kiếm --}}
                    <form method="GET" action="{{ url()->current() }}" class="flex-grow-1">
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

                    {{-- Hành động bulk --}}
                    <div class="gap-2 d-flex">
                        <button type="button" class="btn btn-success btn-sm btn-restore">
                            <i class="fas fa-undo"></i> Khôi phục nguyên liệu
                        </button>
                        {{-- lí do là nguyên liệu không đc hoàn toàn xóa vĩnh viễn vì nó có thể liên quan tới nhập, xuất, sản phẩm tiêu dùng để kiểm tra dữ liệu khi cần thiết --}}
                        {{-- <button type="button" class="btn btn-danger btn-sm btn-delete">
                            <i class="fas fa-trash-alt"></i> Xóa đã chọn
                        </button> --}}
                    </div>
                </div>

                {{-- Form hành động nhóm --}}
                <form method="POST" action="{{ route('admins.material.bulk') }}" id="bulkForm">
                    @csrf
                    <div class="card-body">
                        @if($materials->isEmpty())
                            <div class="py-5 my-5 text-center">
                                <i class="mb-3 fa fa-warehouse fa-3x text-muted"></i>
                                <h5 class="text-muted">Không có nguyên liệu bị xóa nào</h5>
                                <a href="{{ route('admins.material.index') }}" class="mt-3 btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Quay về danh sách nguyên liệu
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th><input type="checkbox" id="check-all"></th>
                                            <th>Mã</th>
                                            <th>Tên nguyên liệu</th>
                                            <th>Giá (VNĐ)</th>
                                            <th>Nhà cung cấp</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materials as $material)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="ids[]" value="{{ $material->id }}" class="check-item">
                                                </td>
                                                <td>{{ $material->ma_nguyen_lieu }}</td>
                                                <td>{{ $material->ten_nguyen_lieu }}</td>
                                                <td>{{ number_format($material->gia) }}</td>
                                                <td>{{ $material->nhaCungCap->ten_nha_cung_cap ?? 'Chưa có' }}</td>
                                                <td><span class="badge bg-danger">Đã tạm xóa</span></td>
                                                <td class="">
                                                    @if ($material->deleted_at)
                                                        {{ $material->deleted_at->format('H:i \n\g\à\y d \t\h\á\n\g m \n\ă\m Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('check-all');
    const checkboxes = document.querySelectorAll('.check-item');

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', function (e) {
            if (!e.target.classList.contains('check-item')) {
                const checkbox = row.querySelector('.check-item');
                checkbox.checked = !checkbox.checked;
                checkAll.checked = [...checkboxes].every(cb => cb.checked);
            }
        });
    });

    const form = document.getElementById('bulkForm');

    document.querySelector('.btn-restore').addEventListener('click', function () {
        if (![...checkboxes].some(cb => cb.checked)) {
            Swal.fire('Chưa chọn nguyên liệu nào!', '', 'warning');
            return;
        }

        Swal.fire({
            title: 'Khôi phục nguyên liệu?',
            text: 'Bạn có chắc chắn muốn khôi phục các nguyên liệu đã chọn không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Khôi phục',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#28a745',
        }).then(result => {
            if (result.isConfirmed) {
                form.insertAdjacentHTML('beforeend', '<input type="hidden" name="action" value="restore">');
                form.submit();
            }
        });
    });

    document.querySelector('.btn-delete').addEventListener('click', function () {
        if (![...checkboxes].some(cb => cb.checked)) {
            Swal.fire('Chưa chọn nguyên liệu nào!', '', 'warning');
            return;
        }

        Swal.fire({
            title: 'Xóa vĩnh viễn?',
            text: 'Các nguyên liệu này sẽ bị xóa vĩnh viễn và không thể khôi phục!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#dc3545',
        }).then(result => {
            if (result.isConfirmed) {
                form.insertAdjacentHTML('beforeend', '<input type="hidden" name="action" value="delete">');
                form.submit();
            }
        });
    });
});
</script>
@endpush
