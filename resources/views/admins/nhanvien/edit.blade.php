@extends('layouts.admin')
@section('title', 'Chỉnh sửa nhân viên')
@section('subtitle', 'Cập nhật thông tin nhân viên')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="mb-3 fw-bold">Cập nhật thông tin nhân viên</h3>
        <ul class="mb-3 breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admins.nhanvien.index') }}">Danh sách nhân viên cửa hàng</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#" class="">Chỉnh sửa nhân viên</a>
            </li>
        </ul>
    </div>

    @if ($errors->any())
        <div class="mx-4 mt-3 alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="rounded shadow-sm card">
                <form action="{{ route('admins.nhanvien.update', $nhanVien->ma_nhan_vien) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-4 card-body">

                        <div class="mb-3">
                            <label class="form-label">Mã nhân viên</label>
                            <input type="text" class="form-control" value="{{ $nhanVien->ma_nhan_vien }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="ho_ten_nhan_vien" value="{{ $nhanVien->ho_ten_nhan_vien }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Chức vụ</label>
                            <select name="ma_chuc_vu" class="form-select" required>
                                @foreach($chucVus as $chucVu)
                                    <option value="{{ $chucVu->ma_chuc_vu }}" {{ $nhanVien->ma_chuc_vu == $chucVu->ma_chuc_vu ? 'selected' : '' }}>
                                        {{ $chucVu->ten_chuc_vu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" value="{{ $nhanVien->so_dien_thoai }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="gioi_tinh" class="form-select" required>
                                <option value="0" {{ $nhanVien->gioi_tinh == 0 ? 'selected' : '' }}>Nam</option>
                                <option value="1" {{ $nhanVien->gioi_tinh == 1 ? 'selected' : '' }}>Nữ</option>
                                <option value="2" {{ $nhanVien->gioi_tinh == 2 ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label">Ca làm</label>
                            <select name="ca_lam" class="form-select" required>
                                <option value="1" {{ $nhanVien->ca_lam == 1 ? 'selected' : '' }}>Ca Sáng</option>
                                <option value="0" {{ $nhanVien->ca_lam == 0 ? 'selected' : '' }}>Ca Tối</option>
                                <option value="2" {{ $nhanVien->ca_lam == 2 ? 'selected' : '' }}>Full Ca</option>
                            </select>
                        </div> --}}

                        <div class="mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" value="{{ $nhanVien->ngay_sinh }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="dia_chi" value="{{ $nhanVien->dia_chi }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email tài khoản</label>
                            <input type="email" name="email" value="{{ $nhanVien->taiKhoan->email ?? '' }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cửa hàng</label>
                            <select name="ma_cua_hang" class="form-select" required>
                                @foreach($cuaHangs as $ch)
                                    <option value="{{ $ch->ma_cua_hang }}" {{ $nhanVien->ma_cua_hang == $ch->ma_cua_hang ? 'selected' : '' }}>
                                        {{ $ch->ten_cua_hang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="card-footer bg-light text-end">
                        <button type="submit" class="btn btn-primary nhanvien-btn-update">Cập nhật</button>
                        <a href="{{ route('admins.nhanvien.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush
