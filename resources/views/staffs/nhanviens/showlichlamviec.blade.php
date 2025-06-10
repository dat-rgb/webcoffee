@extends('layouts.staff')
@section('title', 'Lịch làm việc theo tuần')

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="mb-4">Lịch làm việc @if($weekOffset == 0) tuần này @else tuần sau @endif</h2>
        <ul class="mb-3 breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('staff') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('staffs.nhanviens.index') }}">Danh sách nhân viên cửa hàng</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('staffs.nhanviens.lich.tuan') }}">Lịch làm việc</a>
            </li>
        </ul>
    </div>
    <form method="GET" action="{{ route('staffs.nhanviens.lich.tuan') }}" class="mb-3">
        @csrf
        <input type="hidden" name="ma_cua_hang" value="{{ $maCuaHang }}">
        <input type="hidden" name="week" value="{{ $weekOffset }}">
    </form>
    <div class="mb-3">
        @if($weekOffset > 0)
            <a href="{{ route('staffs.nhanviens.lich.tuan', ['ma_cua_hang' => $maCuaHang, 'week' => $weekOffset - 1]) }}" class="btn btn-secondary">← Tuần trước</a>
        @endif
        <a href="{{ route('staffs.nhanviens.lich.tuan', ['ma_cua_hang' => $maCuaHang, 'week' => $weekOffset + 1]) }}" class="btn btn-primary">Tuần sau →</a>
    </div>

    @if(count($nhanViens))
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>Nhân viên</th>
                    @foreach($dates as $date)
                        <th>{{ $date->format('d/m') }}<br>{{ $date->format('D') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($nhanViens as $nv)
                    <tr class="text-center">
                        <td>
                            {{ $nv->ho_ten_nhan_vien }}
                            <br>
                            <span style="font-size: 0.75rem; color: gray;">({{ $nv->chucVu->ten_chuc_vu ?? '' }})</span>
                        </td>
                        @foreach($dates as $date)
                            <td>
                                @php
                                    $caLabels = [
                                        0 => 'Ca Tối',
                                        1 => 'Ca Sáng',
                                        2 => 'Full Ca',
                                        3 => 'Nghỉ'
                                    ];
                                    $caSo = $lichPhanCong[$nv->ma_nhan_vien][$date->format('Y-m-d')][0]->ca_lam ?? null;
                                @endphp
                                {{ $caLabels[$caSo] ?? '-' }}
                            </td>

                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Vui lòng chọn cửa hàng để xem lịch làm việc.</p>
    @endif
</div>
@endsection
