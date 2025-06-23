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
            font-size: 0.875rem; 
            margin-top: 0.25rem; 
            display: block;
            word-wrap: break-word;
        }
        th {
            white-space: nowrap;
            font-size: 14px;
            padding: 8px 10px;
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">{{ $subtitle }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
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
                    <a href="{{ route('admin.blog.edit.show', $blog->ma_blog) }}">{{ \Illuminate\Support\Str::limit($blog->tieu_de, 40) }}</a>
                </li>
            </ul>
        </div>
        <form id="blog-form-edit" method="POST" enctype="multipart/form-data" Actions="{{ route('admin.blog.update',$blog->ma_blog) }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <!-- Thông tin Blog -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Phần tiêu đề, sub tiêu để và nội dung -->
                                <div class="col-md-6 col-lg-8">
                                    <div class="form-group">
                                        <label for="tieu_de">Tiêu đề <span style="color: red;">*</span></label>
                                        <input type="text" name="tieu_de" class="form-control" id="tieu_de" placeholder="Tiêu đề" value="{{ old('tieu_de', $blog->tieu_de) }}">
                                        @error('tieu_de')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="sub_tieu_de">Nội dung ngắn</label>
                                        <input type="text" name="sub_tieu_de" class="form-control form-control" id="defaultInput" placeholder="Nội dung ngắn" value="{{ old('sub_tieu_de', $blog->sub_tieu_de) }}">
                                        @error('sub_tieu_de')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tac_gia">Tác giả <span style="color: red;">*</span></label>
                                        <input type="text" name="tac_gia" class="form-control form-control" id="defaultInput" placeholder="Tác giả" value="{{ old('tac_gia', $blog->tac_gia) }}">
                                        @error('tac_gia')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tags</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <!-- Hot -->
                                            <input type="hidden" name="hot" value="0">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="hot" value="1" class="selectgroup-input"
                                                    {{ old('hot', $blog->hot) == 1 ? 'checked' : '' }}>
                                                <span class="selectgroup-button">Hot</span>
                                            </label>

                                            <!-- New -->
                                            <input type="hidden" name="is_new" value="0">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="is_new" value="1" class="selectgroup-input"
                                                    {{ old('is_new', $blog->is_new) == 1 ? 'checked' : '' }}>
                                                <span class="selectgroup-button">New</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="ma_danh_muc">Danh mục Blog <span style="color: red;">*</span></label>
                                        <select class="form-select" name="ma_danh_muc" id="exampleFormControlSelect1">
                                            <option value="" selected disabled>-- Chọn danh mục Blog --</option>
                                            @foreach ( $danhMucBlogs as $dm )
                                                <option value="{{ $dm->ma_danh_muc_blog }}"
                                                    {{ (old('ma_danh_muc', $blog->ma_danh_muc_blog) == $dm->ma_danh_muc_blog) ? 'selected' : '' }}>
                                                    {{ $dm->ten_danh_muc_blog }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="trang_thai">Trạng thái</label>
                                        <select class="form-select" name="trang_thai" id="exampleFormControlSelect1">
                                            <option value="" disabled {{ is_null(old('trang_thai', $blog->trang_thai)) ? 'selected' : '' }}>-- Chọn trạng thái --</option>
                                            <option value="1" {{ old('trang_thai', $blog->trang_thai) == '1' ? 'selected' : '' }}>Hiển thị</option>
                                            <option value="0" {{ old('trang_thai', $blog->trang_thai) == '0' ? 'selected' : '' }}>Ẩn</option>
                                        </select>
                                        @error('trang_thai')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="hinh_anh">Hình ảnh đại diện</label>
                                        <input type="file" name="hinh_anh" class="form-control-file" id="exampleFormControlFile1">
                                        @if ($blog->hinh_anh)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $blog->hinh_anh) }}" alt="Hình ảnh hiện tại" style="max-width: 200px; border-radius: 8px;">
                                            </div>
                                        @endif

                                        @error('hinh_anh')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label for="noi_dung">Nội dung <span style="color:red">*</span></label>
                                        <textarea class="" name="noi_dung" id="noi_dung">
                                        {{ old('noi_dung', $blog->noi_dung) }}
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
                        <button type="submit" class="btn btn-primary">Cập nhật</button> 
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-danger">Hủy</a>
                    </div>  
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
        height: 500,
        images_upload_credentials: true,

        images_upload_handler: function (blobInfo, success, failure) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('tinymce.upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.withCredentials = true;

            xhr.onload = function () {
                if (xhr.status !== 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                let json;
                try {
                    json = JSON.parse(xhr.responseText);
                } catch (e) {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                if (!json || typeof json.location !== 'string') {
                    failure('Invalid response format');
                    return;
                }

                success(json.location);
            };

            xhr.onerror = function () {
                failure('Image upload failed due to a network error.');
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });
</script>
<script src="{{ asset('admins/js/blog-validate-add.js') }}"></script>
@endpush