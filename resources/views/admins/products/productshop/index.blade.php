@extends('layouts.admin')
@section('title',$title)
@section('subtitle',$subtitle)

@push('styles')
@endpush
@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">{{ $subtitle }}</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.products.list') }}">Sản phẩm</a>
            </li>
            @if(request('ma_cua_hang'))
                @php
                    $cuahangSelected = $cuaHangs->firstWhere('ma_cua_hang', request('ma_cua_hang'));
                @endphp
                @if($cuahangSelected)
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <span>{{ $cuahangSelected->ten_cua_hang }}</span>
                    </li>
                @endif
            @endif
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
                                placeholder="Nhập tên sản phẩm hoặc tên danh mục..." 
                                value="{{ request('search') }}" autocomplete="off">                                
                                <button type="button" class="btn btn-outline-secondary" id="searchBtn">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Chọn cửa hàng --}}
                        <div class="col-12 col-lg-3">
                            <select name="ma_cua_hang" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Chọn cửa hàng --</option>
                                @foreach($cuaHangs as $ch)
                                    <option value="{{ $ch->ma_cua_hang }}" {{ request('ma_cua_hang') == $ch->ma_cua_hang ? 'selected' : '' }}>
                                        {{ $ch->ten_cua_hang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown thao tác --}}
                        <div class="col-6 col-lg-2">
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                    Thao tác
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" id="delete-products">
                                            Xóa các sản phẩm đã chọn
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Nút thêm sản phẩm --}}
                        <div class="col-6 col-lg-2">
                            <button type="button" 
                                class="btn btn-primary w-100 {{ empty(request('ma_cua_hang') ) ? 'disabled' : '' }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addProductModal">
                                <i class="fa fa-plus"></i> Thêm sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
                @if(!request()->filled('ma_cua_hang'))
                    <div class="text-center my-5 py-5">
                        <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chọn cửa hàng để xem sản phẩm.</h5>
                    </div>
                @else
                    @if($productShop->isEmpty())
                        @php
                            $storeName = $cuaHangs->where('ma_cua_hang', request('ma_cua_hang'))->first()->ten_cua_hang ?? 'chưa xác định';
                            $hasSearch = request()->has('search') && request('search') !== '';
                        @endphp
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">
                                @if($hasSearch)
                                    Không tìm thấy sản phẩm nào tại cửa hàng {{ $storeName }} với từ khóa "{{ request('search') }}"
                                @else
                                    Không có sản phẩm nào tại cửa hàng {{ $storeName }}
                                @endif
                            </h5>
                        </div>
                    @else
                        <div class="card-body">
                            <form id="deleteProductsForm" method="POST" action="{{ route('admin.product-shop.delete') }}">
                                @csrf
                                <input type="hidden" name="ma_cua_hang" value="{{ request('ma_cua_hang') }}">
                                <div id="productIdsContainer"></div>
                                <div class="table-responsive">
                                    <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                            <table class="table table-striped table-hover align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="listCheckAll"></th>
                                                        <th>Ảnh</th>
                                                        <th>Mã SP</th>
                                                        <th>Tên SP</th>
                                                        <th>Danh mục</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="order-tbody">
                                                    @foreach ( $productShop as $pro )
                                                        <tr class="product-row">
                                                            <td>
                                                                <input type="checkbox" class="product-checkbox" value="{{ $pro->sanPham->ma_san_pham }}">
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.product.edit.form',$pro->sanPham->ma_san_pham) }}" data-bs-toggle="tooltip" title="{{ $pro->sanPham->ten_san_pham }}">
                                                                    <img src="{{ $pro->sanPham->hinh_anh ? asset('storage/' . $pro->sanPham->hinh_anh) : asset('images/no_product_image.png') }}" alt="{{ $pro->sanPham->ten_san_pham }}" width="80">
                                                                </a>
                                                            </td>
                                                            <td>{{ $pro->sanPham->ma_san_pham }}</td>
                                                            <td>{{ $pro->sanPham->ten_san_pham }}</td>
                                                            <td>{{ $pro->sanPham->danhMuc->ten_danh_muc }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-5">
                                            <div class="dataTables_info">
                                                Hiển thị {{ $productShop->firstItem() }} đến {{ $productShop->lastItem() }} của {{ $productShop->total() }} sản phẩm
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                                <ul class="pagination justify-content-end mb-0">
                                                    {{-- Previous Page --}}
                                                    <li class="paginate_button page-item {{ $productShop->onFirstPage() ? 'disabled' : '' }}">
                                                        <a href="{{ $productShop->appends(request()->query())->previousPageUrl() ?? '#' }}" class="page-link">Trước</a>
                                                    </li>

                                                    {{-- Page Numbers --}}
                                                    @for ($i = 1; $i <= $productShop->lastPage(); $i++)
                                                        <li class="paginate_button page-item {{ $productShop->currentPage() == $i ? 'active' : '' }}">
                                                            <a href="{{ $productShop->url($i) }}" class="page-link">{{ $i }}</a>
                                                        </li>
                                                    @endfor

                                                    {{-- Next Page --}}
                                                    <li class="paginate_button page-item {{ !$productShop->hasMorePages() ? 'disabled' : '' }}">
                                                        <a href="{{ $productShop->appends(request()->query())->nextPageUrl() ?? '#' }}" class="page-link">Kế tiếp</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
                <!-- Modal Thêm Sản Phẩm -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="addProductModalLabel">
                                    <i class="fa fa-box-open me-2"></i>Chọn sản phẩm thêm vào cửa hàng {{ $cuahangSelected->ten_cua_hang ?? null }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                @if($productsNoShop->isEmpty())
                                    <div class="text-center text-muted">Tất cả sản phẩm đã có trong cửa hàng này.</div>
                                @else
                                    <!-- Tìm kiếm trong modal -->
                                    <div class="input-group mb-3">
                                        <input type="text" id="modalSearchInput" class="form-control" placeholder="Tìm kiếm tên sản phẩm...">
                                        <button class="btn btn-primary" type="button" id="modalSearchBtn">
                                            <i class="fa fa-search me-1"></i> Tìm
                                        </button>
                                    </div>

                                    <form id="addProductsForm" action="{{route('admin.product-shop.addtoshop')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="ma_cua_hang" value="{{ request('ma_cua_hang') }}">
                                        <table class="table table-striped table-hover align-middle text-center">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="modalCheckAll"></th>
                                                    <th>Ảnh</th>
                                                    <th>Mã SP</th>
                                                    <th>Tên SP</th>
                                                    <th>Danh mục</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productModalTableBody">
                                                @foreach ($productsNoShop as $pro)
                                                    <tr class="product-row">
                                                        <td><input type="checkbox" name="san_pham_ids[]" class="product-checkbox" value="{{ $pro->ma_san_pham }}"></td>
                                                        <td>
                                                            <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" width="80" alt="{{ $pro->ten_san_pham }}">
                                                        </td>
                                                        <td>{{ $pro->ma_san_pham }}</td>
                                                        <td class="product-name">{{ $pro->ten_san_pham }}</td>
                                                        <td class="product-catgory-name">{{ $pro->danhMuc->ten_danh_muc ?? '---' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr id="noProductFoundRow" style="display: none;">
                                                    <td colspan="5" class="text-muted text-center">Không tìm thấy sản phẩm nào theo từ khóa.</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </form>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                @if(!$productsNoShop->isEmpty())
                                    <button type="button" id="submitAddProducts" class="btn btn-success">
                                        <i class="fa fa-plus me-1"></i> Thêm vào cửa hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('searchBtn')?.addEventListener('click', function() {
    this.closest('form')?.submit();
});
document.addEventListener('DOMContentLoaded', () => {
    const modalSearchBtn  = document.getElementById('modalSearchBtn');
    const modalSearchInput= document.getElementById('modalSearchInput');
    const modalTableBody  = document.getElementById('productModalTableBody');
    const noProductRow    = document.getElementById('noProductFoundRow');

    function visibleCheckboxes(container) {
        return [...container.querySelectorAll('.product-row')]
            .filter(r => r.style.display !== 'none')
            .map(r => r.querySelector('.product-checkbox'));
    }

    if (modalSearchBtn && modalSearchInput && modalTableBody) {
        modalSearchBtn.addEventListener('click', () => {
            const kw = modalSearchInput.value.toLowerCase();
            let found = false;

            modalTableBody.querySelectorAll('.product-row').forEach(row => {
                const name     = row.querySelector('.product-name')?.textContent.toLowerCase()   || '';
                const category = row.querySelector('.product-catgory-name')?.textContent.toLowerCase() || '';
                const match = name.includes(kw) || category.includes(kw);
                row.style.display = match ? '' : 'none';
                if (match) found = true;
            });

            if (noProductRow) noProductRow.style.display = found ? 'none' : '';

            // Cập nhật trạng thái check-all sau khi tìm kiếm
            const modalCheckAll = document.getElementById('modalCheckAll');
            if (modalCheckAll) {
                modalCheckAll.checked = visibleCheckboxes(modalTableBody).every(cb => cb.checked);
            }
        });
    }

    const modal = document.getElementById('addProductModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', () => {
            const modalCheckAll = modal.querySelector('#modalCheckAll');
            const modalBody     = modal.querySelector('#productModalTableBody');
            if (!modalCheckAll || !modalBody) return;

            // Check-all chỉ check hàng đang hiển thị
            modalCheckAll.onchange = () => {
                visibleCheckboxes(modalBody).forEach(cb => cb.checked = modalCheckAll.checked);
            };

            // Click vào dòng để toggle checkbox
            modalBody.onclick = e => {
                const row = e.target.closest('.product-row');
                if (!row || e.target.tagName === 'INPUT' || e.target.closest('a')) return;

                const cb = row.querySelector('.product-checkbox');
                cb.checked = !cb.checked;

                modalCheckAll.checked = visibleCheckboxes(modalBody).every(c => c.checked);
            };

            // Khi thay đổi 1 checkbox
            modalBody.onchange = e => {
                if (e.target.classList.contains('product-checkbox')) {
                    modalCheckAll.checked = visibleCheckboxes(modalBody).every(c => c.checked);
                }
            };
        });
    }

    const addBtn = document.getElementById('submitAddProducts');
    if (addBtn) {
        addBtn.addEventListener('click', () => {
            const sel = modal?.querySelectorAll('.product-checkbox:checked') || [];
            if (!sel.length) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Chưa chọn sản phẩm!',
                    text: 'Vui lòng chọn ít nhất 1 sản phẩm.'
                });
            }

            Swal.fire({
                title: `Thêm ${sel.length} sản phẩm?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Thêm',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then(r => {
                if (!r.isConfirmed) return;

                // Fix chỗ này
                Swal.fire({
                    title: 'Đang xử lý...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                document.getElementById('addProductsForm')?.submit();
            });
        });
    }

    const listCheckAll = document.getElementById('listCheckAll');
    const listBody     = document.getElementById('order-tbody');
    if (listCheckAll && listBody) {
        listCheckAll.onchange = () => {
            listBody.querySelectorAll('.product-checkbox')
                    .forEach(cb => cb.checked = listCheckAll.checked);
        };

        listBody.onclick = e => {
            const row = e.target.closest('.product-row');
            if (!row) return;
            if (e.target.tagName === 'INPUT' || e.target.closest('a')) return;
            const cb = row.querySelector('.product-checkbox');
            cb.checked = !cb.checked;
            listCheckAll.checked = [...listBody.querySelectorAll('.product-checkbox')]
                                   .every(c => c.checked);
        };

        listBody.onchange = e => {
            if (e.target.classList.contains('product-checkbox')) {
                listCheckAll.checked = [...listBody.querySelectorAll('.product-checkbox')]
                                       .every(c => c.checked);
            }
        };
    }

    const deleteBtn = document.getElementById('delete-products');
    if (deleteBtn && listBody) {
        deleteBtn.onclick = () => {
            const checked = listBody.querySelectorAll('.product-checkbox:checked');
            if (!checked.length) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Chưa chọn sản phẩm!',
                    text: 'Vui lòng chọn ít nhất 1 sản phẩm để xóa.'
                });
            }

            Swal.fire({
                title: `Xóa ${checked.length} sản phẩm?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then(r => {
                if (!r.isConfirmed) return;
                Swal.fire({
                    title: 'Đang xử lý...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const box = document.getElementById('productIdsContainer');
                if (box) {
                    box.innerHTML = '';
                    checked.forEach(cb => {
                        const inp = document.createElement('input');
                        inp.type  = 'hidden';
                        inp.name  = 'product_ids[]';
                        inp.value = cb.value;
                        box.appendChild(inp);
                    });
                }

                document.getElementById('deleteProductsForm')?.submit();
            });
        };
    }
});
</script>
@endpush


