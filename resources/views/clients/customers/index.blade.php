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
            <!-- Sidebar menu -->
            <div class="col-lg-4">
                <div class="contact-form-wrap p-4 border rounded shadow-sm bg-white">
                    <h4 class="mb-4 fw-bold">Menu tài khoản</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark sidebar-link">
                                <i class="fas fa-user me-3 fs-5"></i> Hồ sơ
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark sidebar-link">
                                <i class="far fa-address-book me-3 fs-5"></i> Sổ địa chỉ
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark sidebar-link">
                                <i class="fas fa-heart me-3 fs-5"></i> Sản phẩm yêu thích
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark sidebar-link">
                                <i class="fas fa-history me-3 fs-5"></i> Lịch sử mua hàng
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark sidebar-link">
                                <i class="fas fa-eye me-3 fs-5"></i> Sản phẩm đã xem
                            </a>
                        </li>
                        <li>
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark sidebar-link">
                                <i class="fas fa-key me-3 fs-5"></i> Đổi mật khẩu
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

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

    <!-- Inline CSS -->
    <style>
        .sidebar-link:hover {
            color: #0d6efd; /* màu primary */
            background-color: #e9f0ff;
            border-radius: 6px;
            padding-left: 10px;
            transition: 0.3s;
        }
        .sidebar-link i {
            width: 24px;
            text-align: center;
        }
        body {
            background-color: #f8f9fa; /* sáng nhẹ cho toàn trang */
        }
    </style>
</div>

<!-- end contact form -->
@endsection