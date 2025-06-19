@extends('layouts.admin')
@section('title',$title)
@section('subtitle',$subtitle)

@push('styles')
<style>
    .nguyen-lieu-row:hover {
        background-color: #f0f9ff;
        cursor: pointer;
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
                <a href="{{ route('admins.shopmaterial.index', ['ma_cua_hang' => request('ma_cua_hang')]) }}">Kho cửa hàng nguyên liệu</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Thêm nguyên liệu vào kho cửa hàng</a>
            </li>
        </ul>
    </div>
    <div class="mb-4 page-header d-flex justify-content-between align-items-center">
        <h4 class="page-title"> <strong>{{ $ten_cua_hang }}</strong></h4>
        <a href="{{ route('admins.shopmaterial.index', ['ma_cua_hang' => request('ma_cua_hang')]) }}" class="btn btn-outline-secondary">
            ← Quay lại
        </a>
    </div>

    <div class="border-0 shadow-sm card rounded-4">
        <div class="p-4 card-header d-flex justify-content-between align-items-center">
            <strong>DANH SÁCH NGUYÊN LIỆU NHẬP KHO</strong>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-chon-nguyen-lieu">
                + Chọn nguyên liệu
            </button>
        </div>
        <div class="p-4 card-body">
            <form id="form-them-nguyen-lieu" action="{{ route('admins.shopmaterial.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ma_cua_hang" value="{{ request('ma_cua_hang') }}">

                <div class="mb-4 table-responsive">
                    <table class="table display table-striped table-hover" id="nguyen-lieu-table">
                        <thead>
                            <tr>
                                <th>Mã NL</th>
                                <th>Tên nguyên liệu</th>
                                <th>Đơn vị</th>
                                <th>Số lượng tồn tối thiểu</th>
                            </tr>
                        </thead>
                        <tbody id="nguyen-lieu-da-chon-body">
                            <!-- JavaScript sẽ render dữ liệu -->
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" id="btn-luu-nguyen-lieu" class="px-4 btn btn-primary">
                        Lưu nguyên liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chọn nguyên liệu -->
<div class="modal fade" id="modal-chon-nguyen-lieu" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Chọn nguyên liệu để thêm vào kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                {{-- tìm kiếm --}}
                <div class="mb-3">
                    <input type="text" id="search-material" class="form-control" placeholder="Tìm mã hoặc tên nguyên liệu...">
                </div>
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" id="check-all-nguyen-lieu">
                            </th>
                            <th>Mã NL</th>
                            <th>Tên nguyên liệu</th>
                            <th>Đơn vị</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $index => $material)
                            <tr class="nguyen-lieu-row" data-index="{{ $index }}">
                                <td>
                                    <input type="checkbox" class="chon-nguyen-lieu" data-id="{{ $material->ma_nguyen_lieu }}"
                                        data-ten="{{ $material->ten_nguyen_lieu }}"
                                        data-donvi="{{ $material->don_vi }}">
                                </td>
                                <td>{{ $material->ma_nguyen_lieu }}</td>
                                <td>{{ $material->ten_nguyen_lieu }}</td>
                                <td>{{ $material->don_vi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="btn-them-nguyen-lieu" type="button" class="btn btn-primary" data-bs-dismiss="modal">Thêm vào danh sách</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-them-nguyen-lieu').addEventListener('click', function () {
        const checked = document.querySelectorAll('.chon-nguyen-lieu:checked');
        const tbody = document.getElementById('nguyen-lieu-da-chon-body');
        tbody.innerHTML = '';

        checked.forEach(item => {
            const id = item.dataset.id;
            const ten = item.dataset.ten;
            const donvi = item.dataset.donvi;

            tbody.innerHTML += `
                <tr>
                    <td><input type="hidden" name="ma_nguyen_lieu[]" value="${id}">${id}</td>
                    <td>${ten}</td>
                    <td>${donvi}</td>
                    <td>
                        <input type="number" name="so_luong_ton_min[${id}]" class="form-control" min="0" required>
                    </td>
                </tr>
            `;
        });
    });

    document.getElementById('btn-luu-nguyen-lieu').addEventListener('click', function () {
        Swal.fire({
            title: 'Xác nhận',
            text: 'Bạn có muốn thêm các nguyên liệu này vào cửa hàng không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Có, thêm vào!',
            cancelButtonText: 'Hủy',
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-them-nguyen-lieu').submit();
            }
        });
    });
</script>
<script>
    // Xử lý check all
    document.getElementById('check-all-nguyen-lieu').addEventListener('change', function () {
        const isChecked = this.checked;
        const checkboxes = document.querySelectorAll('.chon-nguyen-lieu');
        checkboxes.forEach(cb => cb.checked = isChecked);
    });

    // Nếu bỏ chọn một ô riêng lẻ thì tự động bỏ check all
    document.querySelectorAll('.chon-nguyen-lieu').forEach(cb => {
        cb.addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('.chon-nguyen-lieu');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            document.getElementById('check-all-nguyen-lieu').checked = allChecked;
        });
    });
    document.querySelectorAll('.nguyen-lieu-row').forEach((row, index) => {
        row.addEventListener('click', function (e) {
            // Nếu click vào chính checkbox thì bỏ qua (tránh toggle 2 lần)
            if (e.target.type === 'checkbox') return;

            const checkbox = row.querySelector('.chon-nguyen-lieu');
            checkbox.checked = !checkbox.checked;

            // Cập nhật trạng thái check all nếu cần
            const allCheckboxes = document.querySelectorAll('.chon-nguyen-lieu');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            document.getElementById('check-all-nguyen-lieu').checked = allChecked;
        });
    });
        document.getElementById('search-material').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#modal-chon-nguyen-lieu tbody tr');

        rows.forEach(row => {
            const maNL = row.cells[1].textContent.toLowerCase();
            const tenNL = row.cells[2].textContent.toLowerCase();
            const matched = maNL.includes(keyword) || tenNL.includes(keyword);
            row.style.display = matched ? '' : 'none';
        });
    });

</script>

@endpush
