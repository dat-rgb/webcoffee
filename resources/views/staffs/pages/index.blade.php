

@extends('layouts.staff')

@section('title', $title)
@section('subtitle', $subtitle)

@section('content')

@php
    $nv = Auth::guard('staff')->user()->nhanvien;
    $tk = Auth::guard('staff')->user();
@endphp

<div class="page-inner">
    <div class="row">
        <div class="col-12">
            <div class="shadow-sm card">
                <div class="card-body">
                    <div class="row">
                        <!-- Bên trái: avatar + info -->
                        <div class="text-center col-md-4 border-end">
                            <div class="mb-3">
                                <img src="{{ asset('admins/img/profile.jpg') }}" class="border rounded-circle" width="120" height="120">
                            </div>
                            <h4 class="fw-bold">{{ $nv->ho_ten_nhan_vien }}</h4>
                            <div class="mb-1 text-muted">
                                {{ ucfirst(optional($nv->chucVu)->ten_chuc_vu) ?? 'Chức vụ chưa cập nhật' }}
                            </div>
                            <div class="mb-1 text-muted">
                                {{ optional($nv->cuaHang)->ten_cua_hang ?? 'Cửa hàng chưa cập nhật' }}
                            </div>
                            <!-- Tổng giờ làm việc -->
                            <!-- <div class="mt-4 row">
                                <div class="col-6">
                                    <div class="p-3 text-center border rounded shadow-sm">
                                        <div class="mb-2 icon-big text-warning">
                                            <i class="icon-pie-chart fs-3"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Giờ đã làm</div>
                                            <div class="fs-5 fw-bold text-dark">120 giờ</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 text-center border rounded shadow-sm">
                                        <div class="mb-2 icon-big text-success">
                                            <i class="icon-wallet fs-3"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Lương</div>
                                            <div class="fs-5 fw-bold text-dark">5.000.000 đ</div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <!-- Bên phải: form cập nhật -->
                        <div class="col-md-8">
                            <h5 class="mb-3 fw-bold">Chỉnh sửa thông tin cá nhân</h5>
                            <form id="profileForm" action="{{ route('staff.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror" value="{{ old('ho_ten', $nv->ho_ten_nhan_vien) }}" required>
                                        @error('ho_ten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror" value="{{ old('so_dien_thoai', $nv->so_dien_thoai) }}" required>
                                        @error('so_dien_thoai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email tài khoản</label>
                                        <input type="email" class="form-control" value="{{ $tk->email }}" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Ngày sinh</label>
                                        <input type="date" name="ngay_sinh" class="form-control @error('ngay_sinh') is-invalid @enderror" value="{{ old('ngay_sinh', $nv->ngay_sinh) }}" required>
                                        @error('ngay_sinh')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" name="dia_chi" class="form-control @error('dia_chi') is-invalid @enderror" value="{{ old('dia_chi', $nv->dia_chi) }}">
                                        @error('dia_chi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label d-block">Giới tính</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('gioi_tinh') is-invalid @enderror" type="radio" name="gioi_tinh" id="nam" value="1" {{ old('gioi_tinh', $nv->gioi_tinh) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="nam">Nam</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('gioi_tinh') is-invalid @enderror" type="radio" name="gioi_tinh" id="nu" value="0" {{ old('gioi_tinh', $nv->gioi_tinh) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="nu">Nữ</label>
                                        </div>
                                        @error('gioi_tinh')
                                            <div class="mt-1 text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- end form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('profileForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Ngăn submit mặc định

            Swal.fire({
                title: 'Bạn có chắc muốn cập nhật?',
                text: "Thông tin cá nhân sẽ được thay đổi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cập nhật',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Chấp nhận => Submit form
                }
            });
        });
    });
</script>
@endpush












<!-- Tổng giờ làm việc -->
        {{-- <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                        <div class="text-center icon-big">
                            <i class="icon-pie-chart text-warning"></i>
                        </div>
                        </div>
                        <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Giờ đã làm</p>
                            <h4 class="card-title">120 giờ</h4>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                        <div class="text-center icon-big">
                            <i class="icon-wallet text-success"></i>
                        </div>
                        </div>
                        <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Lương </p>
                            <h4 class="card-title">5.000.000 đ</h4>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
