@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

@section('content')
<div class="page-inner">
    <div class="container ">
        {{-- THÔNG BÁO --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        {{-- TIÊU ĐỀ --}}
        <div class="page-header">
            <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
            <ul class="mb-3 breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">
                    <a href="{{ route('admins.supplier.index') }}">Danh sách nhà cung cấp</a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">
                    <span>Chỉnh sửa</span>
                </li>
            </ul>
        </div>

        {{-- FORM --}}
        <div class="border-0 shadow-sm card rounded-4">
            <div class="card-header bg-light rounded-top-4">
                <h4 class="mb-0 fw-bold">Chỉnh sửa thông tin nhà cung cấp</h4>
            </div>
            <div class="p-4 card-body">
                <form action="{{ route('admins.supplier.update', ['id' => $ncc->ma_nha_cung_cap]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Tên nhà cung cấp --}}
                        <div class="col-md-6">
                            <label for="ten_nha_cung_cap" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" name="ten_nha_cung_cap" id="ten_nha_cung_cap"
                                class="form-control @error('ten_nha_cung_cap') is-invalid @enderror"
                                value="{{ old('ten_nha_cung_cap', $ncc->ten_nha_cung_cap) }}" required>
                            @error('ten_nha_cung_cap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-md-6">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" id="so_dien_thoai"
                                class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                value="{{ old('so_dien_thoai', $ncc->so_dien_thoai) }}" required>
                            @error('so_dien_thoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="mail" class="form-label">Email</label>
                            <input type="email" name="mail" id="mail"
                                class="form-control @error('mail') is-invalid @enderror"
                                value="{{ old('mail', $ncc->mail) }}" required>
                            @error('mail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="col-md-6">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <input type="text" name="dia_chi" id="dia_chi"
                                class="form-control @error('dia_chi') is-invalid @enderror"
                                value="{{ old('dia_chi', $ncc->dia_chi) }}" required>
                            @error('dia_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nút --}}
                    <div class="mt-4 text-end">
                        <button type="submit" class="px-4 btn btn-primary">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                        <a href="{{ route('admins.supplier.index') }}" class="btn btn-secondary ms-2">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush


