@extends('layouts.admin')
@section('title',$title)
@section('subtitle',$subtitle)
@push('styles')
    <style>
        .toast-error {
            background-color: #ff0000 !important;
            color: #ffffff !important;
        }
        .custom-error{
            color: red;
            font-size: 0.875rem; /* Cỡ chữ phù hợp cho mobile */
            margin-top: 0.25rem; /* Khoảng cách từ trường input */
            display: block;
            word-wrap: break-word;
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
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.products.form') }}">Thêm sản phẩm</a>
        </li>
        </ul>
    </div>
    <form id="product-form" method="POST" enctype="multipart/form-data" Actions="{{ route('admin.products.add') }}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <!-- Thông tin sản phẩm -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="email2">Mã sản phẩm</label>
                                    <div class="input-icon">
                                        <input type="text" name="ma_san_pham" class="form-control" value="{{ $newCode }}"  readonly>
                                        @error('ma_san_pham')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="largeInput">Tên sản phẩm</label>
                                    <input type="text" name="ten_san_pham" class="form-control form-control" id="defaultInput" placeholder="Tên sản phẩm" value="{{ old('ten_san_pham') }}" required>
                                    @error('ten_san_pham')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Hình ảnh</label>
                                    <input type="file" name="hinh_anh" class="form-control-file" id="exampleFormControlFile1">
                                    @error('hinh_anh')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Danh mục sản phẩm</label>
                                    <select class="form-select" name="ma_danh_muc" id="exampleFormControlSelect1">
                                        <option value="" selected disabled>-- Chọn danh mục sản phẩm --</option>
                                        @foreach ( $categorys as $cate )
                                            <option value="{{ $cate->ma_danh_muc }}">{{ $cate->ten_danh_muc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giá</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">VND</span>
                                        <input type="number" name="gia" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ old('gia') }}">
                                        <span class="input-group-text">.00</span>
                                        @error('gia')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Trạng thái</label>
                                    <select class="form-select" name="trang_thai" id="exampleFormControlSelect1">
                                        <option value="" selected disabled>-- Chọn trạng thái --</option>
                                        <option value="1">Hiển thị</option>
                                        <option value="2">Ẩn</option>
                                        <option value="3">Demo</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="comment">Mô tả</label>
                                    <textarea name="mo_ta" class="form-control" id="comment" rows="5"></textarea>
                                    @error('mo_ta')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tags</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <!-- Hot -->
                                        <input type="hidden" name="hot" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="hot" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button">Hot</span>
                                        </label>

                                        <!-- New -->
                                        <input type="hidden" name="is_new" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="is_new" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button">New</span>
                                        </label>

                                        <!-- Sản phẩm đóng gói -->
                                        <input type="hidden" name="san_pham_pha_che" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="san_pham_dong_goi" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button">Đóng gói</span>
                                        </label>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hành động -->
            <div class="card-action">
                <button type="submit" class="btn btn-primary">Thêm</button> <!-- Nút chính -->
                <button class="btn btn-danger" onclick="window.history.back()">Hủy</button> <!-- Thoát, không gây nhầm lẫn -->
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/product-add.js') }}"></script>
    <script src="{{ asset('admins/js/product-validate-add.js') }}"></script>
@endpush