@extends('layouts.admin')
@section('title',$title)
@section('subtitle',$subtitle)
@push('styles')
    <style>
        .toast-error {
            background-color: #ff0000 !important;
            color: #ffffff !important;
        }
        .custom-error{
            color: red;
            font-size: 0.875rem; /* Cỡ chữ phù hợp cho mobile */
            margin-top: 0.25rem; /* Khoảng cách từ trường input */
            display: block;
            word-wrap: break-word;
        }
    </style>
@endpush
@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">{{ $subtitle }}</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('admin') }}">
                <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.thongTinWebSite') }}">{{ $subtitle }}</a>
            </li>
        </ul>
    </div>
    <form id="thongTinWebsite-edit-form" method="POST" enctype="multipart/form-data" action="">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <!-- Thông tin công ty -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="ten_website">Tên website</label>
                                    <input type="text" name="ten_website" class="form-control" id="ten_website" placeholder="Tên công ty" 
                                        value="{{ old('ten_website', $thongTinWebsite['ten_website']) }}" required>
                                    @error('ten_website')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="ten_cong_ty">Tên công ty</label>
                                    <input type="text" name="ten_cong_ty" class="form-control" id="ten_cong_ty" placeholder="Tên công ty" 
                                        value="{{ old('ten_cong_ty', $thongTinWebsite['ten_cong_ty']) }}" required readonly>
                                    @error('ten_cong_ty')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="so_dien_thoai">Số điện thoại</label>
                                    <input type="text" name="so_dien_thoai" class="form-control" id="so_dien_thoai" placeholder="Số điện thoại" 
                                        value="{{ old('so_dien_thoai', $thongTinWebsite['so_dien_thoai']) }}" required>
                                    @error('so_dien_thoai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Email" 
                                        value="{{ old('email', $thongTinWebsite['email']) }}" required>
                                    @error('email')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                 <div class="form-group">
                                    <label for="dia_chi">Địa chỉ</label>
                                    <textarea name="dia_chi" class="form-control" id="dia_chi" rows="2">{{ old('dia_chi', $thongTinWebsite['dia_chi']) }}</textarea>
                                    @error('dia_chi')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror   
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" name="logo" class="form-control-file" id="logo">
                                    @error('logo')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    @if ($thongTinWebsite->logo)
                                        <img src="{{ asset('images/'. $thongTinWebsite['logo']) }}" alt="Logo công ty" style="max-width: 200px; margin-top: 10px;">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="	favicon">Favicon</label>
                                    <input type="file" name="favicon" class="form-control-file" id="favicon">
                                    @error('favicon')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    @if ($thongTinWebsite->	favicon)
                                        <img src="{{ asset('images/'.  $thongTinWebsite['favicon']) }}" alt="Logo công ty" style="max-width: 100px; margin-top: 10px;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="facebook_url">FaceBook</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <img src="{{ asset('images/website/icon_fb.png') }}" alt="Facebook" width="20">
                                        </span>
                                        <input type="text" name="facebook_url" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                            value="{{ old('facebook_url', $thongTinWebsite->facebook_url) }}">
                                    </div>
                                    @error('facebook_url')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="instagram_url">Instagram</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <img src="{{ asset('images/website/icon_instagram.png') }}" alt="Instagram" width="20">
                                        </span>
                                        <input type="text" name="instagram_url" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                            value="{{ old('facebook_url', $thongTinWebsite->facebook_url) }}">
                                    </div>
                                    @error('instagram_url')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="zalo_url">Zalo</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <img src="{{ asset('images/website/icon_zalo.png') }}" alt="Zalo" width="20">
                                        </span>
                                        <input type="text" name="zalo_url" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                            value="{{ old('zalo_url', $thongTinWebsite->zalo_url) }}">
                                    </div>
                                    @error('zalo_url')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="youtube_url">Youtube</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <img src="{{ asset('images/website/icon_youtube.png') }}" alt="Youtube" width="20">
                                        </span>
                                        <input type="text" name="youtube_url" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                            value="{{ old('youtube_url', $thongTinWebsite->youtube_url) }}">
                                    </div>
                                    @error('youtube_url')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="tiktok_url">TikTok</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <img src="{{ asset('images/website/icon_tiktok.png') }}" alt="Youtube" width="20">
                                        </span>
                                        <input type="text" name="tiktok_url" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                            value="{{ old('tiktok_url', $thongTinWebsite->tiktok_url) }}">
                                    </div>
                                    @error('tiktok_url')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>   
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="mo_ta">Mô tả</label>
                                    <textarea name="mo_ta" class="form-control" id="mo_ta" rows="5">{{ old('mo_ta', $thongTinWebsite['mo_ta']) }}</textarea>
                                    @error('mo_ta')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tu_khoa">Từ khóa</label>
                                    <textarea name="tu_khoa" class="form-control" id="tu_khoa" rows="3">{{ old('tu_khoa', $thongTinWebsite['tu_khoa']) }}</textarea>
                                    @error('tu_khoa')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footer_text">Footer text</label>
                                    <textarea name="footer_text" class="form-control" id="footer_text" rows="2">{{ old('footer_text', $thongTinWebsite['footer_text']) }}</textarea>
                                    @error('footer_text')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hành động -->
            <div class="card-action">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/thongTinWebsite-add.js') }}"></script>
    <script src="{{ asset('admins/js/thongTinWebsite-validate-add.js') }}"></script>
@endpush