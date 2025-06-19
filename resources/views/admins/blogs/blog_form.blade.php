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
            <a href="{{ route('admin.blog.index') }}">Blog</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.blog.form') }}">Thêm Blog</a>
        </li>
        </ul>
    </div>
    <form id="blog-form" method="POST" enctype="multipart/form-data" Actions="{{ route('admin.blog.add') }}">
        @csrf
        <div class="row">
                <!-- Thông tin Blog -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Phần tiêu đề, sub tiêu để và nội dung -->
                            <div class="col-md-6 col-lg-8">
                                <div class="form-group">
                                    <label for="tieu_de">Tiêu đề <span style="color: red;">*</span></label>
                                    <input type="text" name="tieu_de" class="form-control" id="tieu_de" placeholder="Tiêu đề" value="{{ old('tieu_de') }}" required>
                                    @error('tieu_de')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sub_tieu_de">Nội dung ngắn</label>
                                    <input type="text" name="sub_tieu_de" class="form-control form-control" id="defaultInput" placeholder="Nội dung ngắn" value="{{ old('sub_tieu_de') }}" required>
                                    @error('sub_tieu_de')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tac_gia">Tác giả <span style="color: red;">*</span></label>
                                    <input type="text" name="tac_gia" class="form-control form-control" id="defaultInput" placeholder="Tác giả" value="{{ old('tac_gia') }}" required>
                                    @error('tac_gia')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                             <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="ma_danh_muc">Danh mục Blog <span style="color: red;">*</span></label>
                                    <select class="form-select" name="ma_danh_muc" id="exampleFormControlSelect1">
                                        <option value="" selected disabled>-- Chọn danh mục Blog --</option>
                                        @foreach ( $danhMucBlogs as $dm )
                                            <option value="{{ $dm->ma_danh_muc_blog }}">{{ $dm->ten_danh_muc_blog }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select class="form-select" name="trang_thai" id="exampleFormControlSelect1">
                                        <option value="" selected disabled>-- Chọn trạng thái --</option>
                                        <option value="1">Hiển thị</option>
                                        <option value="0">Ẩn</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="hinh_anh">Hình ảnh</label>
                                    <input type="file" name="hinh_anh" class="form-control-file" id="exampleFormControlFile1">
                                    @error('hinh_anh')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tags</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <!-- Hot -->
                                        <input type="hidden" name="hot" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="hot" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button">Hot</span>
                                        </label>

                                        <!-- New -->
                                        <input type="hidden" name="is_new" value="0">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="is_new" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button">New</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="form-group">
                                    <label for="noi_dung">Nội dung <span style="color:red">*</span></label>

                                    <textarea class="" name="noi_dung" id="noi_dung">
                                    {{ old('noi_dung') }}
                                    </textarea>
                                    @error('noi_dung')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          
            <!-- Hành động -->
            <div class="card-action">
                <button type="submit" class="btn btn-primary">Thêm</button> <!-- Nút chính -->
                <button class="btn btn-danger" onclick="window.history.back()">Hủy</button> <!-- Thoát, không gây nhầm lẫn -->
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    tinymce.init({
        selector: 'textarea#noi_dung',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        images_upload_url: '{{ route('tinymce.upload') }}',
        images_upload_credentials: true, // Gửi cookie (CSRF token) kèm request

        // Cấu hình thêm để gửi CSRF token trong headers
        images_upload_handler: function (blobInfo, success, failure) {
            let xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            xhr.open('POST', '{{ route('tinymce.upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.onload = function () {
                if (xhr.status !== 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                let json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            let formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });
</script>

    <script src="{{ asset('admins/js/product-add.js') }}"></script>
    <script src="{{ asset('admins/js/blog-validate-add.js') }}"></script>
@endpush