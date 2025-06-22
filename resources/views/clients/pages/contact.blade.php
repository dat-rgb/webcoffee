@extends('layouts.app')
@section('title', $title)
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                    <h1>{{ $subtitle }}</h1>
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
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="form-title">
                    <h2>Bạn có thắc mắc hay cần hỗ trợ?</h2>
                    <p>Đội ngũ CDMT Coffee & Tea luôn sẵn sàng lắng nghe bạn! Hãy để lại lời nhắn, chúng tôi sẽ phản hồi nhanh nhất có thể.</p>
                </div>
                <div id="form_status"></div>
                <div class="contact-form">
                    <form id="contact-form" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <p>
                            <input type="hidden" id="ma_khach_hang" name="ma_khach_hang" value="{{ Auth::user()->khachHang->ma_khach_hang ?? '' }}">
                            <input type="text" placeholder="Họ và tên của bạn" name="name" id="name"value="{{ old('name', Auth::user()->khachHang->ho_ten_khach_hang ?? '') }}">
                            <input type="email" placeholder="Email liên hệ" name="email" id="email" value="{{ old('email',Auth::user()->email ?? '') }}">
                        </p>
                        <p>
                            <input type="tel" placeholder="Số điện thoại" name="phone" id="phone" value="{{ old('phone',Auth::user()->khachHang->so_dien_thoai ?? '') }}">
                            <input type="text" placeholder="Chủ đề liên hệ" name="subject" id="subject" value="{{ old('subject') }}">
                        </p>
                        <p>
                            <textarea name="message" id="message" cols="30" rows="10" placeholder="Nội dung tin nhắn của bạn...">{{ old('message') }}</textarea>
                        </p>
                        <p>
                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        </p>
                        <p><input type="submit" value="Gửi liên hệ"></p>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-form-wrap">
                    <div class="contact-form-box">
                        <h4><i class="fas fa-map"></i>Địa chỉ</h4>
                        <p>{{ $thongTinWebsite['dia_chi'] }}</p>
                    </div>  
                    <div class="contact-form-box">
                        <h4><i class="fas fa-address-book"></i>Liên hệ</h4>
                        <p>Phone: {{  $thongTinWebsite['so_dien_thoai'] }} <br> Email: {{  $thongTinWebsite['email'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end contact form -->
<!-- find our location -->
<div class="find-location blue-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <p><i class="fas fa-map-marker-alt"></i>Tìm vị trí của chúng tôi</p>
            </div>
        </div>
    </div>
</div>
<!-- end find our location -->
<!-- google map section -->
<div class="embed-responsive embed-responsive-21by9">
    {!! $thongTinWebsite['ban_do'] !!}
</div>
<!-- end google map section -->
@endsection
@push('scripts')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush
