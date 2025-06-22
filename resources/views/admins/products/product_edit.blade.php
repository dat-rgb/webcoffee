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
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.product.edit.form',$product->ma_san_pham) }}">Chỉnh sửa sản phẩm {{ $product->ma_san_pham }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <form id="product-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.update', $product->ma_san_pham) }}">
            @csrf
            <div class="form-group mb-12">
                <div class="selectgroup">
                    <label class="selectgroup-item">
                        <input 
                            type="radio" 
                            name="loai_san_pham" 
                            value="0" 
                            class="selectgroup-input" 
                            {{ $product->loai_san_pham == 0 ? 'checked' : '' }} 
                            disabled>
                        <span class="selectgroup-button">Pha chế</span>
                    </label>
                    <label class="selectgroup-item">
                        <input 
                            type="radio" 
                            name="loai_san_pham" 
                            value="1" 
                            class="selectgroup-input" 
                            {{ $product->loai_san_pham == 1 ? 'checked' : '' }} 
                            disabled>
                        <span class="selectgroup-button">Đóng gói</span>
                    </label>
                </div>
            </div>

            <!-- sản phẩm pha chế -->
            <div id="formPhaChe" class="col-md-12" style="{{ old('loai_san_pham', $product->loai_san_pham) == 0 ? '' : 'display:none' }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="ma_san_pham">Mã sản phẩm</label>
                                    <div class="input-icon">
                                        <input type="text" name="ma_san_pham" class="form-control" value="{{ $product->ma_san_pham }}"  readonly>
                                        @error('ma_san_pham')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="largeInput">Tên sản phẩm</label>
                                    <input type="text" name="ten_san_pham" class="form-control form-control" id="defaultInput" placeholder="Tên sản phẩm" value="{{ old('ten_san_pham',$product->ten_san_pham) }}" >
                                    @error('ten_san_pham')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="hinh_anh">Hình ảnh</label>
                                    <input type="file" name="hinh_anh" class="form-control-file" id="hinh_anh">
                                    @error('hinh_anh')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    @if ($product->hinh_anh)
                                        <img src="{{ asset('storage/' . $product->hinh_anh) }}" alt="Ảnh sản phẩm" style="max-width: 150px; margin-top: 10px;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="ma_danh_muc">Danh mục sản phẩm</label>
                                    <select class="form-select" name="ma_danh_muc" id="ma_danh_muc">
                                        @foreach ($categorys as $cate)
                                            <option value="{{ $cate->ma_danh_muc }}" 
                                                {{ old('ma_danh_muc', $product->ma_danh_muc) == $cate->ma_danh_muc ? 'selected' : '' }}>
                                                {{ $cate->ten_danh_muc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giá</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">VND</span>
                                        <input type="number" name="gia" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                            value="{{ old('gia', $product->gia) }}">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                    @error('gia')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select class="form-select" name="trang_thai" id="trang_thai">
                                        <option value="1" {{ old('trang_thai', $product->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="2" {{ old('trang_thai', $product->trang_thai) == 2 ? 'selected' : '' }}>Ẩn</option>
                                        <option value="3" {{ old('trang_thai', $product->trang_thai) == 3 ? 'selected' : '' }}>Demo</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="mo_ta">Mô tả</label>
                                    <textarea name="mo_ta" class="form-control" id="mo_ta" rows="5">{{ old('mo_ta', $product->mo_ta) }}</textarea>
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
                                            <input type="checkbox" name="hot" value="1" class="selectgroup-input"
                                                {{ old('hot', $product->hot ?? 0) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">Hot</span>
                                        </label>

                                        <!-- New -->
                                        <input type="hidden" name="is_new" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="is_new" value="1" class="selectgroup-input"
                                                {{ old('is_new', $product->is_new ?? 0) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">New</span>
                                        </label>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                    <input type="hidden" name="loai_san_pham" value="0">
                </div>
            </div>

            <!-- sản phẩm đóng gói -->
            <div id="formDongGoi" class="col-md-12" style="{{ old('loai_san_pham', $product->loai_san_pham) == 1 ? '' : 'display:none' }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="ma_san_pham">Mã sản phẩm</label>
                                    <div class="input-icon">
                                        <input type="text" name="ma_san_pham" class="form-control" value="{{ $product->ma_san_pham }}"  readonly>
                                        @error('ma_san_pham')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nguyen_lieu">Chọn nguyên liệu bán được</label>
                                    @php
                                        $selectedNguyenLieu = old('ma_nguyen_lieu') !== null 
                                            ? old('ma_nguyen_lieu') 
                                            : optional($thanhPhans->first())->ma_nguyen_lieu;
                                    @endphp
                                    <select class="form-select" name="ma_nguyen_lieu" id="nguyen_lieu_select">
                                        <option disabled {{ $selectedNguyenLieu ? '' : 'selected' }}>-- Chọn nguyên liệu --</option>
                                        @foreach($ingredients as $nl)
                                            <option 
                                                value="{{ $nl->ma_nguyen_lieu }}" 
                                                {{ $selectedNguyenLieu == $nl->ma_nguyen_lieu ? 'selected' : '' }}
                                                data-ten="{{ $nl->ten_nguyen_lieu }}"
                                                data-gia="{{ $nl->gia }}"
                                                data-mo-ta="{{ $nl->mo_ta }}"
                                                data-hinh="{{ asset('storage/'.$nl->hinh_anh) }}">
                                                {{ $nl->ten_nguyen_lieu }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="largeInput">Tên sản phẩm</label>
                                    <input type="text" name="ten_san_pham" class="form-control form-control" id="ten_from_nguyen_lieu" placeholder="Tên sản phẩm" value="{{ old('ten_san_pham', $product->ten_san_pham) }}" >
                                    @error('ten_san_pham')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="ma_danh_muc">Danh mục sản phẩm</label>
                                    <select class="form-select" name="ma_danh_muc" id="ma_danh_muc">
                                        @foreach ($categorys as $cate)
                                            <option value="{{ $cate->ma_danh_muc }}" 
                                                {{ old('ma_danh_muc', $product->ma_danh_muc) == $cate->ma_danh_muc ? 'selected' : '' }}>
                                                {{ $cate->ten_danh_muc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giá</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">VND</span>
                                        <input type="number" name="gia" class="form-control" id="gia_from_nguyen_lieu" aria-label="Amount (to the nearest dollar)" value="{{ old('gia', $product->gia) }}">
                                        <span class="input-group-text">.00</span>
                                        @error('gia')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="hinh_anh">Hình ảnh</label>
                                    <input type="file" name="hinh_anh" class="form-control-file" id="hinh_anh">
                                    @error('hinh_anh')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    @if ($product->hinh_anh)
                                        <img src="{{ asset('storage/' . $product->hinh_anh) }}" alt="Ảnh sản phẩm" style="max-width: 150px; margin-top: 10px;">
                                    @endif
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="comment">Mô tả</label>
                                    <textarea name="mo_ta" class="form-control" id="mo_ta" rows="5">{{ old('mo_ta', $product->mo_ta) }}</textarea>
                                    @error('mo_ta')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select class="form-select" name="trang_thai" id="trang_thai">
                                        <option value="1" {{ old('trang_thai', $product->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="2" {{ old('trang_thai', $product->trang_thai) == 2 ? 'selected' : '' }}>Ẩn</option>
                                        <option value="3" {{ old('trang_thai', $product->trang_thai) == 3 ? 'selected' : '' }}>Demo</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tags</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <!-- Hot -->
                                        <input type="hidden" name="hot" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="hot" value="1" class="selectgroup-input"
                                                {{ old('hot', $product->hot ?? 0) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">Hot</span>
                                        </label>

                                        <!-- New -->
                                        <input type="hidden" name="is_new" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="is_new" value="1" class="selectgroup-input"
                                                {{ old('is_new', $product->is_new ?? 0) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">New</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="loai_san_pham" value="1">
                </div>
            </div>
            <div class="card-action mt-4">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('admin.products.list') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>  
@endsection

@push('scripts')
<script src="{{ asset('admins/js/product-add.js') }}"></script>
<script src="{{ asset('admins/js/product-validate-add.js') }}"></script>

@endpush