@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

@section('content')
<div class="page-inner">
    <div class="container ">
        {{-- Toastr flash messages --}}
        @if(session('success'))
            <script>toastr.success("{{ session('success') }}");</script>
        @endif
        @if(session('error'))
            <script>toastr.error("{{ session('error') }}");</script>
        @endif
        <div class="page-header">
            <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
            <ul class="mb-3 breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-supplier">
                    <a href="{{ route('admins.supplier.index') }}">Danh Sách Nhà Cung Cấp</a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-supplier">
                    <a href="{{ route('admins.supplier.create') }}">Thêm nhà cung cấp</a>
                </li>
            </ul>
        </div>
        <div class="border-0 shadow-sm card rounded-4">
            <div class="card-header bg-light rounded-top-4">
                <h4 class="mb-0 fw-bold">Thông tin nhà cung cấp nguyên liệu </h4>
            </div>
            <div class="p-4 card-body">
                <form action="{{ route('admins.supplier.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ten_nha_cung_cap" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" id="ten_nha_cung_cap" name="ten_nha_cung_cap"
                                class="form-control @error('ten_nha_cung_cap') is-invalid @enderror"
                                value="{{ old('ten_nha_cung_cap') }}" required>
                            @error('ten_nha_cung_cap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="text" id="so_dien_thoai" name="so_dien_thoai"
                                class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                value="{{ old('so_dien_thoai') }}" required>
                            @error('so_dien_thoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="mail" class="form-label">Địa chỉ email</label>
                            <input type="email" id="mail" name="mail"
                                class="form-control @error('mail') is-invalid @enderror"
                                value="{{ old('mail') }}" required autocomplete="email">
                            @error('mail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <input type="text" id="dia_chi" name="dia_chi"
                                class="form-control @error('dia_chi') is-invalid @enderror"
                                value="{{ old('dia_chi') }}" required>
                            @error('dia_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="button" class="px-4 btn btn-primary" id="confirm-add">
                            <i class="fas fa-plus me-1"></i> Thêm nhà cung cấp
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const submitBtn = document.getElementById('confirm-add');
            const form = submitBtn.closest('form');

            submitBtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Xác nhận thêm?',
                    text: 'Bạn có chắc chắn muốn thêm nhà cung cấp này không?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, thêm ngay!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
