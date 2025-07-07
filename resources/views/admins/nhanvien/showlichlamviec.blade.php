@extends('layouts.admin')
@section('title', 'Lịch làm việc theo tuần')

@section('content')
<div class="container-fluid">
    <div class="mb-4 page-header">
        <h2 class="mb-3">Lịch làm việc @if($weekOffset == 0) tuần này @else tuần sau @endif</h2>
        <ul class="mb-0 breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">
                <a href="{{ route('admins.nhanvien.index') }}">Danh sách nhân viên cửa hàng</a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">
                <a href="{{ route('admins.nhanvien.lich.tuan') }}">Lịch làm việc</a>
            </li>
        </ul>
    </div>

    <div class=" card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form method="GET" action="{{ route('admins.nhanvien.lich.tuan') }}" class="gap-3 d-flex align-items-center">
                @csrf
                <label for="ma_cua_hang" class="mb-0 fw-semibold">Chọn cửa hàng:</label>
                <select name="ma_cua_hang" id="ma_cua_hang" class="form-select" onchange="this.form.submit()" style="width: 220px;">
                    <option value="">-- Chọn cửa hàng --</option>
                    @foreach($cuaHangs as $ch)
                        <option value="{{ $ch->ma_cua_hang }}" {{ $maCuaHang == $ch->ma_cua_hang ? 'selected' : '' }}>
                            {{ $ch->ten_cua_hang }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="week" value="{{ $weekOffset }}">
            </form>

            <div>
                @if($weekOffset > 0)
                    <a href="{{ route('admins.nhanvien.lich.tuan', ['ma_cua_hang' => $maCuaHang, 'week' => $weekOffset - 1]) }}" class="btn btn-sm btn-secondary">← Tuần trước</a>
                @endif
                <a href="{{ route('admins.nhanvien.lich.tuan', ['ma_cua_hang' => $maCuaHang, 'week' => $weekOffset + 1]) }}" class="btn btn-sm btn-primary">Tuần sau →</a>
            </div>
        </div>

        <div class="card-body">
            @if(count($nhanViens))
                <div class="table-responsive">
                    <table class="table text-center align-middle table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nhân viên</th>
                                @foreach($dates as $date)
                                    <th>{{ $date->format('d/m') }}<br><small>{{ $date->format('D') }}</small></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nhanViens as $nv)
                                <tr>
                                    <td class="text-start">
                                        <strong>{{ $nv->ho_ten_nhan_vien }}</strong><br>
                                        <span class="text-muted small">({{ $nv->chucVu->ten_chuc_vu ?? '---' }})</span>
                                    </td>
                                    @foreach($dates as $date)
                                        @php
                                            $caLabels = [0 => 'Ca Tối', 1 => 'Ca Sáng', 2 => 'Full Ca', 3 => 'Nghỉ'];
                                            $caSo = $lichPhanCong[$nv->ma_nhan_vien][$date->format('Y-m-d')][0]->ca_lam ?? null;
                                        @endphp
                                        <td>{{ $caLabels[$caSo] ?? '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-3 mt-4 text-center rounded " style="max-width: 500px; margin: 0 auto;">
                    
                    <p class="mb-0 text-muted">Vui lòng chọn cửa hàng để xem lịch làm việc.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush
