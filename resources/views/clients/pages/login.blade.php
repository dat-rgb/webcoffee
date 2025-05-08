@extends('layouts.app')

@section('title', $title)
@push('styles')
    <style>
        .toast-error {
            background-color: #ff0000 !important;
            color: #ffffff !important;
        }
    </style>
@endpush
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Welcome Back</p>
                    <h1>Login</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
 
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6">
        <div class="card shadow-sm p-4" style="border-radius: 20px;">
            <h3 class="text-center mb-4" style="color: #f28123; font-weight: bold;">Đăng Nhập</h3>
            <form method="POST" action="{{ route('login.post') }}" id="login-form">
                @csrf

                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control rounded-pill" name="email" required autofocus>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" class="form-control rounded-pill" name="password" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <button type="submit" class="btn btn-warning w-100 rounded-pill" style="background-color: #f28123; color: white; font-weight: bold;">
                    Đăng nhập
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-decoration-none" style="color: #f28123;">Chưa có tài khoản? Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush