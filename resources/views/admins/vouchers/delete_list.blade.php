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
                    <a href="{{ route('admin.vouchers.list') }}">Vouchers</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.vouchers.deleted-list') }}">Vouchers đã xóa</a>
                </li>
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
                                            placeholder="Nhập tên hoặc mã Voucher để tìm kiếm..." 
                                            value="{{ request('search') }}" 
                                            autocomplete="off"
                                        >
                                        <button type="submit" class="input-group-text bg-white">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                    </div>
                                </div>  

                                {{-- Thao tác nhanh --}}
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <button type="button" class="dropdown-item" id="restore-vouchers">Khôi phục Voucher đã xóa</button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" id="force-delete-vouchers">Xóa vĩnh viễn</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div> 
                                
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        @if($vouchers->isEmpty())
                                            <div class="text-center my-5 py-5">
                                                <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Không có voucher nào trong danh sách xóa</h5>
                                            </div>
                                        @else
                                            <table id="add-row" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>Ảnh</th>
                                                        <th>Mã Voucher</th>
                                                        <th>Tên Voucher</th>
                                                        <th>Bắt đầu</th>
                                                        <th>Kết thúc</th>
                                                        <th>Điều kiện</th>
                                                        <th>Giá trị giảm</th>
                                                        <th>Giảm tối đa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ( $vouchers as $vou )
                                                        <tr role="row" class="odd voucher-row">
                                                            <td>
                                                                <input type="checkbox" class="voucher-checkbox" value="{{ $vou->ma_voucher }}">
                                                            </td> 
                                                            <td>
                                                                <img src="{{ asset('storage/' . ($vou->hinh_anh ?? 'vouchers/voucher-default.png')) }}" alt="{{ $vou->ten_voucher }}" style="width: 60px;">
                                                            </td>
                                                            <td>{{ $vou->ma_voucher }}</td>
                                                            <td>{{ $vou->ten_voucher }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($vou->ngay_bat_dau)->format('d/m/Y H:i') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($vou->ngay_ket_thuc)->format('d/m/Y H:i') }}</td>
                                                            <td>Hóa đơn từ {{ number_format($vou->dieu_kien_ap_dung, 0, ',', '.') }} </td>
                                                            <td>
                                                                Giảm
                                                                @if($vou->gia_tri_giam > 100)
                                                                    {{ number_format($vou->gia_tri_giam, 0, ',', '.') }} đ
                                                                @else
                                                                    {{ $vou->gia_tri_giam }} %
                                                                @endif
                                                            </td>
                                                            <td>Tối đa {{ number_format($vou->giam_gia_max, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach                                              
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                            <ul class="pagination">
                                            {{ $vouchers->links() }} {{-- pagination --}}
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
    <script src="{{ asset('admins/js/admin-voucher.js') }}"></script>
@endpush
