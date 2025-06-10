@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

@push('styles')
    <style>
        .fas, .far {
            color: #f39c12;  /* Màu vàng cho sao */
            font-size: 18px;  /* Kích thước sao */
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
                    <a href="{{ route('admins.material.index') }}">Danh sách nguyên liệu</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
    <form method="GET" action="{{ url()->current() }}" class="row g-2">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                   placeholder="Tìm theo mã, tên, nhà cung cấp...">
        </div>

        <div class="col-md-3">
            <select name="loai_nguyen_lieu" class="form-select">
                <option value="">Tất cả loại nguyên liệu</option>
                <option value="0" {{ request('loai_nguyen_lieu') == '0' ? 'selected' : '' }}>Nguyên liệu</option>
                <option value="1" {{ request('loai_nguyen_lieu') == '1' ? 'selected' : '' }}>Vật liệu</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="trang_thai" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="2" {{ request('trang_thai') == '2' ? 'selected' : '' }}>Không hoạt động</option>
                <option value="3" {{ request('trang_thai') == '3' ? 'selected' : '' }}>Đã lưu trữ</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="add-row" class="table display table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">

                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Mã nguyên liệu</th>
                                                    <th>Tên nguyên liệu</th>
                                                    <th>Nhà cung cấp</th>
                                                    <th>Số lượng</th>
                                                    <th>Đơn vị</th>
                                                    <th>Giá(VNĐ)</th>
                                                    <th>Loại</th>
                                                    <th>Trạng thái</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($materials->isEmpty())
                                                    <tr>
                                                        <td colspan="9" class="text-center">Không có sản phẩm nào. <a href="{{ route('admin.products.form') }}">Thêm mới.</a></td>
                                                    </tr>
                                                @else
                                                    @foreach($materials as $item)
                                                    <tr>
                                                        <td>{{ ($materials->currentPage() - 1) * $materials->perPage() + $loop->iteration }}</td>
                                                        <td>{{ $item->ma_nguyen_lieu }}</td>
                                                        <td>{{ $item->ten_nguyen_lieu }}</td>
                                                        <td>{{ $item->nhaCungCap->ten_nha_cung_cap ?? 'Chưa có' }}</td>
                                                        <td>{{ $item->so_luong}} </td>
                                                        <td>{{ $item->don_vi }}</td>
                                                        <td>{{ number_format($item->gia) }}</td>
                                                        <td>{{ $item->loai_nguyen_lieu == 0 ? 'nguyên liệu' : 'vật liệu' }}</td>
                                                        <td style="text-align: center;">
                                                            @if($item->trang_thai == 1)
                                                                <span class="badge bg-success">Hoạt động</span>
                                                            @else
                                                                <span class="badge bg-danger">Không hoạt động</span>
                                                            @endif
                                                        </td>

                                                        <td style="min-width: 120px;">
                                                            <div class="d-flex align-items-center justify-content-start">
                                                                {{-- Bật / Tắt hoạt động --}}
                                                                <form action="{{ route('admins.material.toggleStatus', $item->id) }}" method="POST" style="margin-right: 8px;">
                                                                    @csrf
                                                                    <div class="m-0 form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" id="statusToggle{{ $item->id }}"
                                                                            {{ $item->trang_thai == 1 ? 'checked' : '' }}
                                                                            onclick="this.form.submit();">
                                                                    </div>
                                                                </form>

                                                                {{-- chỉnh sửa --}}
                                                                <a href="{{ route('admins.material.edit', $item->ma_nguyen_lieu) }}" class="p-0 btn btn-sm me-4">
                                                                    <i class="fas fa-cog text-warning"></i>
                                                                </a>

                                                                {{-- lưu trữ --}}
                                                                <form action="{{ route('admins.material.archive', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn lưu trữ nguyên liệu này?')" style="margin: 0;">
                                                                    @csrf
                                                                    <button type="submit" class="p-0 btn btn-sm" style="border: none; background: none;">
                                                                        <i class="fas fa-archive text-secondary" title="Lưu trữ"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>



                                                    </tr>
                                                    @endforeach
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                            <ul class="pagination">
                                                {!! $materials->links('pagination::bootstrap-5') !!}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end dataTables_wrapper -->
                        </div> <!-- end table-responsive -->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush

