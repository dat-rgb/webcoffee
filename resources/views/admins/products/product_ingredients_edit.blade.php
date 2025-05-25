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
        }
        .card-header {
            font-weight: 600;
            font-size: 1rem;
        }
        .ingredient-block + .ingredient-block {
            border-top: 1px dashed #ddd;
            margin-top: 1rem;
            padding-top: 1rem;
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
                <a href="{{ route('admin.vouchers.form') }}">Thêm thành phầm sản phẩm</a>
            </li>
        </ul>
    </div>
    <form id="thanh-phan-form" method="POST" action="{{ route('admin.products.ingredients.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
           <!-- Thông tin sản phẩm -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <img src="{{ $product->hinh_anh ? asset('storage/' . $product->hinh_anh) : asset('images/no_product_image.png') }}" 
                            alt="Product Image" class="img-fluid rounded mb-3" style="max-height: 180px;">
                        <h5 class="fw-bold">{{ $product->ten_san_pham }}</h5>
                        <p>Mã SP: <strong>{{ $product->ma_san_pham }}</strong></p>
                        <input type="hidden" name="ma_san_pham" value="{{ $product->ma_san_pham }}">

                        <!-- Nút Lưu & Hủy -->
                        <div class="mt-4 d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('admin.products.list') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách size + nguyên liệu -->
            <div class="col-md-8">
                @foreach ($sizes as $size)
                @php
                    $checked = in_array($size->ma_size, $selectedSizes);
                    $ingredientsForSize = $existingIngredients[$size->ma_size] ?? [null];
                @endphp

                <div class="card mb-4 shadow-sm">
                    <div class="card-header" style="background-color: #e3f2fd; color: #0d6efd;" class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2 toggle-size"
                            data-size="{{ $size->ma_size }}" id="size_{{ $size->ma_size }}"
                            {{ $checked ? 'checked' : '' }}>
                        <label class="form-check-label mb-0" for="size_{{ $size->ma_size }}">
                            Size: {{ $size->ten_size }}
                        </label>
                        <input type="hidden" name="sizes[]" value="{{ $size->ma_size }}">
                    </div>

                    <!-- Nguyên liệu -->
                    <div class="card-body ingredient-group {{ $checked ? '' : 'd-none' }}" data-size-group="{{ $size->ma_size }}">
                        @foreach ($ingredientsForSize as $item)
                        <div class="ingredient-block row align-items-end mb-3">
                            <div class="col-md-4">
                                <label>Nguyên liệu</label>
                                <select name="ingredients[{{ $size->ma_size }}][]" class="form-control">
                                    @foreach ($ingredients as $ing)
                                        <option value="{{ $ing->ma_nguyen_lieu }}"
                                            @if ($item && $item->ma_nguyen_lieu == $ing->ma_nguyen_lieu) selected @endif>
                                            {{ $ing->ten_nguyen_lieu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Định lượng</label>
                                <input type="number" name="dinh_luongs[{{ $size->ma_size }}][]" class="form-control"
                                    value="{{ $item->dinh_luong ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label>Đơn vị</label>
                                <select name="don_vis[{{ $size->ma_size }}][]" class="form-control">
                                    <option value="g" @if ($item && $item->don_vi == 'g') selected @endif>g</option>
                                    <option value="ml" @if ($item && $item->don_vi == 'ml') selected @endif>ml</option>
                                    <option value="ly" @if ($item && $item->don_vi == 'ly') selected @endif>ly</option>
                                </select>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm add-ingredient-btn">+</button>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-ingredient-btn ms-1">-</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/product-ingredient-add.js') }}"></script>
@endpush