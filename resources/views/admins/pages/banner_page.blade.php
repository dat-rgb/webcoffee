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
                <a href="{{ route('admin.dashboard') }}">
                <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.home.banner.show') }}">{{ $subtitle }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            @foreach($banners as $position => $group)
                <form action="{{ route('admin.home.banners.updateGroup', $position) }}" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h4 class="fw-bold text-primary text-uppercase">{{ str_replace('_', ' ', $position) }}</h4>
                                    <hr>
                                </div>
                                @foreach($group as $banner)
                                    <div class="col-md-6 col-lg-3">
                                        <!-- Nút Xóa -->
                                        <form action="{{ route('admin.home.banners.destroy', $banner->id) }}" method="POST" class="form-delete-banner d-inline">
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 bg-transparent" title="Xóa banner">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </form>
                                        <div class="form-group">
                                            <label for="hinh_anh_{{ $banner->id }}"><strong>{{ $banner->vi_tri .' -  ' .$banner->thu_tu }}</strong></label>
                                            <input type="file" name="hinh_anh_{{ $banner->id }}" class="form-control-file" id="hinh_anh_{{ $banner->id }}">
                                            @error('hinh_anh_'.$banner->id)
                                                <div class="custom-error">{{ $message }}</div>
                                            @enderror
                                            @if ($banner->hinh_anh)
                                            <img src="{{ asset('storage/' . $banner->hinh_anh) }}" 
                                                alt="Banner" 
                                                style="width: 100%; max-width: 200px; height: 100px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 10px;">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="tieu_de_{{ $banner->id }}">Tiêu đề {{ $banner->vi_tri .' -  ' .$banner->thu_tu }}</label>
                                            <input type="text" name="tieu_de_{{ $banner->id }}" class="form-control" id="tieu_de_{{ $banner->id }}"
                                                value="{{ old('tieu_de_'.$banner->id, $banner->tieu_de) }}">
                                            @error('tieu_de_'.$banner->id)
                                                <div class="custom-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="noi_dung_{{ $banner->id }}">Tiêu đề phụ {{ $banner->vi_tri .' -  ' .$banner->thu_tu }}</label>
                                            <input type="text" name="noi_dung_{{ $banner->id }}" class="form-control" id="noi_dung_{{ $banner->id }}"
                                                value="{{ old('noi_dung_'.$banner->id, $banner->noi_dung) }}">
                                            @error('noi_dung_'.$banner->id)
                                                <div class="custom-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label for="link_dich_{{ $banner->id }}">Link</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </span>
                                                <input type="text" name="link_dich_{{ $banner->id }}" class="form-control"
                                                    value="{{ old('link_dich_'.$banner->id, $banner->link_dich) }}">
                                            </div>
                                            @error('link_dich_'.$banner->id)
                                                <div class="custom-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Hành động -->
                        <div class="card-action">
                            @php
                                $canAddMore = 
                                    ($position === 'top_banner' && $group->count() < 1) ||
                                    ($position === 'main_slider' && $group->count() < 6) ||
                                    ($position === 'about_section_bg' && $group->count() < 1) ||
                                    ($position === 'store_gallery' && $group->count() < 12);
                            @endphp

                            @if ($canAddMore)
                                <button type="button" class="btn btn-primary btn-open-modal" data-position="{{ $position }}">Thêm</button>
                            @endif
                            <button type="button" class="btn btn-danger btn-confirm-update">Cập nhật</button>
                        </div>
                    </div>
                </form>
            @endforeach
            <!-- Modal -->
            <div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <form action="{{ route('admin.home.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addBannerModalLabel">Thêm banner mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                    <input type="hidden" name="vi_tri" id="modal_vi_tri">

                    <div class="row">
                        <div class="col-md-6">
                        <label>Hình ảnh</label>
                        <input type="file" name="hinh_anh" class="form-control">
                        </div>

                        <div class="col-md-6">
                        <label>Tiêu đề</label>
                        <input type="text" name="tieu_de" class="form-control">
                        </div>

                        <div class="col-md-12 mt-2">
                        <label>Tiêu đề phụ</label>
                        <input type="text" name="sub_tieu_de" class="form-control">
                        </div>

                        <div class="col-md-12 mt-2">
                        <label>Link</label>
                        <input type="text" name="link_dich" class="form-control">
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.form-delete-banner').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Ngăn submit mặc định

        Swal.fire({
            title: 'Xóa banner?',
            text: "Bạn có chắc muốn xóa banner này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Nếu đồng ý thì submit form
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-open-modal').forEach(button => {
        button.addEventListener('click', function () {
            const position = this.getAttribute('data-position');
            document.getElementById('modal_vi_tri').value = position;

            // Cập nhật title cho rõ ràng
            document.getElementById('addBannerModalLabel').textContent = 'Thêm banner mới cho: ' + position.replace(/_/g, ' ').toUpperCase();

            // Mở modal
            new bootstrap.Modal(document.getElementById('addBannerModal')).show();
        });
    });
});

document.querySelectorAll('.btn-confirm-update').forEach(button => {
    button.addEventListener('click', function () {
        Swal.fire({
            title: 'Xác nhận cập nhật?',
            text: "Bạn có chắc muốn cập nhật banner này không?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Cập nhật',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
        }).then((result) => {
            if (result.isConfirmed) {
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endpush