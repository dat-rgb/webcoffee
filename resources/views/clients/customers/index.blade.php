@extends('layouts.app')
@section('title', $title)
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>Customer</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
<!-- contact form -->
<div class="contact-from-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            @include('clients.customers.sub_layout_customer')
            <!-- Main content -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <h3 class="mb-4">Xin chào, {{ $taiKhoan->khachHang->ho_ten_khach_hang }}</h3>
                <div class="p-4 bg-white border rounded shadow-sm">
                    <h4 class="mb-3 fw-semibold">Thông tin chi tiết</h4>
                    <p><strong>Email:</strong> {{ $taiKhoan->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $taiKhoan->khachHang->so_dien_thoai ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $taiKhoan->khachHang->dia_chi ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end contact form -->
@endsection