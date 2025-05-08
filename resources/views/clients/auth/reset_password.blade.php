@extends('layouts.app')

@section('title', $title)
@push('styles')
    <style>
        .toast-error {
            background-color: #ff0000 !important;
            color: #ffffff !important;
        }
        .custom-error{
            color: red;
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
                    <p></p>
                    <h1>Đặt lại mật khẩu</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<div id="scrollTarget" class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6">
        <div class="card shadow-sm p-4" style="border-radius: 20px;">
            <h3 class="text-center mb-4" style="color: #f28123; font-weight: bold;">Đặt lại mật khẩu</h3>
            <form method="POST" action="{{ route('resetPassword.update') }}" id="reset-password-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control rounded-pill" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">Mật khẩu mới:</label>
                    <input type="password" class="form-control rounded-pill" name="password" required>
                    @error('password')
                        <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">Xác nhận mật khẩu mới:</label>
                    <input type="password" class="form-control rounded-pill" name="password_confirmation" required>
                    @error('password')
                        <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning w-100 rounded-pill" style="background-color: #f28123; color: white; font-weight: bold;">
                    Đặt lại mặt khẩu
                </button>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush

