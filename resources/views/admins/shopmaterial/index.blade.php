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
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                {{-- Logic để hiển thị tên cửa hàng trong subtitle --}}
                @php
                    $displaySubtitle = $subtitle;
                    if (request('ma_cua_hang') && isset($stores)) {
                        $selectedStore = $stores->firstWhere('ma_cua_hang', request('ma_cua_hang'));
                        if ($selectedStore) {
                            $displaySubtitle = $subtitle . ' tại ' . $selectedStore->ten_cua_hang;
                        }
                    }
                @endphp
                <h3 class="mb-3 fw-bold">{{ $displaySubtitle }}</h3>
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
                        <a href="#">Quản lý kho nguyên liệu</a>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header">
                    {{-- Dropdown chọn cửa hàng--}}
                    <div class="flex-wrap gap-3 card-header d-flex align-items-center justify-content-between">

                        <form action="{{ url()->current() }}" method="GET" class="gap-3 d-flex align-items-center" style="min-width: 250px;">
                            <select name="ma_cua_hang" class="form-select" onchange="this.form.submit()" required>
                                @if(!request('ma_cua_hang'))
                                    <option value="" selected disabled>-- Chọn cửa hàng --</option>
                                @endif
                                @foreach($stores as $store)
                                    <option value="{{ $store->ma_cua_hang }}"
                                        {{ request('ma_cua_hang') == $store->ma_cua_hang ? 'selected' : '' }}>
                                        {{ $store->ten_cua_hang }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <div class="gap-2 d-flex align-items-center">
                            <a href="{{ route('admins.shopmaterial.create', ['ma_cua_hang' => request('ma_cua_hang')]) }}"
                            class="btn btn-success {{ !request('ma_cua_hang') ? 'disabled' : '' }}"
                            onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                            <i class="fa fa-plus"></i> Thêm nguyên liệu
                            </a>

                            <form id="selectMaterialsForm" action="{{ route('admins.shopmaterial.showImportPage') }}" method="GET">
                                <button type="submit"
                                        class="btn btn-primary"
                                        {{ !request('ma_cua_hang') ? 'disabled' : '' }}
                                        onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                                    <i class="fas fa-file-import"></i> Nhập nguyên liệu
                                </button>
                            </form>

                            <form id="exportMaterialsForm" action="{{ route('admins.shopmaterial.showExportPage') }}" method="GET">
                                <button type="submit"
                                        class="btn btn-primary"
                                        {{ !request('ma_cua_hang') ? 'disabled' : '' }}
                                        onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                                    <i class="fas fa-file-export"></i> Xuất nguyên liệu
                                </button>
                            </form>
                            <form id="destroyMaterialsForm" action="{{ route('admins.shopmaterial.showDestroyPage') }}" method="GET">
                                @csrf
                                <button type="submit"
                                        class="btn btn-danger"
                                        {{ !request('ma_cua_hang') ? 'disabled' : '' }}
                                        onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                                    <i class="fas fa-file-excel"></i> Hủy nguyên liệu
                                </button>
                            </form>

                        </div>
                    </div>
                    {{-- Tìm kiếm nguyên liệu --}}
                    <form method="GET" action="{{ url()->current() }}" class="gap-2 d-flex align-items-center" style="max-width: 500px;">
                        @if (request('ma_cua_hang'))
                            <input type="hidden" name="ma_cua_hang" value="{{ request('ma_cua_hang') }}">
                        @endif
                        <div class="shadow-sm input-group">
                            <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Tìm theo mã nguyên liệu, tên nguyên liệu..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                            >
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fa fa-search text-muted"></i>
                            </button>
                        </div>
                    </form>
                </div>
                {{-- Hiển thị danh sách kho --}}
                <div class="card-body">
                    @if(!request('ma_cua_hang'))
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-store fa-3x text-muted"></i>
                            <h5 class="text-muted">Vui lòng chọn cửa hàng để xem nguyên liệu</h5>
                        </div>
                    @elseif($materials->isEmpty())
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-warehouse fa-3x text-muted"></i>
                            <h5 class="text-muted">Không có nguyên liệu nào trong kho</h5>
                            <p>Vui lòng chọn cửa hàng khác để xem kho nguyên liệu.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table display table-striped table-hover">
                                <thead>
                                    <tr class="text-center align-middle">
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Mã nguyên liệu</th>
                                        <th>Tên nguyên liệu</th>
                                        <th>Số lượng tồn</th>
                                        <th>Số lượng tồn tối thiểu</th>
                                        {{-- <th>Đơn vị</th> --}}
                                        <th>Trạng thái</th>
                                        <th>Yêu cầu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        @php
                                            $rowClass = '';
                                            if ($material->so_luong_ton <= $material->so_luong_ton_min) {
                                                $rowClass = 'table-warning';
                                            }
                                            if (isset($material->so_luong_ton) && $material->so_luong_ton == 0) {
                                                $rowClass = 'table-danger';
                                            }

                                        @endphp
                                        <tr class="{{ $rowClass }} ">
                                            <td class="text-center">
                                                <input type="checkbox" name="materials[]" value="{{ $material->ma_cua_hang }}|{{ optional($material->nguyenLieu)->ma_nguyen_lieu }}">
                                            </td>
                                            <td>{{ optional($material->nguyenLieu)->ma_nguyen_lieu ?? 'N/A' }}</td>
                                            <td>{{ optional($material->nguyenLieu)->ten_nguyen_lieu ?? 'N/A' }}</td>
                                            {{-- <td>{{ $material->so_luong_ton }}</td>
                                            <td>{{ $material->so_luong_ton_min }}</td>
                                            <td class="text-center align-middle">{{ $material->don_vi }}</td> --}}
                                            <td class="text-center align-middle">{{ $material->so_luong_ton . ' ' . $material->don_vi }}</td>
                                            <td class="text-center align-middle">{{ $material->so_luong_ton_min . ' ' . $material->don_vi }}</td>

                                            <td class="text-center align-middle">
                                                @php $trangThai = optional($material->cuaHang)->trang_thai; @endphp
                                                @if ($trangThai == 1)
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @elseif ($trangThai == 2)
                                                    <span class="badge bg-danger">Không hoạt động</span>
                                                @else
                                                    Không xác định
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if ($material->so_luong_ton <= $material->so_luong_ton_min)
                                                    Yêu cầu nhập hàng
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $materials->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                        </div>

                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admins/js/alert.js') }}"></script>
<script src="{{ asset('admins/js/admin-category.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Xóa localStorage nếu reload trang (F5)
    const navEntry = performance.getEntriesByType("navigation")[0];
    if ((navEntry && navEntry.type === "reload") || sessionStorage.getItem('formSubmitted') || sessionStorage.getItem('backToIndex')) {
        localStorage.removeItem('selectedMaterialsImport');
        localStorage.removeItem('selectedMaterialsExport');
        sessionStorage.removeItem('formSubmitted');
        sessionStorage.removeItem('backToIndex');
        console.log('Cleared localStorage due to reload or back navigation');
    }

    // 2. Bắt sự kiện các nút "Quay lại"
    // document.querySelectorAll('a.btn-secondary, a.back-to-index').forEach(el => {
    //     el.addEventListener('click', function () {
    //         sessionStorage.setItem('backToIndex', 'true');
    //     });
    // });

    // 2. Lấy các phần tử checkbox và nút chọn tất cả
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('input[name="materials[]"]');

    // 3. Lấy dữ liệu đã lưu trong localStorage hoặc khởi tạo mảng rỗng
    let selectedMaterialsImport = JSON.parse(localStorage.getItem('selectedMaterialsImport')) || [];
    let selectedMaterialsExport = JSON.parse(localStorage.getItem('selectedMaterialsExport')) || [];

    // 4. Khởi tạo trạng thái checkbox dựa trên localStorage
    checkboxes.forEach(cb => {
        cb.checked = selectedMaterialsImport.includes(cb.value) || selectedMaterialsExport.includes(cb.value);
    });

    // 5. Cập nhật trạng thái nút chọn tất cả
    function updateCheckAll() {
        checkAll.checked = checkboxes.length > 0 && [...checkboxes].every(cb => cb.checked);
    }
    updateCheckAll();

    // 6. Xử lý khi checkbox từng dòng thay đổi
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked) {
                if (!selectedMaterialsImport.includes(this.value)) selectedMaterialsImport.push(this.value);
                if (!selectedMaterialsExport.includes(this.value)) selectedMaterialsExport.push(this.value);
            } else {
                selectedMaterialsImport = selectedMaterialsImport.filter(v => v !== this.value);
                selectedMaterialsExport = selectedMaterialsExport.filter(v => v !== this.value);
            }
            localStorage.setItem('selectedMaterialsImport', JSON.stringify(selectedMaterialsImport));
            localStorage.setItem('selectedMaterialsExport', JSON.stringify(selectedMaterialsExport));
            updateCheckAll();
        });
    });

    // 7. Xử lý khi checkAll thay đổi
    checkAll.addEventListener('change', function () {
        if (this.checked) {
            selectedMaterialsImport = [...checkboxes].map(cb => cb.value);
            selectedMaterialsExport = [...checkboxes].map(cb => cb.value);
        } else {
            selectedMaterialsImport = [];
            selectedMaterialsExport = [];
        }
        checkboxes.forEach(cb => cb.checked = this.checked);
        localStorage.setItem('selectedMaterialsImport', JSON.stringify(selectedMaterialsImport));
        localStorage.setItem('selectedMaterialsExport', JSON.stringify(selectedMaterialsExport));
    });

    // 8. Click vào dòng table để toggle checkbox, ngoại trừ click vào link, nút, checkbox trực tiếp
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (['A', 'BUTTON'].includes(e.target.tagName) || e.target.type === 'checkbox' || e.target.closest('.form-button-action')) {
                return;
            }
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (!checkbox) return;

            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                if (!selectedMaterialsImport.includes(checkbox.value)) selectedMaterialsImport.push(checkbox.value);
                if (!selectedMaterialsExport.includes(checkbox.value)) selectedMaterialsExport.push(checkbox.value);
            } else {
                selectedMaterialsImport = selectedMaterialsImport.filter(v => v !== checkbox.value);
                selectedMaterialsExport = selectedMaterialsExport.filter(v => v !== checkbox.value);
            }
            localStorage.setItem('selectedMaterialsImport', JSON.stringify(selectedMaterialsImport));
            localStorage.setItem('selectedMaterialsExport', JSON.stringify(selectedMaterialsExport));
            updateCheckAll();
        });
    });

    // 9. Hàm xử lý submit form chung cho import, export, destroy
    function handleFormSubmit(formId, localStorageKey, alertTitle, alertText) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const selectedMaterials = JSON.parse(localStorage.getItem(localStorageKey)) || [];

            if (selectedMaterials.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: alertTitle,
                    text: alertText,
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Xóa input cũ nếu có
            const oldInputs = form.querySelectorAll('input[name="materials[]"]');
            oldInputs.forEach(input => input.remove());

            // Tạo input ẩn cho từng nguyên liệu
            selectedMaterials.forEach(id => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'materials[]';
                hiddenInput.value = id;
                form.appendChild(hiddenInput);
            });

            localStorage.removeItem(localStorageKey);
            sessionStorage.setItem('formSubmitted', 'true');
            form.submit();
        });
    }

    // 10. Gán xử lý form nhập, xuất, hủy theo hàm chung trên
    handleFormSubmit('selectMaterialsForm', 'selectedMaterialsImport', 'Chưa chọn nguyên liệu', 'Vui lòng chọn ít nhất 1 nguyên liệu để nhập.');
    handleFormSubmit('exportMaterialsForm', 'selectedMaterialsExport', 'Chưa chọn nguyên liệu', 'Vui lòng chọn ít nhất 1 nguyên liệu để xuất.');
    handleFormSubmit('destroyMaterialsForm', 'selectedMaterialsExport', 'Chưa chọn nguyên liệu', 'Vui lòng chọn ít nhất 1 nguyên liệu để hủy.');

});
</script>

@endpush
