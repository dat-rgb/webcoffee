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
        }
        .swal2-confirm {
            margin-right: 10px;
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
            <a href="{{ route('admin.vouchers.list') }}">Vouchers</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.vouchers.edit',$voucher->ma_voucher) }}">{{ $subtitle }}</a>
        </li>
        </ul>
    </div>
    <form id="voucher-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.vouchers.update', $voucher->ma_voucher) }}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <!-- Thông tin voucher -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Sửa thông tin voucher {{ $voucher->ma_voucher }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="email2">Mã voucher</label>
                                    <div class="input-icon">
                                        <input type="text" name="ma_voucher" class="form-control" placeholder="Mã voucher" value="{{ $voucher->ma_voucher }}" readonly required>
                                        @error('ma_voucher')
                                            <div class="custom-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="largeInput">Tên voucher</label>
                                    <input type="text" name="ten_voucher" class="form-control form-control" id="defaultInput" placeholder="Tên voucher" value="{{ $voucher->ten_voucher }}" required>
                                    @error('ten_voucher')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Hình ảnh</label>
                                    
                                    <!-- Nếu có hình ảnh hiện tại, hiển thị nó -->
                                    @if($voucher->hinh_anh) <!-- Kiểm tra nếu voucher có hình ảnh -->
                                        <div class="current-image">
                                            <img src="{{ asset('storage/' . $voucher->hinh_anh) }}" alt="Current Image" style="width: 150px; height: 150px;">
                                        </div>
                                    @endif
                                    
                                    <!-- Input chọn hình ảnh mới -->
                                    <input type="file" name="hinh_anh" class="form-control-file" id="exampleFormControlFile1">
                                    
                                    @error('hinh_anh')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Trạng thái</label>
                                    <select class="form-select" name="trang_thai" id="exampleFormControlSelect1">
                                        <option value="1" {{ old('trang_thai', $voucher->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="2" {{ old('trang_thai', $voucher->trang_thai) == 2 ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Giá Trị Giảm</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">%, vnd</span>
                                        <input type="number" name="gia_tri_giam" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ $voucher->gia_tri_giam }}">
                                        <span class="input-group-text">.00</span>
                                        @error('gia_tri_giam')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giảm tối đa</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">VND</span>
                                        <input type="number" name="giam_gia_max" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ $voucher->giam_gia_max }}">
                                        <span class="input-group-text">.00</span>
                                        @error('giam_gia_max')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Số lượng</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Number</span>
                                        <input type="number" name="so_luong" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ $voucher->so_luong }}">
                                        @error('so_luong')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Điều kiện áp dụng</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">VND</span>
                                        <input type="number" name="dieu_kien_ap_dung" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ $voucher->dieu_kien_ap_dung }}">
                                        <span class="input-group-text">.00</span>
                                        @error('dieu_kien_ap_dung')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ngày bắt đầu</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Date Time</span>
                                        <input type="datetime-local" name="ngay_bat_dau" class="form-control" value="{{ $voucher->ngay_bat_dau }}">
                                        @error('ngay_bat_dau')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Date Time</span>
                                        <input type="datetime-local" name="ngay_ket_thuc" class="form-control" value="{{ $voucher->ngay_ket_thuc }}">
                                        @error('ngay_ket_thuc')
                                        <div class="custom-error">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hành động -->
            <div class="card-action">
                <button type="button" class="btn btn-primary btn-update">Cập nhật</button> <!-- Nút chính -->
                <a href="{{ route('admin.vouchers.list') }}" class="btn btn-danger voucher-cancel-btn">Hủy</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
    <script src="{{ asset('admins/js/product-add.js') }}"></script>
    <script src="{{ asset('admins/js/voucher-validate-add.js') }}"></script>
@endpush