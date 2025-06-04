@extends('layouts.app')
@section('title', $title)   

@section('content')

<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
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
            @include('clients.customers.sub_layout_customer')
            <div class="col-lg-8">
                <div class="bg-white border rounded shadow-sm p-4">
                    <h4 class="mb-4">Thông tin cá nhân</h4>
                    @php $kh = $taiKhoan->khachHang; @endphp
                    <form id="customer-info-form" action="{{ route('customer.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="maKhachHang">Mã khách hàng:</label>
                                <input type="maKhachHang" class="form-control" id="maKhachHang" value="{{ $kh->ma_khach_hang }}" readonly>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="hoTen">Họ tên:</label>
                                <input type="text" class="form-control" id="hoTen" name="hoTen" value="{{ $kh->ho_ten_khach_hang }}">
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" value="{{ $taiKhoan->email }}" readonly>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="soDienThoai">Số điện thoại:</label>
                                <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" value="{{ $kh->so_dien_thoai ?? '' }}">
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="ngaySinh">Ngày sinh:</label>
                                <input type="date" class="form-control" id="ngaySinh" name="ngaySinh" value="{{ $kh->ngay_sinh }}">
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="gioiTinh">Giới tính:</label>
                                <select class="form-control" id="gioiTinh" name="gioiTinh">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="1" @if($kh->gioi_tinh === 1) selected @endif>Nam</option>
                                    <option value="0" @if($kh->gioi_tinh === 0) selected @endif>Nữ</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label>Hạng thành viên:</label>
                                <input type="text" class="form-control" value="{{ $kh->hang_thanh_vien }}" readonly>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label>Điểm tích lũy:</label>
                                <input type="text" class="form-control" value="{{ $kh->diem_thanh_vien }}" readonly>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
                <!-- địa chỉ -->
                <div class="bg-white border rounded shadow-sm p-4 mt-4">
                    <h4 class="mb-4">Sổ địa chỉ</h4>

                    @forelse ($kh->diaChis ?? [] as $diaChi)
                        <div class="border rounded p-3 mb-3 {{ $diaChi->mac_dinh ? 'border-primary' : '' }}">
                            <p class="mb-1"><strong>Địa chỉ:</strong> 
                                {{ implode(', ', array_filter([
                                    $diaChi->dia_chi,
                                    $diaChi->phuong_xa,
                                    $diaChi->quan_huyen,
                                    $diaChi->tinh_thanh
                                ])) }}
                            </p>
                            @if($diaChi->mac_dinh)
                                <span class="badge bg-primary">Mặc định</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Bạn chưa có địa chỉ nào.</p>
                    @endforelse

                    <!-- nút mở modal -->
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-toggle="modal" data-target="#addAddressModal">
                        <i class="fa fa-plus"></i> Thêm địa chỉ mới
                    </button>

                    <!-- modal -->
                    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('customer.address.store') }}" method="POST" id="addAddressForm">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <!-- các input tương tự -->
                            <div class="form-group">
                                <label for="diaChi">Địa chỉ</label>
                                <input type="text" class="form-control" id="diaChi" name="diaChi" required>
                            </div>
                            <div class="form-group">
                                <label for="tinhThanh">Tỉnh/Thành phố</label>
                                <select class="form-control" id="tinhThanh" name="tinhThanh" required>
                                    <option value="">-- Chọn tỉnh/thành phố --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quanHuyen">Quận/Huyện</label>
                                <select class="form-control" id="quanHuyen" name="quanHuyen" required>
                                    <option value="">-- Chọn quận/huyện --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="phuongXa">Phường/Xã</label>
                                <select class="form-control" id="phuongXa" name="phuongXa" required>
                                    <option value="">-- Chọn phường/xã --</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>
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
$('#addAddressModal').on('shown.bs.modal', function () {
    console.log('Modal opened');
    let $tinh = $('#tinhThanh');
    let $huyen = $('#quanHuyen');
    let $xa = $('#phuongXa');

    // Nếu chưa có tỉnh trong select thì mới load
    if ($tinh.children('option').length <= 1) {
        $.get('{{ route("api.diachi") }}', function (res) {
            $tinh.empty().append('<option value="">-- Chọn tỉnh/thành phố --</option>');
            $.each(res.tinhTP, function(i, item) {
                $tinh.append(`<option value="${item.code}">${item.name}</option>`);
            });
        });
    }

    // Reset các dropdown huyện, xã mỗi lần mở modal
    $huyen.empty().append('<option value="">-- Chọn quận/huyện --</option>');
    $xa.empty().append('<option value="">-- Chọn phường/xã --</option>');
});

// Khi chọn tỉnh, load quận/huyện
$('#tinhThanh').on('change', function () {
    let provinceId = $(this).val();
    let $huyen = $('#quanHuyen');
    let $xa = $('#phuongXa');

    if (!provinceId) {
        $huyen.empty().append('<option value="">-- Chọn quận/huyện --</option>');
        $xa.empty().append('<option value="">-- Chọn phường/xã --</option>');
        return;
    }

    $.get('/api/dia-chi?province=' + provinceId, function (data) {
        $huyen.empty().append('<option value="">-- Chọn quận/huyện --</option>');
        $.each(data.quanHuyen, function(i, item) {
            $huyen.append(`<option value="${item.code}">${item.name}</option>`);
        });
        $xa.empty().append('<option value="">-- Chọn phường/xã --</option>');
    });
});

// Khi chọn quận/huyện, load phường/xã
$('#quanHuyen').on('change', function () {
    let districtId = $(this).val();
    let $xa = $('#phuongXa');

    if (!districtId) {
        $xa.empty().append('<option value="">-- Chọn phường/xã --</option>');
        return;
    }

    $.get('/api/dia-chi?district=' + districtId, function (data) {
        $xa.empty().append('<option value="">-- Chọn phường/xã --</option>');
        $.each(data.phuongXa, function(i, item) {
            $xa.append(`<option value="${item.code}">${item.name}</option>`);
        });
    });
});
</script>

@endpush