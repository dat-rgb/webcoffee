@extends('layouts.admin')
@section('title',$title)
@section('subtitle',$subtitle)

@push('styles')
<style>
     th {
        white-space: nowrap;
        font-size: 14px;
        padding: 8px 10px;
        text-align: left;
    }
    .store-row td {
        text-align: left;
        vertical-align: top; 
        word-break: break-word;
    }
    .modal-content {
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-title {
        font-size: 1.25rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .custom-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .modal-body .row {
        margin-top: 0.5rem;
    }
    .badge-success {
        background-color: #28a745;
        color: #fff;
        padding: 4px 10px;
        border-radius: 5px;
    }

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
        padding: 4px 10px;
        border-radius: 5px;
    }
    .badge-time {
        background-color: #17a2b8;
        color: #fff;
        padding: 4px 10px;
        border-radius: 5px;
        font-weight: 500;
    }
</style>
@endpush
@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">{{ $subtitle }}</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('admin') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Cửa hàng</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                        {{-- Ô tìm kiếm --}}
                        <div class="col-12 col-lg-5"> 
                            <div class="input-group">
                            <input type="text" id="searchInput" name="search" class="form-control"
                                placeholder="Nhập tên cửa hàng hoặc tên danh mục..." 
                                value="{{ request('search') }}" autocomplete="off">                                
                                <button type="button" class="btn btn-outline-secondary" id="searchBtn">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Dropdown thao tác --}}
                        <div class="col-6 col-lg-2">
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                    Thao tác
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" id="delete-store">
                                           Chuyển đổi trạng thái cửa hàng được chọn
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-6 col-lg-2">
                            <button type="button" 
                                class="btn btn-primary w-100" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addStoreModal">
                                <i class="fa fa-plus"></i> Thêm cửa hàng
                            </button>
                        </div>
                    </form>
                </div>
                    @if($stores->isEmpty())
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có cửa hàng trong danh sách</h5>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-striped table-hover align-middle text-center">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="listCheckAll"></th>
                                                    <th>Mã cửa hàng</th>
                                                    <th>Trạng thái</th>
                                                    <th>Giờ hoạt động</th>
                                                    <th style="min-width: 300px;">Tên cửa hàng</th>
                                                    <th style="min-width: 250px;">Email</th>
                                                    <th style="min-width: 450px;">Địa chỉ</th>
                                                    <th>Số điện thoại</th>
                                                  
                                                </tr>
                                            </thead>
                                            <tbody id="order-tbody">
                                                @foreach ( $stores as $store )
                                                    <tr class="store-row">
                                                        <td>
                                                            <input type="checkbox" class="store-checkbox" value="{{ $store->ma_cua_hang }}">
                                                        </td>
                                                        <td>
                                                            <a href="" class="" data-bs-toggle="tooltip" title="{{ $store->ten_cua_hang }}">
                                                            {{ $store->ma_cua_hang }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if ($store->trang_thai === 1)
                                                                <span class="badge badge-success">Hoạt động</span>
                                                            @else
                                                                <span class="badge badge-danger">Không hoạt động</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-time">
                                                                {{ 'Từ ' . $store->gio_mo_cua . ' đến ' . $store->gio_dong_cua }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $store->ten_cua_hang }}</td>
                                                        <td>{{ $store->email }}</td>
                                                        <td>{{ $store->dia_chi }}</td>
                                                        <td>{{ $store->so_dien_thoai }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <form id="store-action-form" method="POST" action="{{ route('admin.store.toggle') }}">
                                            @csrf
                                            <input type="hidden" name="action" id="store-action-type" value=""> 
                                            <input type="hidden" name="store_ids[]" id="store-ids-holder"> 
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
        </div>
    </div>
    <!-- Modal Thêm cửa hàng mới -->
    <div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addStoreModalLabel">
                        <i class="fa fa-plus me-2"></i> Thêm cửa hàng mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>

                <form id="addStoreForm" method="POST" action="{{ route('admin.store.add') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="ma_cua_hang">Mã cửa hàng</label>
                                    <input type="text" name="ma_cua_hang" class="form-control" value="{{ $newStoreCode }}" readonly>
                                    @error('ma_cua_hang') <div class="custom-error">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="ten_cua_hang">Tên cửa hàng</label>
                                    <input type="text" name="ten_cua_hang" class="form-control" placeholder="Nhập tên cửa hàng" value="{{ old('ten_cua_hang') }}" >
                                    @error('ten_cua_hang') <div class="custom-error">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="so_dien_thoai">Số điện thoại</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        <input type="number" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}">
                                    </div>
                                    @error('so_dien_thoai') <div class="custom-error">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Nhập email" value="{{ old('email') }}" >
                                    @error('email') <div class="custom-error">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Trạng thái</label>
                                    <select class="form-select" name="trang_thai" id="exampleFormControlSelect1">
                                        <option value="" selected disabled>-- Chọn trạng thái --</option>
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="gio_mo_cua">Giờ hoạt động</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="mx-2">Từ </span>
                                        <input type="time" name="gio_mo_cua" class="form-control w-auto" value="{{ old('gio_mo_cua') }}">
                                        <span class="mx-2"> đến</span>
                                        <input type="time" name="gio_dong_cua" class="form-control w-auto" value="{{ old('gio_dong_cua') }}">
                                    </div>
                                    @error('gio_mo_cua')
                                        <div class="custom-error text-danger">{{ $message }}</div>
                                    @enderror
                                    @error('gio_dong_cua')
                                        <div class="custom-error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="dia_chi">Số nhà, tên đường</label>
                                    <input type="text" name="dia_chi" class="form-control" placeholder="Nhập địa chỉ" value="{{ old('dia_chi') }}" >
                                    @error('dia_chi') <div class="custom-error">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="province">Tỉnh / Thành phố</label>
                                    <select id="provinceSelect" name="province" class="form-select" ></select>
                                    <input type="hidden" id="provinceName" name="provinceName">
                                </div>
                                <div class="form-group">
                                    <label for="district">Quận / Huyện</label>
                                    <select id="districtSelect" name="district" class="form-select" disabled ></select>
                                    <input type="hidden" id="districtName" name="districtName">
                                </div>
                                <div class="form-group">
                                    <label for="ward">Phường / Xã</label>
                                    <select id="wardSelect" name="ward" class="form-select" disabled ></select>
                                    <input type="hidden" id="wardName" name="wardName">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Đóng
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check me-1"></i> Lưu cửa hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admins/js/admin-store.js') }}"></script>
<script>
    // Check all
    document.getElementById('listCheckAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.store-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('delete-store').addEventListener('click', function () {
        const checkedBoxes = document.querySelectorAll('.store-checkbox:checked');

        console.log('Route:', "{{ route('admin.store.toggle') }}");
        console.log('Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        if (checkedBoxes.length === 0) {
            Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một cửa hàng.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Xác nhận',
            text: 'Bạn có chắc chắn muốn chuyển trạng thái các cửa hàng này không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Có, thực hiện',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                let storeIds = [];
                checkedBoxes.forEach(box => {
                    storeIds.push(box.value);
                });

                console.log('Stores:', storeIds);

                // Hiển thị đang xử lý khoảng 5s
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    fetch("{{ route('admin.store.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            store_ids: storeIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Thành công', data.message, 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Lỗi', data.message || 'Đã xảy ra lỗi.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Lỗi', 'Không thể gửi yêu cầu.', 'error');
                    });
                }, 500); // delay xử lý fetch khoảng 0.5s (cho hợp lý)
            }
        });
    });

</script>

@endpush


