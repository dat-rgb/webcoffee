@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
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
                            <a href="#">Quản lý kho nguyên liệu</a>
                        </li>
                    </ul>
                </div>

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
                            {{-- <a href="{{route('admins.shopmaterial.create') }}"
                            class="btn btn-primary {{ !request('ma_cua_hang') ? 'disabled' : '' }}"
                            onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                                <i class="fa fa-plus"></i> Thêm nguyên liệu
                            </a> --}}
                        {{-- <form id="createMaterialsForm" action="{{ route('admins.shopmaterial.create') }}" method="GET">
                            <button type="submit"
                                    class="btn btn-primary"
                                    {{ !request('ma_cua_hang') ? 'disabled' : '' }}
                                    onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                                <i class="fa fa-plus"></i> Thêm nguyên liệu
                            </button>
                        </form> --}}
                        <a href="{{ route('admins.shopmaterial.create', ['ma_cua_hang' => request('ma_cua_hang')]) }}"
                        class="btn btn-primary {{ !request('ma_cua_hang') ? 'disabled' : '' }}"
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
                        <form id="destroyMaterialsForm" action="#" method="GET">
                            <button type="submit"
                                    class="btn btn-primary"
                                    {{ !request('ma_cua_hang') ? 'disabled' : '' }}
                                    onclick="{{ !request('ma_cua_hang') ? 'return alert(\'Vui lòng chọn cửa hàng trước.\')' : '' }}">
                                <i class="fas fa-file-excel"></i> Hủy nguyên liệu
                            </button>
                        </form>
                    </div>
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
                                        <th>Slg tồn</th>
                                        <th>Slg max</th>
                                        <th>Đơn vị</th>
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
                                            <td>{{ $material->so_luong_ton }}</td>
                                            <td>{{ $material->so_luong_ton_max }}</td>
                                            <td class="text-center align-middle">{{ $material->don_vi }}</td>
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

                        <div class="text-center">
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
        // Nếu reload F5 → clear localStorage
        const navEntry = performance.getEntriesByType("navigation")[0];
        if (navEntry && navEntry.type === "reload") {
            localStorage.removeItem('selectedMaterialsImport');
            localStorage.removeItem('selectedMaterialsExport');
            console.log('Reload detected, cleared selectedMaterials');
        }
        // Các biến chung cho checkbox
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('input[name="materials[]"]');

        // Lưu checkbox đã chọn trong localStorage theo key riêng cho import và export
        let selectedMaterialsImport = JSON.parse(localStorage.getItem('selectedMaterialsImport')) || [];
        let selectedMaterialsExport = JSON.parse(localStorage.getItem('selectedMaterialsExport')) || [];

        // Đánh dấu checkbox với selectedMaterialsImport
        checkboxes.forEach(cb => {
            cb.checked = selectedMaterialsImport.includes(cb.value) || selectedMaterialsExport.includes(cb.value);
        });

        // Cập nhật checkbox toàn chọn
        function updateCheckAll() {
            checkAll.checked = checkboxes.length > 0 && [...checkboxes].every(cb => cb.checked);
        }
        updateCheckAll();

        // Xử lý checkbox thay đổi
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                // Theo mặc định cập nhật selectedMaterialsImport, bạn có thể tùy chỉnh nếu muốn
                // Ví dụ: nếu muốn dùng chung 1 danh sách cho cả 2, thì dùng chung 1 key localStorage.
                if (this.checked) {
                    if (!selectedMaterialsImport.includes(this.value)) {
                        selectedMaterialsImport.push(this.value);
                    }
                    if (!selectedMaterialsExport.includes(this.value)) {
                        selectedMaterialsExport.push(this.value);
                    }
                } else {
                    selectedMaterialsImport = selectedMaterialsImport.filter(v => v !== this.value);
                    selectedMaterialsExport = selectedMaterialsExport.filter(v => v !== this.value);
                }
                localStorage.setItem('selectedMaterialsImport', JSON.stringify(selectedMaterialsImport));
                localStorage.setItem('selectedMaterialsExport', JSON.stringify(selectedMaterialsExport));
                updateCheckAll();
            });
        });

        // Xử lý checkAll
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

        // Click trên dòng toggle checkbox
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function (e) {
                if (['A','BUTTON'].includes(e.target.tagName) || e.target.type === 'checkbox' || e.target.closest('.form-button-action')) {
                    return;
                }
                const checkbox = row.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    if (checkbox.checked) {
                        if (!selectedMaterialsImport.includes(checkbox.value)) {
                            selectedMaterialsImport.push(checkbox.value);
                        }
                        if (!selectedMaterialsExport.includes(checkbox.value)) {
                            selectedMaterialsExport.push(checkbox.value);
                        }
                    } else {
                        selectedMaterialsImport = selectedMaterialsImport.filter(v => v !== checkbox.value);
                        selectedMaterialsExport = selectedMaterialsExport.filter(v => v !== checkbox.value);
                    }
                    localStorage.setItem('selectedMaterialsImport', JSON.stringify(selectedMaterialsImport));
                    localStorage.setItem('selectedMaterialsExport', JSON.stringify(selectedMaterialsExport));
                    updateCheckAll();
                }
            });
        });

        // Xử lý form import
        const importForm = document.getElementById('selectMaterialsForm');
        if (importForm) {
            importForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const selectedMaterials = JSON.parse(localStorage.getItem('selectedMaterialsImport')) || [];

                if (selectedMaterials.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn nguyên liệu',
                        text: 'Vui lòng chọn ít nhất 1 nguyên liệu để nhập.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Xóa các hidden input cũ nếu có
                const oldInputs = importForm.querySelectorAll('input[name="materials[]"]');
                oldInputs.forEach(input => input.remove());

                // Tạo hidden input cho từng nguyên liệu
                selectedMaterials.forEach(id => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'materials[]';
                    hiddenInput.value = id;
                    importForm.appendChild(hiddenInput);
                });

                localStorage.removeItem('selectedMaterialsImport');
                importForm.submit();
            });
        }

        // Xử lý form export tương tự
        const exportForm = document.getElementById('exportMaterialsForm');
        if (exportForm) {
            exportForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const selectedMaterials = JSON.parse(localStorage.getItem('selectedMaterialsExport')) || [];

                if (selectedMaterials.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn nguyên liệu',
                        text: 'Vui lòng chọn ít nhất 1 nguyên liệu để xuất.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Xóa các hidden input cũ nếu có
                const oldInputs = exportForm.querySelectorAll('input[name="materials[]"]');
                oldInputs.forEach(input => input.remove());

                // Tạo hidden input cho từng nguyên liệu
                selectedMaterials.forEach(id => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'materials[]';
                    hiddenInput.value = id;
                    exportForm.appendChild(hiddenInput);
                });

                localStorage.removeItem('selectedMaterialsExport');
                exportForm.submit();
            });
        }


    });

</script>
@endpush




















{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('input[name="materials[]"]');
    const form = document.getElementById('selectMaterialsForm');

    // Lưu checkbox đã chọn vào localStorage
    let selectedMaterials = JSON.parse(localStorage.getItem('selectedMaterials')) || [];

    // Đánh dấu lại những cái đã lưu
    checkboxes.forEach(cb => {
        cb.checked = selectedMaterials.includes(cb.value);
    });

    function updateCheckAll() {
        checkAll.checked = checkboxes.length > 0 && [...checkboxes].every(cb => cb.checked);
    }
    updateCheckAll();

    // Check từng cái → update localStorage
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked) {
                if (!selectedMaterials.includes(this.value)) {
                    selectedMaterials.push(this.value);
                }
            } else {
                selectedMaterials = selectedMaterials.filter(v => v !== this.value);
            }
            localStorage.setItem('selectedMaterials', JSON.stringify(selectedMaterials));
            updateCheckAll();
        });
    });

    // Check all → update tất cả
    checkAll.addEventListener('change', function () {
        selectedMaterials = this.checked ? [...checkboxes].map(cb => cb.value) : [];
        checkboxes.forEach(cb => cb.checked = this.checked);
        localStorage.setItem('selectedMaterials', JSON.stringify(selectedMaterials));
    });

    // Click trên dòng → toggle checkbox
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.type === 'checkbox' || e.target.closest('.form-button-action')) {
                return;
            }
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;

                if (checkbox.checked) {
                    if (!selectedMaterials.includes(checkbox.value)) {
                        selectedMaterials.push(checkbox.value);
                    }
                } else {
                    selectedMaterials = selectedMaterials.filter(v => v !== checkbox.value);
                }
                localStorage.setItem('selectedMaterials', JSON.stringify(selectedMaterials));
                updateCheckAll();

                //console.log('Selected materials before clearing:', selectedMaterials);

            }
        });
    });

    // Khi submit form → kiểm tra & chuyển hướng URL kèm danh sách ID
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Lấy toàn bộ materials đã chọn trong localStorage
        const selectedMaterials = JSON.parse(localStorage.getItem('selectedMaterials')) || [];

        if (selectedMaterials.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn nguyên liệu',
                text: 'Vui lòng chọn ít nhất 1 nguyên liệu để nhập.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Xóa các hidden input cũ nếu có
        const oldInputs = form.querySelectorAll('input[name="materials[]"]');
        oldInputs.forEach(input => input.remove());

        // Tạo hidden input cho từng nguyên liệu
        selectedMaterials.forEach(id => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'materials[]';
            hiddenInput.value = id;
            form.appendChild(hiddenInput);
        });
        //console.log('Selected materials before clearing:', selectedMaterials);

        // Xóa localStorage để tránh giữ lại dữ liệu cũ
        localStorage.removeItem('selectedMaterials');

        // Submit form
        form.submit();
    });


    // Nếu reload F5 → clear localStorage
    const navEntry = performance.getEntriesByType("navigation")[0];
    if (navEntry && navEntry.type === "reload") {
        localStorage.removeItem('selectedMaterials');
        console.log('Reload detected, cleared selectedMaterials');
    }
});
</script> --}}
