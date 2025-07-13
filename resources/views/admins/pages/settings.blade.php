@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống')
@section('subtitle', 'Cài đặt chung cho hoạt động website')

@push('styles')
<style>
    .custom-error {
        color: red;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
        word-wrap: break-word;
    }
</style>
@endpush

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Cài đặt hệ thống</h3>
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
                <span>Cài đặt hệ thống</span>
            </li>
        </ul>
    </div>

    <form id="admin-setting-form" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body row">

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="phi_ship">Phí ship (VNĐ)</label>
                                <input type="number" name="phi_ship" id="phi_ship" class="form-control"
                                    value="{{ old('phi_ship', $settings->phi_ship) }}" min="0">
                                @error('phi_ship')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nguong_mien_phi_ship">Ngưỡng miễn phí ship (VNĐ)</label>
                                <input type="number" name="nguong_mien_phi_ship" id="nguong_mien_phi_ship" class="form-control"
                                    value="{{ old('nguong_mien_phi_ship', $settings->nguong_mien_phi_ship) }}" min="0">
                                @error('nguong_mien_phi_ship')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="vat_mac_dinh">VAT mặc định (%)</label>
                                <input type="number" name="vat_mac_dinh" id="vat_mac_dinh" class="form-control"
                                    value="{{ old('vat_mac_dinh', $settings->vat_mac_dinh) }}" min="0" max="100">
                                @error('vat_mac_dinh')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="so_luong_toi_thieu">Số lượng tối thiểu</label>
                                <input type="number" name="so_luong_toi_thieu" id="so_luong_toi_thieu" class="form-control"
                                    value="{{ old('so_luong_toi_thieu', $settings->so_luong_toi_thieu) }}" min="1">
                                @error('so_luong_toi_thieu')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="so_luong_toi_da">Số lượng tối đa</label>
                                <input type="number" name="so_luong_toi_da" id="so_luong_toi_da" class="form-control"
                                    value="{{ old('so_luong_toi_da', $settings->so_luong_toi_da) }}" min="1">
                                @error('so_luong_toi_da')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ty_le_diem_thuong">Tỷ lệ điểm thưởng (VNĐ / 1 điểm)</label>
                                <input type="number" name="ty_le_diem_thuong" id="ty_le_diem_thuong" class="form-control"
                                    value="{{ old('ty_le_diem_thuong', $settings->ty_le_diem_thuong) }}" min="1">
                                @error('ty_le_diem_thuong')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="ban_kinh_giao_hang">Bán kính giao hàng (km)</label>
                                <input type="number" name="ban_kinh_giao_hang" id="ban_kinh_giao_hang" class="form-control"
                                    value="{{ old('ban_kinh_giao_hang', $settings->ban_kinh_giao_hang) }}" min="0">
                                @error('ban_kinh_giao_hang')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ban_kinh_hien_thi_cua_hang">Bán kính hiển thị cửa hàng (km)</label>
                                <input type="number" name="ban_kinh_hien_thi_cua_hang" id="ban_kinh_hien_thi_cua_hang" class="form-control"
                                    value="{{ old('ban_kinh_hien_thi_cua_hang', $settings->ban_kinh_hien_thi_cua_hang) }}" min="0">
                                @error('ban_kinh_hien_thi_cua_hang')
                                    <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group form-check mt-4">
                                <input type="checkbox" name="che_do_bao_tri" class="form-check-input" id="che_do_bao_tri"
                                    {{ old('che_do_bao_tri', $settings->che_do_bao_tri) ? 'checked' : '' }}>
                                <label class="form-check-label" for="che_do_bao_tri">Bật chế độ bảo trì</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-action text-end">
                        <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admins/js/admin-settings-validate.js') }}"></script>
@endpush
