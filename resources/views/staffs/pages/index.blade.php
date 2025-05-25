@extends('layouts.staff')

@section('title', $title)
@section('subtitle', $subtitle)

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-md-6 col-lg-5">
            <div class="card card-profile shadow-sm">
                <div class="card-header" style="background-image: url('{{ asset('admins/img/blogpost.jpg') }}'); background-size: cover;">
                    <div class="profile-picture text-center mt-3">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset('admins/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle border border-white">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="user-profile text-center">
                        <div class="name fw-bold fs-4">
                            {{ Auth::guard('staff')->user()->nhanvien->ho_ten_nhan_vien ?? 'Chưa có tên' }}
                        </div>
                        <div class="job text-muted">
                            {{ ucfirst(optional(Auth::guard('staff')->user()->nhanvien->chucVu)->ten_chuc_vu) ?? 'Chức vụ chưa cập nhật' }}
                        </div>
                        <div class="desc mt-2">
                            {{ Auth::guard('staff')->user()->nhanvien->dia_chi ?? 'Chưa có địa chỉ' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tổng giờ làm việc -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                        <div class="icon-big text-center">
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
                        <div class="icon-big text-center">
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
        </div>
    </div>
</div>
@endsection
