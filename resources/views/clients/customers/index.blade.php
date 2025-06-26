@extends('layouts.app')
@section('title', $title)   
@push('styles')
<style>
.member-card {
    background: linear-gradient(135deg, #F28123, #07212e);
    border-radius: 16px;
    position: relative;
    overflow: hidden;
}

.member-card::before {
    content: "";
    position: absolute;
    top: -40px;
    right: -40px;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.member-card h5,
.member-card p {
    color: #fff;
}

.barcode-box svg {
    max-width: 180px;
    height: 60px;
    display: block;
    margin: 0 auto;
}
</style>

@endpush
@section('content')

<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                    <h1>Thông tin khách hàng</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb -->

<!-- customer info section -->
<div class="contact-from-section mt-5 mb-5">
    <div class="container">
        <div class="row">   
            <div class="col-12 d-lg-none px-3">
                <div class="toggle-menu-wrapper mb-2">
                    <button class="btn btn-sm"
                            type="button"
                            data-toggle="collapse"
                            data-target="#accountMenu"
                            aria-expanded="false"
                            aria-controls="accountMenu"
                            style="background-color: #F28123; color: white; border: none; padding: 6px 16px; border-radius: 30px;">
                        <i class="fas fa-bars mr-1"></i> Menu
                    </button>
                </div>
            </div>

            @include('clients.customers.sub_layout_customer', ['taiKhoan' => $taiKhoan])
            <div class="col-lg-8">
                <div class="bg-white border rounded-lg shadow p-4">
                    @php
                        $kh = $taiKhoan->khachHang;
                    @endphp
                    <div class="card member-card mb-4 border-0 shadow-sm text-white">
                        <div class="card-body d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h5 class="font-weight-bold mb-2">
                                    <i class="fa fa-id-card mr-2"></i>Thẻ Thành Viên
                                </h5>
                                <p class="mb-1"><strong>Hạng:</strong> {{ $kh->hang_thanh_vien }}</p>
                                <p class="mb-0"><strong>Điểm:</strong> {{ $kh->diem_thanh_vien }}</p>
                            </div>
                            <div class="barcode-box bg-white rounded p-2 text-center">
                                <svg id="barcode" data-code="{{ $kh->ma_khach_hang }}"></svg>
                            </div>
                        </div>
                    </div>

                    <form id="customer-info-form" action="{{ route('customer.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small">Tên khách hàng</label>
                                <input type="text" class="form-control rounded-pill" name="hoTen" value="{{ $kh->ho_ten_khach_hang }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small">Email</label>
                                <input type="email" class="form-control rounded-pill" value="{{ $taiKhoan->email }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small">Số điện thoại</label>
                                <input type="text" class="form-control rounded-pill" name="soDienThoai" value="{{ $kh->so_dien_thoai ?? '' }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small">Ngày sinh</label>
                                <input type="date" class="form-control rounded-pill" name="ngaySinh" value="{{ $kh->ngay_sinh }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small d-block">Giới tính</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gioiTinh" id="nam" value="1" {{ $kh->gioi_tinh === 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="nam">Nam</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gioiTinh" id="nu" value="0" {{ $kh->gioi_tinh === 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="nu">Nữ</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn px-4 py-2 rounded-pill shadow-sm text-white"
                                style="background-color: #F28123; border: none;">
                            <i class="fa fa-save mr-1"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end customer info section -->
@endsection

@push('scripts')
<script src="{{ asset('js/customer/customer-validate.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const barcodeEl = document.querySelector("#barcode");
    const maKhachHang = barcodeEl.dataset.code;

    if (maKhachHang) {
        JsBarcode("#barcode", maKhachHang, {
            format: "CODE128",
            lineColor: "#343a40",
            width: 2,
            height: 50,
            displayValue: true
        });
    }
});
</script>
@endpush