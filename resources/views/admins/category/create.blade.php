@extends('layouts.admin')

@section('title', $title)
@section('subtitle',$subtitle)
@section('content')
<div class="page-inner">
        <div class="row">
            <div class="col-md-12">
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
                            <a href="{{ route('admins.category.index') }}">Danh mục sản phẩm</a>
                        </li>
                        <li class="separator">
                            <i class="icon-arrow-right"></i>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admins.category.create') }}">Thêm danh mục</a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow rounded-lg">
                    <div class="card-body">
                        {{-- Hiển thị lỗi nếu có --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form tạo danh mục --}}
                        <form action="{{ route('admins.category.store') }}" method="POST">
                            @csrf

                            {{-- Tên danh mục --}}
                            <div class="form-group">
                                <label for="ten_danh_muc">Tên danh mục</label>
                                <input type="text" class="form-control" id="ten_danh_muc" name="ten_danh_muc" value="{{ old('ten_danh_muc') }}" placeholder="Nhập tên danh mục">
                            </div>

                            {{-- Danh mục cha --}}
                            <div class="form-group">
                                <label for="danh_muc_cha_id">Danh mục cha</label>
                                <select class="form-control" name="danh_muc_cha_id" id="danh_muc_cha_id">
                                    <option value="">-- Không có (danh mục gốc) --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->ma_danh_muc }}" {{ old('danh_muc_cha_id') == $cat->ma_danh_muc ? 'selected' : '' }}>
                                            {{ $cat->ten_danh_muc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Mô tả --}}
                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea class="form-control" id="mo_ta" name="mo_ta" rows="4" placeholder="Nhập mô tả">{{ old('mo_ta') }}</textarea>
                            </div>
                            {{-- Trạng thái --}}
                            <div class="form-group">
                                <label for="trang_thai">Trạng thái</label>
                                <select class="form-control" name="trang_thai" id="trang_thai">
                                    <option value="1" {{ old('trang_thai') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="2" {{ old('trang_thai') == 2 ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>

                            {{-- Nút tạo mới --}}


                            <button type="submit" class="btn btn-primary">Tạo mới</button>
                            <a href="{{ route('admins.category.index') }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
