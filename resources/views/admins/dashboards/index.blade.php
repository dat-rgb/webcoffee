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
    </div>
    <!--  -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Top 5 sản phẩm bán chạy trong tháng</div>
                        <div class="card-tools">
                            <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                <span class="btn-label">
                                <i class="fa fa-pencil"></i>
                                </span>
                                Xem chi tiết
                            </a>
                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                <span class="btn-label">
                                <i class="fa fa-print"></i>
                                </span>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="horizontalBarChartTopSanPham" height="400"></canvas>
                    </div>
                    <div id="myChartTopSanPham"></div>
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
                    <div class="pull-in"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="tangTruongDoanhThu" width="301" height="187" style="display: block; height: 150px; width: 241px;" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
            <div class="card card-round">
                <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary"> {{ $tangTruongNgay['ty_le'] }}%</div>
                    <h2 class="mb-2">{{ $hoaDonNgay }}</h2>
                    <p class="text-muted">Hóa đơn: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                    <div class="pull-in sparkline-fix">
                        <div id="lineChart"><canvas width="243" height="70" style="display: inline-block; width: 243.462px; height: 70px; vertical-align: top;"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
         <!-- Hóa đơn -->
        <div class="col-md-6">
            <div class="card">
                  <div class="card-header">
                    <div class="card-title">Hóa đơn</div>
                  </div>
                  <div class="card-body">
                    <div class="chart-container">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">

                                </div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0">
                            </div>
                        </div>
                    </div>
                      <canvas id="pieChartHoaDon" style="width: 341px; height: 300px; display: block;" width="426" height="375" class="chartjs-render-monitor"></canvas>
                    </div>
                  </div>
            </div>
        </div>
         <!-- Doanh thu -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Doanh thu từng tháng trong năm {{ \Carbon\Carbon::now()->year }}</div>
                </div>
                <div class="card-body">
                    <div class="chart-container"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="barChartDoanhTungThangTheoNam" width="426" height="375" style="display: block; height: 300px; width: 341px;" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </di>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admins/js/plugin/chart.js/chart.min.js') }}"></script>
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
    // Chuyển PHP array sang JSON JS
    const dataDoanhThu = @json(array_values($doanhTungThangTrongNam)).map(Number);

    const barChartDoanhTungThangTheoNam = new Chart(document.getElementById('barChartDoanhTungThangTheoNam'), {
        type: 'bar',
        data: {
            labels: [
                "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", 
                "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", 
                "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
            ],
            datasets: [{
                label: "Doanh thu (đơn vị: VNĐ)",
                backgroundColor: "rgb(23, 125, 255)",
                borderColor: "rgb(23, 125, 255)",
                data: dataDoanhThu,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + " đ";
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed.y;
                            return value.toLocaleString('vi-VN') + " đ";
                        }
                    }
                }
            }
        }
    });

    //Doanh thu hôm nay (Card) - Tăng trưởng theo tháng
    const tangTruongDoanhThu = document.getElementById('tangTruongDoanhThu').getContext('2d');
    const mytangTruongDoanhThu = new Chart(tangTruongDoanhThu, {
        type: 'line',
        data: {
            labels: @json($labelsChart),
            datasets: [{
                label: "Tăng trưởng doanh thu theo tháng",
                fill: true,
                backgroundColor: "rgba(255,255,255,0.2)",
                borderColor: "#fff",
                pointBorderColor: "#fff",
                pointBackgroundColor: "#fff",
                data: @json($dataChart)
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false },
            animation: { easing: "easeInOutBack" },
            scales: {
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 10,
                        padding: 0
                    },
                    gridLines: { display: false }
                }],
                xAxes: [{
                    display: false,
                    gridLines: { zeroLineColor: "transparent" },
                    ticks: {
                        padding: -20,
                        fontColor: "rgba(255,255,255,0.2)",
                        fontStyle: "bold"
                    }
                }]
            }
        }
    });
 
    //Top sản phẩm
    var topSanPhamData = @json($topSPBanChay);
    let labels = topSanPhamData.map(sp => sp.ten_san_pham);
    let data = topSanPhamData.map(sp => parseInt(sp.tong_ban));
    var ctx = document.getElementById('horizontalBarChartTopSanPham').getContext('2d');
    var horizontalBarChartTopSanPham = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels, 
            datasets: [{
                label: "Số lượng bán",
                backgroundColor: '#fdaf4b',
                borderWidth: 1,
                data: data, 
                legendColor: '#fdaf4b'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    ticks: {
                        font: {
                            weight: '500'
                        },
                        padding: 10
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    bodySpacing: 4,
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                }
            },
            layout: {
                padding: { left: 5, right: 5, top: 15, bottom: 15 }
            },
            legendCallback: function(chart) {
                var text = [];
                text.push('<ul class="' + chart.id + '-legend html-legend">');
                for (var i = 0; i < chart.data.datasets.length; i++) {
                    text.push('<li><span style="background-color:' + chart.data.datasets[i].backgroundColor + '"></span>');
                    if (chart.data.datasets[i].label) {
                        text.push(chart.data.datasets[i].label);
                    }
                    text.push('</li>');
                }
                text.push('</ul>');
                return text.join('');
            }
        }
    });
});
</script>
@endpush

