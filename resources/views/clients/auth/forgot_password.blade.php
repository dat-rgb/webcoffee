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
            <h3 class="text-center mb-4" style="color: #f28123; font-weight: bold;">Nhập email để đặt lại mật khẩu</h3>
            <form method="POST" action="{{ route('forgotPassword.send') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control rounded-start-pill" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning w-100 rounded-pill" style="background-color: #f28123; color: white; font-weight: bold;">
                    Gửi
                </button>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush

