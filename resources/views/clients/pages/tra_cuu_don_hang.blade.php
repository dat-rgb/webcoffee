@extends('layouts.app')

@section('title', $title)

@push('styles')
<style>
    .form-title h2 {
        font-weight: 700;
        font-size: 2.5rem;
        color: #222;
    }
    .form-title p {
        font-size: 1rem;
        color: #666;
        margin-top: 6px;
    }
    .form-label {
        font-weight: 600;
        color: #444;
    }
    input.form-control {
        border-radius: 8px;
        border: 1.5px solid #ced4da;
        padding: 10px 15px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    input.form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        outline: none;
    }
    button.btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        transition: background-color 0.3s;
    }
    button.btn-primary:hover {
        background-color: #0056b3;
    }
    .order-details {
        background-color: #fff;
        border-radius: 10px;
        font-size: 1rem;
        box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
    }
    .order-info p {
        margin: 6px 0;
        font-size: 0.95rem;
    }
    .status {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        user-select: none;
    }
    .status-0 { background-color: #ffc107; } /* vàng */
    .status-1 { background-color: #17a2b8; } /* xanh dương nhạt */
    .status-2 { background-color: #28a745; } /* xanh lá */
    .status-3 { background-color: #007bff; } /* xanh dương đậm */
    .status-4 { background-color: #6f42c1; } /* tím */
    .status-5 { background-color: #dc3545; } /* đỏ */
    .status-default { background-color: #6c757d; } /* xám */
    table.table {
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
    }
    table.table thead tr th {
        border-bottom: none !important;
        color: #555;
        font-weight: 600;
        padding-bottom: 12px !important;
    }
    table.table tbody tr {
        background: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgb(0 0 0 / 0.05);
    }
    table.table tbody tr td {
        border: none !important;
        vertical-align: middle;
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }
</style>
@endpush

@section('content')
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Tra cứu đơn hàng</p>
                    <h1>Tra cứu đơn hàng</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-form-section mt-150 mb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="form-title text-center mb-4">
                    <h2>Nhập mã đơn hàng của bạn</h2>
                    <p>Vui lòng nhập mã đơn hàng để tra cứu thông tin đơn hàng của bạn.</p>
                </div>
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('traCuuDonHang.search') }}" id="traCuuForm" class="shadow-sm p-4 rounded bg-white">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="ma_don_hang" class="form-label fw-semibold">Mã đơn hàng</label>
                        <input type="text" name="ma_don_hang" id="ma_don_hang" class="form-control" placeholder="Nhập mã đơn hàng" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Tra cứu</button>
                    </div>
                </form>
            </div>
        </div>

        @isset($order)
        <div class="order-details mt-5 shadow-sm p-4 rounded bg-white mx-auto" style="max-width: 800px;">
        <div class="d-flex flex-wrap justify-content-between mb-4">
            <div class="order-info col-md-6 mb-3">
                <p><strong>Mã Hóa Đơn:</strong> {{ $order->ma_hoa_don }}</p>
                <p><strong>Cửa hàng:</strong> {{ $order->ma_cua_hang }}</p>
                <p><strong>Nhân viên:</strong> {{ $order->ma_nhan_vien ?? 'Không có' }}</p>
                <p><strong>Ngày lập:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>SĐT:</strong> {{ $order->so_dien_thoai }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
            </div>
            <div class="order-info col-md-6 mb-3">
                <p><strong>Khách hàng:</strong>
                {{ $order->ma_khach_hang ? $order->ten_khach_hang : 'Guest - ' . $order->ten_khach_hang }}
                </p>
                @if($order->ma_khach_hang)
                <p>Điểm: {{ $order->khachHang->diem_thanh_vien }} | Hạng: {{ $order->khachHang->hang_thanh_vien }}</p>
                @endif
                <p><strong>PT thanh toán:</strong>
                {{ $order->phuong_thuc_thanh_toan === 'COD' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' }}
                </p>
                <p><strong>{{ $order->phuong_thuc_nhan_hang === 'pickup' ? 'Nhận hàng tại quầy' : 'Giao hàng đến' }}:</strong>
                {{ $order->dia_chi }}
                </p>

                @php
                $statusTexts = [0 => 'Chờ xác nhận', 1 => 'Đã xác nhận', 2 => 'Hoàn tất', 3 => 'Đang giao', 4 => 'Đã nhận', 5 => 'Đã hủy'];
                $colorClasses = [0 => 'status-0', 1 => 'status-1', 2 => 'status-2', 3 => 'status-3', 4 => 'status-4', 5 => 'status-5'];
                $st = $order->trang_thai;
                @endphp

                <p><strong>Trạng thái:</strong>
                <span class="status {{ $colorClasses[$st] ?? 'status-default' }}">{{ $statusTexts[$st] ?? 'Không xác định' }}</span>
                </p>

                @if ($order->trang_thai === 3)
                <p><strong>Shipper:</strong> {{ $order->giaoHang->ho_ten_shipper ?? 'Chưa có' }}</p>
                <p><strong>SĐT:</strong> {{ $order->giaoHang->so_dien_thoai ?? 'Chưa có' }}</p>
                <p><strong>Trạng thái giao hàng:</strong>
                    @if($order->giaoHang->trang_thai === 0)
                    Đang giao hàng
                    @elseif($order->giaoHang->trang_thai === 1)
                    Giao thành công
                    @else
                    Không thành công
                    @endif
                </p>
                @endif

                @if ($order->trang_thai === 5 && $order->lichSuHuyDonHang->first())
                @php $huy = $order->lichSuHuyDonHang->first(); @endphp
                <p><strong>Người hủy:</strong>
                    {{ $huy->ma_khach_hang === null ? ($huy->ma_nhan_vien ? 'NV ' . $huy->ma_nhan_vien : 'Không xác định') : 'Khách hàng' }}
                </p>
                <p><strong>Lý do:</strong> {{ $huy->ly_do_huy }}</p>
                @endif
            </div>
            </div>
            <h5 class="mb-3 fw-semibold">Chi tiết đơn hàng:</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 45%;">Sản phẩm</th>
                            <th class="text-center" style="width: 15%;">Số lượng</th>
                            <th class="text-end" style="width: 15%;">Đơn giá</th>
                            <th class="text-end" style="width: 20%;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->chiTietHoaDon as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->ten_san_pham }}</td>
                                <td class="text-center">{{ $item->so_luong }}</td>
                                <td class="text-end">{{ number_format($item->don_gia, 0, ',', '.') }}₫</td>
                                <td class="text-end">{{ number_format($item->don_gia * $item->so_luong, 0, ',', '.') }}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                @if ($order->trang_thai < 2)
                <form id="cancelOrderForm" action="{{ route('customer.orders.cancel', $order->ma_hoa_don) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="cancel_reason" id="lyDoHuyInput">
                </form>
                <!-- Nút hủy dùng để kích hoạt SweetAlert -->
                <button type="button" class="btn btn-danger" onclick="showCancelPrompt()">
                    <i class="bi bi-x-circle"></i> Hủy đơn hàng
                </button>
                @endif
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection
@push('scripts')
<script>
    function showCancelPrompt() {
        Swal.fire({
            title: 'Bạn muốn hủy đơn?',
            input: 'text',
            inputLabel: 'Nhập lý do hủy',
            inputPlaceholder: 'Ví dụ: Đặt nhầm, không cần nữa...',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận hủy',
            cancelButtonText: 'Đóng',
            inputValidator: (value) => {
                if (!value) {
                    return 'Vui lòng nhập lý do!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('lyDoHuyInput').value = result.value;
                document.getElementById('cancelOrderForm').submit();
            }
        });
    }
</script>
@endpush