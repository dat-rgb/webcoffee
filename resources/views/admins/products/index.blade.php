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
                    <a href="{{ route('admin.products.list') }}">Sản phẩm</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">{{ $subtitle }}</h4>
                            <a href="{{ route('admin.products.form') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Thêm sản phẩm
                            </a>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-icon-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="add-row" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="add-row_info">
                                           
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ảnh</th>
                                                    <th>Mã SP</th>
                                                    <th>Tên SP</th>
                                                    <th>Danh mục</th>
                                                    <th>Giá</th>
                                                    <th>Trạng thái</th>
                                                    <th>Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($products->isEmpty())
                                                    <tr>
                                                        <td colspan="9" class="text-center">Không có sản phẩm nào. <a href="{{ route('admin.products.form') }}">Thêm mới.</a></td>
                                                    </tr>
                                                @else
                                                    @foreach ( $products as $pro )
                                                        <tr role="row" class="odd">
                                                            <td class="sorting_1">{{ $loop->iteration }}</td>
                                                            <td>
                                                            <img src="{{ asset('storage/' . $pro->hinh_anh) }}" alt="{{ $pro->ten_san_pham }}" width="80">
                                                            </td>
                                                            <td>{{ $pro->ma_san_pham }}</td>
                                                            <td>{{ $pro->ten_san_pham }}</td>
                                                            <td>{{ $pro->danhMuc->ten_danh_muc }}</td>
                                                            <td>{{ number_format($pro->gia, 0, ',', '.') }}</td>
                                                            <td>
                                                                @if ($pro->trang_thai == 1)
                                                                    <span class="badge badge-success">Đang bán</span>
                                                                @elseif ($pro->trang_thai == 2)
                                                                    <span class="badge badge-danger">Ngừng bán</span>
                                                                @elseif ($pro->trang_thai == 3)
                                                                    <span class="badge badge-warning">Lưu trữ</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Không xác định</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $pro->rating)
                                                                        <i class="fas fa-star"></i>  <!-- Sao đầy -->
                                                                    @elseif ($i - 0.5 == $pro->rating)
                                                                        <i class="fas fa-star-half-alt"></i>  <!-- Sao nửa -->
                                                                    @else
                                                                        <i class="far fa-star"></i>  <!-- Sao rỗng -->
                                                                    @endif
                                                                @endfor
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    @if($pro->trang_thai == 1)
                                                                        <button type="button" class="btn btn-icon btn-round btn-info" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                        <form action="{{ route('admin.product.archive', $pro->ma_san_pham) }}" method="POST" class="archive-form">
                                                                            @csrf
                                                                            <button type="button" class="btn btn-icon btn-round btn-secondary archive-btn" data-bs-toggle="tooltip" title="Lưu trữ">
                                                                                <i class="fa fa-bookmark"></i>
                                                                            </button>
                                                                        </form>
                                                                        <form action="{{ route('admin.product.hidde-or-acctive', $pro->ma_san_pham) }}" method="POST" class="hidden-or-acctive">
                                                                            @csrf    
                                                                            <button type="button" class="btn btn-icon btn-round btn-black hidden-btn" data-bs-toggle="tooltip" title="Ẩn">
                                                                                <i class="fas fa-toggle-off text-white"></i>
                                                                            </button>   
                                                                        </form>
                                                                    @elseif($pro->trang_thai == 2)

                                                                        <form action="{{ route('admin.product.hidde-or-acctive', $pro->ma_san_pham) }}" method="POST" class="acctive-form">
                                                                            @csrf
                                                                            <button type="button" class="btn btn-icon btn-round btn-warning acctive-btn" data-bs-toggle="tooltip" title="Hiển thị">
                                                                                <i class="fas fa-toggle-on text-white"></i>
                                                                            </button>
                                                                        </form>
                                                                    @elseif($pro->trang_thai == 3)
                                                                        <form action="{{ route('admin.product.archive',$pro->ma_san_pham) }}" method="POST" class="acctive-form">
                                                                            @csrf    
                                                                            <button type="button" class="btn btn-icon btn-round btn-success acctive-btn" data-bs-toggle="tooltip" title="Khôi phục">
                                                                                <i class="fas fa-undo text-white"></i>
                                                                            </button>
                                                                        </form>
                                                                        <button type="button" class="btn btn-icon btn-round btn-danger" data-bs-toggle="tooltip" title="Xóa">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    @endif
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
                                                {!! $products->links('pagination::bootstrap-5') !!}
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
