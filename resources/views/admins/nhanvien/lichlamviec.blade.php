@extends('layouts.admin')
@section('title', 'Phân công lịch làm việc')
{{-- @section('subtile',$subtitle) --}}
@section('content')

<div class="container">
    <div class="page-header">
        {{-- <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3> --}}
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
                <a href="{{ route('admins.nhanvien.lich.showForm') }}">Phân công lịch làm việc</a>
            </li>
        </ul>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admins.nhanvien.lich.showForm') }}" method="GET" class="mb-4">
        @csrf
        <label>Chọn cửa hàng:</label>
        <select name="ma_cua_hang" class="form-select" onchange="this.form.submit()">
            <option value="">-- Chọn cửa hàng --</option>
            @foreach($cuaHangs as $ch)
                <option value="{{ $ch->ma_cua_hang }}" {{ ($maCuaHang == $ch->ma_cua_hang) ? 'selected' : '' }}>
                    {{ $ch->ten_cua_hang }}
                </option>
            @endforeach
            </select>
    </form>

    @if(count($nhanViens) > 0)
    <form action="{{ route('admins.nhanvien.lich.assignWork') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>Nhân viên</th>
                    @foreach($tuanToi as $ngay)
                        <th>{{ $ngay->format('D d/m') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($nhanViens as $nv)
                <tr class="text-center">
                    <td>{{ $nv->ho_ten_nhan_vien }}
                        <br>
                        <span style="font-size: 0.75rem; color: gray;">({{ $nv->chucVu->ten_chuc_vu ?? '' }})</span>
                    </td>
                    @foreach($tuanToi as $ngay)
                    <td>
                        <select name="work[{{ $nv->ma_nhan_vien }}][{{ $ngay->format('Y-m-d') }}]" class="form-select">
                            <option value="3">Nghỉ</option>
                            <option value="1">Ca sáng</option>
                            <option value="0">Ca tối</option>
                            <option value="2">Full ca</option>
                        </select>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary lich-btn-update">Lưu phân công</button>
    </form>
    @endif
</div>
@endsection


@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush
