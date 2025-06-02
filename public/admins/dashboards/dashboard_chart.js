document.addEventListener("DOMContentLoaded", function () {
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
});
