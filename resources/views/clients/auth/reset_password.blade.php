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
                    <p>Coffee & Tea</p>
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
                    <input type="email" class="form-control rounded-start-pill" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">Mật khẩu:</label>
                    <div class="input-group">
                        <input type="password" class="form-control rounded-start-pill" id="password" name="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-white border-start-0 rounded-end-pill" style="cursor: pointer;" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">Xác nhận mật khẩu:</label>
                    <div class="input-group">
                        <input type="password" class="form-control rounded-start-pill" id="password_confirmation" name="password_confirmation" required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-white border-start-0 rounded-end-pill" style="cursor: pointer;" id="togglePasswordConfirmation">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    @error('password_confirmation')
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
    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.querySelector(`#${iconId} i`);

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }

        document.getElementById('togglePassword').addEventListener('click', function () {
            toggleVisibility('password', 'togglePassword');
        });

        document.getElementById('togglePasswordConfirmation').addEventListener('click', function () {
            toggleVisibility('password_confirmation', 'togglePasswordConfirmation');
        });
    </script>
@endpush

