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
                    <div class="card-header">
                        <div class="card-title">Nhập thông tin sản phẩm</div>
                    </div>
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
                                    <label for="exampleFormControlSelect1">Danh mục sản phẩm</label>
                                    <select class="form-select" name="ma_danh_muc" id="exampleFormControlSelect1">
                                        @foreach ( $categorys as $cate )
                                            <option value="{{ $cate->ma_danh_muc }}">{{ $cate->ten_danh_muc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Hình ảnh</label>
                                    <input type="file" name="hinh_anh" class="form-control-file" id="exampleFormControlFile1">
                                    @error('hinh_anh')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
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
                                        <option value="1">Đang bán</option>
                                        <option value="2">Ngừng bán</option>
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
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="hot" value="Hot" class="selectgroup-input" >
                                            <span class="selectgroup-button">Hot</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="is_new" value="New" class="selectgroup-input" >
                                            <span class="selectgroup-button">New</span>
                                        </label>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
                <!-- Thành phần sản phẩm -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Nhập thành phần sản phảm</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Size Nhỏ -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Size</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" id="sizeSmallCheckbox" class="selectgroup-input">
                                            <span class="selectgroup-button">Nhỏ</span>
                                        </label>
                                    </div>
                                </div>
                                <div id="ingredientContainerSmall" class="ingredient-container" style="display: none;">
                                    <div class="ingredient-form mb-3">
                                        <select class="form-select mb-2">
                                            <option>Chọn nguyên liệu</option>
                                            <option>Trà xanh</option>
                                            <option>Sữa tươi</option>
                                        </select>
                                        <input type="number" class="form-control mb-2" placeholder="Định lượng">
                                        <select class="form-select mb-2">
                                            <option>g</option>
                                            <option>ml</option>
                                            <option>ly</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success addIngredientBtn">+</button>
                                </div>
                            </div>

                            <!-- Size Vừa -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Size</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" id="sizeMediumCheckbox" class="selectgroup-input">
                                            <span class="selectgroup-button">Vừa</span>
                                        </label>
                                    </div>
                                </div>
                                <div id="ingredientContainerMedium" class="ingredient-container" style="display: none;">
                                    <div class="ingredient-form mb-3">
                                        <select class="form-select mb-2">
                                            <option>Chọn nguyên liệu</option>
                                            <option>Trà xanh</option>
                                            <option>Sữa tươi</option>
                                        </select>
                                        <input type="number" class="form-control mb-2" placeholder="Định lượng">
                                        <select class="form-select mb-2">
                                            <option>g</option>
                                            <option>ml</option>
                                            <option>ly</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success addIngredientBtn">+</button>
                                </div>
                            </div>

                            <!-- Size Lớn -->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Size</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" id="sizeLargeCheckbox" class="selectgroup-input">
                                            <span class="selectgroup-button">Lớn</span>
                                        </label>
                                    </div>
                                </div>
                                <div id="ingredientContainerLarge" class="ingredient-container" style="display: none;">
                                    <div class="ingredient-form mb-3">
                                        <select class="form-select mb-2">
                                            <option>Chọn nguyên liệu</option>
                                            <option>Trà xanh</option>
                                            <option>Sữa tươi</option>
                                        </select>
                                        <input type="number" class="form-control mb-2" placeholder="Định lượng">
                                        <select class="form-select mb-2">
                                            <option>g</option>
                                            <option>ml</option>
                                            <option>ly</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success addIngredientBtn">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hành động -->
            <div class="card-action">
                <button type="submit" class="btn btn-primary">Thêm</button> <!-- Nút chính -->
                <button class="btn btn-outline-primary">Thêm, ở lại</button> <!-- Hành động phụ -->
                <button class="btn btn-warning">Nháp</button> <!-- Hành động tạm -->
                <button class="btn btn-danger">Hủy</button> <!-- Thoát, không gây nhầm lẫn -->
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/product-add.js') }}"></script>
    <script src="{{ asset('admins/js/product-validate-add.js') }}"></script>
@endpush