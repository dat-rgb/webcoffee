@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)

@section('content')
<div class="container mt-4">

    {{-- Hiện thông báo toastr hoặc session nếu có --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2>{{ $title }}</h2>

    <form action="{{ route('admins.supplier.update', $ncc->ma_nha_cung_cap) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="ten_nha_cung_cap" class="form-label">Tên nhà cung cấp</label>
            <input type="text"
                   class="form-control @error('ten_nha_cung_cap') is-invalid @enderror"
                   id="ten_nha_cung_cap"
                   name="ten_nha_cung_cap"
                   value="{{ old('ten_nha_cung_cap', $ncc->ten_nha_cung_cap) }}" required>
            @error('ten_nha_cung_cap')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
            <input type="text"
                   class="form-control @error('so_dien_thoai') is-invalid @enderror"
                   id="so_dien_thoai"
                   name="so_dien_thoai"
                   value="{{ old('so_dien_thoai', $ncc->so_dien_thoai) }}" required>
            @error('so_dien_thoai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="dia_chi" class="form-label">Địa chỉ</label>
            <input type="text"
                   class="form-control @error('dia_chi') is-invalid @enderror"
                   id="dia_chi"
                   name="dia_chi"
                   value="{{ old('dia_chi', $ncc->dia_chi) }}" required>
            @error('dia_chi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mail" class="form-label">Địa chỉ mail</label>
            <input type="email"
                   class="form-control @error('mail') is-invalid @enderror"
                   id="mail"
                   name="mail"
                   value="{{ old('mail', $ncc->mail) }}" required>
            @error('mail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush
