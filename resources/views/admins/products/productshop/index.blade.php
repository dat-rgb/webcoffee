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
                <a href="{{ route('admin') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Sản phẩm</a>
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
                        <div class="col-12 col-lg-6"> 
                            <div class="input-group">
                                <input  type="text"  id="searchInput" name="search"  class="form-control"  placeholder="Nhập mã đơn hàng hoặc tên khách hàng..."  autocomplete="off">
                                <button type="button" class="btn btn-outline-secondary" id="searchBtn">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Chọn cửa hàng -->
                        <div class="col-12 col-lg-4">
                            <select name="ma_cua_hang" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Chọn cửa hàng --</option>
                                @foreach($cuaHangs as $ch)
                                    <option value="{{ $ch->ma_cua_hang }}" {{ request('ma_cua_hang') == $ch->ma_cua_hang ? 'selected' : '' }}>
                                        {{ $ch->ten_cua_hang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thêm sản phẩm vào cửa hàng --}}
                        <div class="col-6 col-lg-2">
                            <button type="button" 
                                class="btn btn-primary w-100 {{ empty(request('ma_cua_hang')) ? 'disabled' : '' }}" 
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
                        @endphp
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có sản phẩm nào tại cửa hàng {{ $storeName }}</h5>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>Ảnh</th>
                                            <th>Mã SP</th>
                                            <th>Tên SP</th>
                                            <th>Danh mục</th>
                                        </tr>
                                    </thead>  
                                    <tbody id="order-tbody">
                                        @foreach ( $productShop as $pro )
                                            <tr role="" class="product-row">
                                                <td>
                                                    <input type="checkbox" class="product-checkbox" value="{{ $pro->sanPham->ma_san_pham }}">
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.product.edit.form',$pro->sanPham->ma_san_pham) }}" class="" data-bs-toggle="tooltip" title="{{ $pro->sanPham->ten_san_pham }}">
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
@endsection

@push('scripts')
<script>
//search product
document.getElementById('modalSearchBtn').addEventListener('click', function () {
    let input = document.getElementById('modalSearchInput').value.toLowerCase();
    let rows = document.querySelectorAll('#productModalTableBody .product-row');
    let found = false;

    rows.forEach(row => {
        let name = row.querySelector('.product-name').textContent.toLowerCase();
        let category = row.querySelector('.product-catgory-name').textContent.toLowerCase();
        let isMatch = name.includes(input) || category.includes(input);
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) found = true;
    });

    // Hiển thị dòng "không tìm thấy"
    document.getElementById('noProductFoundRow').style.display = found ? 'none' : '';
});

// Tự động tìm khi gõ
document.getElementById('modalSearchInput').addEventListener('input', function () {
    document.getElementById('modalSearchBtn').click();
});

//Check product
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addProductModal');

    modal.addEventListener('shown.bs.modal', function () {
        const checkAll = modal.querySelector('#modalCheckAll');
        const checkboxes = modal.querySelectorAll('.product-checkbox');
        const rows = modal.querySelectorAll('.product-row');

        if (!checkAll) return;

        // Check All
        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Toggle khi click vào hàng
        rows.forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.tagName.toLowerCase() === 'input') return;
                const checkbox = this.querySelector('.product-checkbox');
                checkbox.checked = !checkbox.checked;
                checkAll.checked = [...checkboxes].every(cb => cb.checked);
            });
        });

        // Update trạng thái checkAll nếu click từng cái
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                checkAll.checked = [...checkboxes].every(c => c.checked);
            });
        });
    });
});

//Add
document.getElementById('submitAddProducts').addEventListener('click', function () {
    const selected = document.querySelectorAll('.product-checkbox:checked');

    // Nếu không chọn sản phẩm nào
    if (selected.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn sản phẩm!',
            text: 'Vui lòng chọn ít nhất 1 sản phẩm để tiếp tục.',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Nếu đã chọn sản phẩm → xác nhận
    Swal.fire({
        title: 'Xác nhận thêm sản phẩm',
        html: `Bạn chắc chắn muốn thêm <strong>${selected.length}</strong> sản phẩm vào cửa hàng?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'OK, thêm vào!',
        cancelButtonText: 'Hủy',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Hiển thị đang xử lý
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ giây lát.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form sau delay để hiển thị đang xử lý mượt mà
            setTimeout(() => {
                document.getElementById('addProductsForm').submit();
            }, 1000);
        }
    });
});
</script>

@endpush


