@extends('layouts.staff')

@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
    th {
        white-space: nowrap;
        font-size: 14px;
        padding: 8px 10px;
        text-align: center;
    }
</style>
@endpush
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
            <ul class="mb-3 breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('staff') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('staff.productStore',['status' => 1]) }}">Sản phẩm</a>
                </li>
                @if(request()->input('status') == 0)
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('staff.productStore', ['status' => 0]) }}">Sản phẩm đã ẩn</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="row g-2 align-items-center">
                                {{-- Tìm kiếm --}}
                                <div class="col-12 col-lg-4">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control"
                                            placeholder="Nhập tên hoặc mã sản phẩm để tìm kiếm..."
                                            value="{{ request('search') }}"
                                            autocomplete="off"
                                        >
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                        <button type="submit" class="bg-white input-group-text">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Thao tác nhanh --}}
                                <div class="col-6 col-lg-2">
                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(request()->input('status') == 0)
                                                <li><button type="button" class="dropdown-item" id="show-products">Hiển thị các sản phẩm đã chọn</button></li>
                                            @else
                                                <li><button type="button" class="dropdown-item" id="hide-products">Ẩn các sản phẩm đã chọn</button></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-6 col-lg-2">
                                    @if(request()->input('status') == 0)
                                        <a href="{{ route('staff.productStore', ['status' => 1]) }}" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-eye-fill me-1"></i> Sản phẩm hiển thị
                                        </a>
                                    @else
                                        <a href="{{ route('staff.productStore', ['status' => 0]) }}" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-eye-slash-fill me-1"></i> Sản phẩm ẩn
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                    @if($products->isEmpty())
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-box-open fa-3x text-muted"></i>
                            @if($search != null)
                                <h5 class="text-muted">Không tìm thấy sản phẩm nào cho từ khóa "{{ $search }}"</h5>
                            @else
                                <h5 class="text-muted">Không có sản phẩm nào trong danh sách</h5>
                            @endif
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="add-row" class="table display table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>Ảnh</th>
                                                        <th>Mã SP</th>
                                                        <th>Tên SP</th>
                                                        <th>Danh mục</th>
                                                        <th>T.thái</th>
                                                        <th>Tình trạng nguyên liệu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($products as $pro)
                                                        <tr class="product-row">
                                                            <td>
                                                                <input type="checkbox" class="product-checkbox" value="{{ $pro->ma_san_pham }}">
                                                            </td>
                                                            <td>
                                                                <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="{{ $pro->ten_san_pham }}" width="80">
                                                            </td>
                                                            <td>{{ $pro->ma_san_pham }}</td>
                                                            <td>{{ $pro->ten_san_pham }}</td>
                                                            <td>{{ $pro->danhMuc->ten_danh_muc }}</td>
                                                            @php
                                                                $maCuaHang = session('staff')->nhanVien->ma_cua_hang ?? null;
                                                                $spCuaHang = $pro->sanPhamCuaHang->firstWhere('ma_cua_hang', $maCuaHang);
                                                                $trangThai = optional($spCuaHang)->trang_thai;
                                                            @endphp
                                                            <td>
                                                                @if (request('status') === '1')
                                                                    <span class="badge bg-success">Hiển thị</span>
                                                                @else
                                                                    <span class="badge bg-danger">Ẩn</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $uniqueAlerts = collect($pro->ingredientAlerts)->unique(fn($item) => $item['ma_nguyen_lieu'] ?? $item['ten_nguyen_lieu']);
                                                                @endphp

                                                                @if ($uniqueAlerts->isNotEmpty())
                                                                    <ul class="text-warning mb-0 ps-3">
                                                                        @foreach ($uniqueAlerts as $alert)
                                                                            <li style="list-style-type: disc">
                                                                                {{ $alert['ten_nguyen_lieu'] ?? $alert['ma_nguyen_lieu'] }} – 
                                                                                còn {{ $alert['so_luong_ton'] }} {{ $alert['don_vi'] }}
                                                                                (min {{ $alert['so_luong_min'] }})
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <span class="text-success">Đủ</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                                <ul class="pagination">
                                                    {{ $products->appends(request()->query())->links() }}

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end dataTables_wrapper -->
                            </div> <!-- end table-responsive -->
                        </div> <!-- end card-body -->
                    @endif
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Bấm vào checkbox "checkAll"
    $('#checkAll').on('change', function () {
        $('.product-checkbox').prop('checked', this.checked);
    });

    // Bỏ chọn 1 ô -> bỏ luôn "Check All"
    $('.product-checkbox').on('change', function () {
        $('#checkAll').prop('checked', $('.product-checkbox').length === $('.product-checkbox:checked').length);
    });

    // Click vào hàng để check/uncheck
    $('.product-row').on('click', function (e) {
        if (!$(e.target).is('input')) {
            const checkbox = $(this).find('.product-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        }
    });

    function getCheckedProductIds() {
        return $('.product-checkbox:checked').map(function () {
            return $(this).val();
        }).get();
    }
    
    async function handleChangeStatus(newStatus) {
        const selectedIds = getCheckedProductIds();

        if (selectedIds.length === 0) {
            return Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn sản phẩm nào',
                text: 'Vui lòng chọn ít nhất một sản phẩm!',
                confirmButtonText: 'OK'
            });
        }

        const confirm = await Swal.fire({
            icon: 'question',
            title: 'Bạn có chắc?',
            text: `Bạn muốn ${newStatus === 1 ? 'hiển thị' : 'ẩn'} các sản phẩm đã chọn?`,
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        });

        if (!confirm.isConfirmed) return;

        Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Gửi request (AJAX hoặc form)
        try {
            const res = await fetch("{{ route('staff.productStore.update-status') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    products: selectedIds,
                    status: newStatus
                })
            });

            const data = await res.json();

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: data.message || 'Cập nhật trạng thái thành công!',
                }).then(() => location.reload());
            } else {
                throw new Error(data.message || 'Đã xảy ra lỗi');
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: err.message
            });
        }
    }

    $('#show-products').on('click', () => handleChangeStatus(1));
    $('#hide-products').on('click', () => handleChangeStatus(0));
</script>
@endpush
