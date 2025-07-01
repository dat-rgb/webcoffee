@extends('layouts.staff')
@section('title',$title)
@section('subtitle',$subtitle)

@push('styles')
<style type="text/css">/* Chart.js */
    @-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}
    @keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}
</style>

@endpush
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Quản lý cửa hàng</h3>
            <h6 class="op-7 mb-2">{{ $subtitle }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="#" class="btn btn-outline-success btn-round me-2" data-bs-toggle="modal" data-bs-target="#modalPhieuNhap">
                <i class="fas fa-file-import me-1"></i> Phiếu yêu cầu nhập
            </a>
            <a href="#" class="btn btn-outline-warning btn-round me-2" data-bs-toggle="modal" data-bs-target="#modalPhieuXuat">
                <i class="fas fa-file-export me-1"></i> Phiếu yêu cầu xuất
            </a>
            <a href="#" class="btn btn-outline-info btn-round" data-bs-toggle="modal" data-bs-target="#modalPhieuKiemKho">
                <i class="fas fa-clipboard-check me-1"></i> Phiếu Kiểm kho
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thống kê nhân viên -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Nhân viên</p>
                                <h4 class="card-title">{{ $countNhanVien }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Thống kê doanh thu hôm nay -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-luggage-cart"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Tổng doanh thu</p>
                                <h4 class="card-title">{{ number_format($tongDoanhThu,0,',','.') }} đ</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Thống kê đơn hàng -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="far fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Tổng hóa đơn</p>
                                <h4 class="card-title">{{ $tongHoaDon }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--  -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Doanh thu</div>
                        <div class="card-tools d-flex align-items-center justify-content-end gap-2">
                            <div class="d-flex align-items-center gap-1">
                                <input type="date"
                                    id="startDate"
                                    class="form-control form-control-sm"
                                    value="{{ request('start') }}"
                                    placeholder="Từ ngày">

                                <span class="mx-1">–</span>

                                <input type="date"
                                    id="endDate"
                                    class="form-control form-control-sm"
                                    value="{{ request('end') }}"
                                    placeholder="Đến ngày">
                            </div>

                            <!-- Dropdown chọn khoảng thời gian -->
                            <select id="doanhThuOption" class="form-select form-select-sm w-auto">
                                <option value="month" selected>Theo tháng</option>
                                <option value="quarter">Theo quý</option>
                                <option value="year">Theo năm</option>
                            </select>

                            <a href="#" class="btn btn-label-success btn-round btn-sm">
                                <span class="btn-label"><i class="fa fa-file-export"></i></span>
                                Xuất
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="barChartDoanhTungThangTheoNam" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Doanh thu hôm nay</div>
                    </div>
                <div class="card-category">{{ \Carbon\Carbon::now()->format('d/m/Y') }} </div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                        <h1>{{ number_format($doanhThuNgay,0,',','.') }} đ</h1>
                    </div>
                </div>
            </div>
            <div class="card card-round">
                <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary"> {{ $tangTruongNgay['ty_le'] }}%</div>
                    <div class="card-head-row">
                        <div class="card-title">Hóa đơn hôm nay</div>
                    </div>
                    <h2 class="mb-2">{{ $hoaDonNgay }}</h2>
                    <p class="text-muted">Ngày: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Lợi nhuận -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Lợi nhuận</div>
                    <div class="card-tools d-flex align-items-center justify-content-end gap-2">
                        <!-- Dropdown chọn khoảng thời gian -->
                        <select id="loiNhuanOption" class="form-select form-select-sm w-auto">
                            <option value="">--Chọn thời gian--</option>
                            <option value="month" selected>Theo tháng</option>
                            <option value="quarter">Theo quý</option>
                            <option value="year">Theo năm</option>
                        </select>

                        <a href="#" class="btn btn-label-success btn-round btn-sm">
                            <span class="btn-label"><i class="fa fa-file-export"></i></span>
                            Xuất
                        </a>
                    </div>
                </div>  
            <div class="card-body">
                <div class="chart-container"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                    <canvas id="myChartLoiNhuan" width="1116" height="375" style="display: block; height: 300px; width: 893px;" class="chartjs-render-monitor"></canvas>
                </div>
                <div id="myChartLoiNhuan">
                    <ul class="html-legend">
                        <li><span style="background-color:#f3545d"></span>Tổng chi</li>
                        <li><span style="background-color:#fdaf4b"></span>Tổng thu</li>
                        <li><span style="background-color:#177dff"></span>Lợi nhuận</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="row">
         <!-- Hóa đơn -->
        <div class="col-md-6">
            <div class="card">
                  <div class="card-header">
                        <div class="card-title">Hóa đơn theo trạng thái</div>
                  </div>
                  <div class="card-body">
                        <div class="chart-container">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                            <canvas id="pieChartHoaDon" style="width: 341px; height: 300px; display: block;" width="426" height="375" class="chartjs-render-monitor"></canvas>
                        </div>
                  </div>
            </div>
        </div>
        <!-- Sản phẩm bán chạy -->
        <div class="col-md-6">
            <div class="card">
                 <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Sản phẩm bán chạy</div>
                        <div class="card-tools d-flex align-items-center justify-content-end gap-2">
                            <!-- Dropdown chọn khoảng thời gian -->
                            <select id="topSanPhamOption" class="form-select form-select-sm w-auto">
                                <option value="month" selected>Theo tháng</option>
                                <option value="quarter">Theo quý</option>
                                <option value="year">Theo năm</option>
                            </select>
                            <a href="#" class="btn btn-label-success btn-round btn-sm">
                                <span class="btn-label"><i class="fa fa-file-export"></i></span>
                                Xuất
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="horizontalBarChartTopSanPham" width="426" height="375" style="display: block; height: 300px; width: 341px;" class="chartjs-render-monitor"></canvas>
                    </div>
                    <div id="myChartTopSanPham"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Phiếu Nhập -->
<div class="modal fade" id="modalPhieuNhap" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <style>
            tr.nguyenlieu-row.selected {
                background-color: #d1e7dd !important;
                border-left: 5px solid #198754;
            }
        </style>
        <div class="modal-content">
            <form action="{{ route('staff.nguyenlieu.exportPhieuNhap') }}" method="POST" target="_blank" id="phieuNhapForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Chọn nguyên liệu vào phiếu yêu cầu nhập nguyên liệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>Mã NL</th>
                                    <th>Nguyên liệu</th>
                                    <th>Giá</th>
                                    <th>SL Dự kiến</th>
                                    <th>Đơn vị tính</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nguyen_lieu_cua_hang as $index => $nl)
                                <tr class="nguyenlieu-row">
                                    <td class="text-center">
                                        <input type="checkbox" class="check-row" name="chon_nhap[]" value="{{ $nl->ma_nguyen_lieu }}">
                                    </td>
                                    <td>{{ $nl->ma_nguyen_lieu }}</td>
                                    <td>
                                        {{ $nl->ten_nguyen_lieu }} <br>
                                        <span class="badge bg-primary mt-1">ĐL: {{ $nl->so_luong ?? 0 }} {{ $nl->don_vi ?? '' }}</span>
                                    </td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="gia[{{ $nl->ma_nguyen_lieu }}]" 
                                            value="{{ $nl->gia_nhap }}" 
                                            class="form-control input-gia" 
                                            min="1000"
                                            max="1000000000"
                                        >    
                                    </td>
                                    <td>
                                        <input type="number" name="so_luong_du_kien[{{ $nl->ma_nguyen_lieu }}]"
                                        class="form-control input-soluong" min="1">
                                    </td>
                                    <td>
                                        <input type="text" name="don_vi_tinh[{{ $nl->ma_nguyen_lieu }}]"
                                        class="form-control input-donvi" placeholder="Nhập ĐVT">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Xuất PDF</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Phiếu Xuất -->
<div class="modal fade" id="modalPhieuXuat" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('staff.nguyenlieu.exportPhieuXuat') }}" method="POST" target="_blank" id="phieuXuatForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Chọn nguyên liệu vào phiếu yêu cầu xuất nguyên liệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <style>
                            #modalPhieuXuat tr.selected {
                            background-color: #fce4ec !important;
                            border-left: 5px solid #dc3545;
                            }
                        </style>
                        <table class="table table-bordered">
                            <thead class="table-light text-center">
                                <tr>
                                    <th><input type="checkbox" id="checkAllXuat"></th>
                                    <th>Mã NL</th>
                                    <th>Nguyên liệu</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Đơn vị tính</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nguyen_lieu_cua_hang as $index => $nl)
                                    <tr>
                                        <td><input type="checkbox" class="check-row-xuat" name="chon_xuat[]" value="{{ $nl->ma_nguyen_lieu }}"></td>
                                        <td>{{ $nl->ma_nguyen_lieu }}</td>
                                        <td>
                                            {{ $nl->ten_nguyen_lieu }} -
                                            <span class="badge bg-primary text-white">
                                                ĐỊNH LƯỢNG: {{ $nl->so_luong ?? 0 }} ({{ $nl->don_vi ?? '---' }})
                                            </span>
                                        </td>
                                        <td>{{ number_format($nl->gia_nhap, 0, ',', '.') }} đ</td>
                                        <td><input type="number" name="so_luong_xuat[{{ $nl->ma_nguyen_lieu }}]" class="form-control" min="0"></td>
                                        <td>
                                            <input type="text" name="don_vi_tinh[{{ $nl->ma_nguyen_lieu }}]"
                                            class="form-control input-donvi" placeholder="Nhập ĐVT">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Xuất PDF</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Phiếu Kiểm Kho -->
<div class="modal fade" id="modalPhieuKiemKho" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <style>
      tr.nguyenlieu-row.selected {
        background-color: #cff4fc !important;
        border-left: 5px solid #0dcaf0;
      }
    </style>
    <div class="modal-content">
      <form action="{{ route('staff.nguyenlieu.exportPhieuKiemKho') }}" method="POST" target="_blank" id="phieuKiemKhoForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Kiểm kho nguyên liệu tại cửa hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
          <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th><input type="checkbox" id="checkAllKiemKho"></th>
                        <th>Mã NL</th>
                        <th>Nguyên liệu</th>
                        <th>Lô hàng</th>
                        <th>SL Tồn Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nguyen_lieu_kiem_kho as $index => $nl)
                        @php
                            $tongTon = 0;
                            if (!empty($nl->lo_hang)) {
                                foreach ($nl->lo_hang as $lo) {
                                    $tongTon += $lo['ton_lo'] ?? 0;
                                }
                            }
                        @endphp
                        <tr class="nguyenlieu-row">
                            <td class="text-center">
                                <input type="checkbox" class="check-row-kiemkho" name="chon_kiemkho[]" value="{{ $nl->ma_nguyen_lieu }}">
                            </td>
                            <td>{{ $nl->ma_nguyen_lieu }}</td>
                            <td>
                                {{ $nl->ten_nguyen_lieu }} <br>
                                <span class="badge bg-info mt-1">ĐL: {{ $nl->so_luong_goc ?? 0 }} {{ $nl->don_vi }}</span>
                            </td>
                            <td class="text-start">
                            @php
                                // tính tổng tồn
                                $tongTon = $nl->available_batches->sum('con_lai');
                            @endphp

                            @if($nl->available_batches->count())
                                @foreach($nl->available_batches as $lo)
                                    @if($lo['con_lai'] > 0)
                                        <div class="mb-1">
                                            <span class="badge bg-primary border text-white">
                                                {{ $lo['so_lo'] }} -
                                                {{ floor($lo['con_lai'] / $nl->so_luong_goc) }}
                                                {{ $nl->don_vi_tinh }} - HSD
                                                {{ \Carbon\Carbon::parse($lo['han_su_dung'])->format('d/m/Y') }}
                                            </span>
                                            <!-- <span class="badge bg-primary border text-white">
                                                {{ $lo['so_lo'] }} -
                                                {{ $lo['con_lai'] / $nl->so_luong_goc}}
                                                {{ $nl->don_vi_tinh }} - HSD
                                                {{ \Carbon\Carbon::parse($lo['han_su_dung'])->format('d/m/Y') }}
                                            </span> -->
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-muted">Không có lô</span>
                            @endif
                            </td>
                            <td>{{ floor($tongTon / $nl->so_luong_goc) }} {{ $nl->don_vi_tinh ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Xuất PDF</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('admins/js/plugin/chart.js/chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('admins/js/validate-popup-phieu.js') }}"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {
    // Hóa đơn đã nhận/đã hủy của cửa hàng
    const pieChartHoaDon = document.getElementById("pieChartHoaDon").getContext("2d");
    const hoaDonDaNhan = {{ $hoaDonDaNhan }};
    const hoaDonDaHuy = {{ $hoaDonDaHuy }};
    const mypieChartHoaDon = new Chart(pieChartHoaDon, {
        type: "pie",
        data: {
            datasets: [
                {
                    data: [hoaDonDaNhan, hoaDonDaHuy],
                    backgroundColor: ["#1d7af3", "#f3545d"],
                    borderWidth: 0,
                },
            ],
            labels: ["Đã nhận", "Đã hủy"],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        color: "rgb(154, 154, 154)",
                        font: {
                            size: 11
                        },
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    enabled: true
                }
            },
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20,
                },
            },
        },
    });
    
    // Doanh thu theo tháng
    let chartDoanhThu;

    function loadDoanhThu(mode = 'month') {
        const url = new URL('{{ url('/admin/dashboard/doanh-thu-json') }}');
        url.searchParams.set('mode', mode);
        url.searchParams.set('start', document.getElementById('startDate')?.value || '');
        url.searchParams.set('end', document.getElementById('endDate')?.value || '');
        url.searchParams.set('ma_cua_hang', '{{ $selectedCuaHang ?? '' }}');

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const labels = data.labels;
                const values = data.values;

                if (chartDoanhThu) chartDoanhThu.destroy();

                chartDoanhThu = new Chart(document.getElementById('barChartDoanhTungThangTheoNam'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Doanh thu (đơn vị: VNĐ)",
                            backgroundColor: "rgb(23, 125, 255)",
                            data: values,
                            barPercentage: 0.5,
                            categoryPercentage: 0.5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => value.toLocaleString('vi-VN') + " đ"
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: context => context.parsed.y.toLocaleString('vi-VN') + " đ"
                                }
                            }
                        }
                    }
                });
            });
    }

    ['startDate', 'endDate', 'doanhThuOption'].forEach(id =>
        document.getElementById(id)?.addEventListener('change', () => {
            loadDoanhThu(document.getElementById('doanhThuOption').value);
        })
    );

    // Load lần đầu
    loadDoanhThu();

     // Top sản phẩm bán chạy
     let chartTopSanPham;

    function loadTopSanPham(mode = 'month') {
        const url = new URL('{{ url("/admin/dashboard/top-san-pham") }}');
        url.searchParams.set('mode', mode);
        url.searchParams.set('ma_cua_hang', '{{ $selectedCuaHang ?? "" }}');

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(sp => sp.ten_san_pham.length > 30 ? sp.ten_san_pham.slice(0, 27) + '...' : sp.ten_san_pham);
                const values = data.map(sp => parseInt(sp.tong_ban));
                const canvas = document.getElementById('horizontalBarChartTopSanPham');
                canvas.style.height = (data.length * 40) + 'px';

                if (chartTopSanPham) {
                    chartTopSanPham.destroy();
                    chartTopSanPham = null;
                }

                const ctx = canvas.getContext('2d');

                chartTopSanPham = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: "Số lượng bán",
                            backgroundColor: '#fdaf4b',
                            borderWidth: 1,
                            data: values
                        }]
                    },
                    options: {
                        indexAxis: 'y', 
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { beginAtZero: true },
                            y: {
                                ticks: {
                                    font: { weight: '500' },
                                    padding: 10
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: context => `${context.parsed.x} lượt bán`
                                }
                            }
                        },
                        layout: {
                            padding: { left: 5, right: 5, top: 15, bottom: 15 }
                        }
                    }
                });
            });
    }
    loadTopSanPham();
    document.getElementById('topSanPhamOption')?.addEventListener('change', function () {
        loadTopSanPham(this.value);
    });

    ///////Lợi nhuận
    let chartLoiNhuan;

    function loadLoiNhuan(mode = 'month') {
        const url = new URL('{{ url('/admin/dashboard/loi-nhuan-json') }}');

        url.searchParams.set('mode', mode);
        url.searchParams.set('start', document.getElementById('startDate')?.value || '');
        url.searchParams.set('end', document.getElementById('endDate')?.value || '');
        url.searchParams.set('ma_cua_hang', '{{ $selectedCuaHang ?? '' }}');

        fetch(url)
            .then(res => res.json())
            .then(data => {
                //console.log("DATA LỢI NHUẬN:", data);
                const labels = data.labels;
                const chi = data.tongChi.map(Number);
                const thu = data.tongThu.map(Number);
                const loiNhuan = data.loiNhuan.map(Number);

                if (chartLoiNhuan) chartLoiNhuan.destroy(); // clear cũ

                chartLoiNhuan = new Chart(document.getElementById("myChartLoiNhuan"), {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [
                            { label: "Tổng chi", backgroundColor: "#f3545d", data: chi },
                            { label: "Tổng thu", backgroundColor: "#fdaf4b", data: thu },
                            { label: "Lợi nhuận", backgroundColor: "#177dff", data: loiNhuan }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => value.toLocaleString('vi-VN') + " đ"
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: context => context.dataset.label + ": " + context.parsed.y.toLocaleString('vi-VN') + " đ"
                                }
                            }
                        }
                    }
                });
            });
    }

    document.getElementById('loiNhuanOption').addEventListener('change', function () {
        if (this.value) {
            loadLoiNhuan(this.value);
        }
    });

    loadLoiNhuan();

});    
</script>
@endpush

