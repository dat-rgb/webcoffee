@extends('layouts.admin')
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
            <h3 class="fw-bold mb-3">Dashboard</h3>
            <h6 class="op-7 mb-2">{{ $subtitle }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0 d-flex align-items-center gap-2">
            <form action="" method="GET">
                <select name="ma_cua_hang" class="form-select btn-round" style="width: auto;" onchange="this.form.submit()">
                    <option selected disabled>--Chọn cửa hàng--</option>
                    @foreach ($cuaHangs as $store)
                        <option value="{{ $store->ma_cua_hang }}" {{ isset($selectedCuaHang) && $selectedCuaHang == $store->ma_cua_hang ? 'selected' : '' }}>
                            {{ $store->ten_cua_hang }}
                        </option>
                    @endforeach
                </select>
            </form>
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
         <!-- Thống kê khách hàng -->
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Khách hàng</p>
                                <h4 class="card-title">{{ $countKhachHang }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Doanh thu -->
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
                    <div class="chart-container">
                        <canvas id="horizontalBarChartTopSanPham" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admins/js/plugin/chart.js/chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Hóa đơn đã nhận/đã hủy
    const pieChartHoaDon = document.getElementById("pieChartHoaDon").getContext("2d");
    const hoaDonDaNhan = {{ $hoaDonDaNhan }};
    const hoaDonDaHuy = {{ $hoaDonDaHuy }};
    new Chart(pieChartHoaDon, {
        type: "pie",
        data: {
            datasets: [{
                data: [hoaDonDaNhan, hoaDonDaHuy],
                backgroundColor: ["#1d7af3", "#f3545d"],
                borderWidth: 0,
            }],
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
                        font: { size: 11 },
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: { enabled: true }
            },
            layout: {
                padding: { left: 20, right: 20, top: 20, bottom: 20 }
            }
        }
    });

    //Doanh thu
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

