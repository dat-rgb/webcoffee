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
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        font-family: 'Segoe UI', sans-serif;
    }

    .order-details p {
        margin: 4px 0;
    }

    .order-details table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }

    .order-details th,
    .order-details td {
        padding: 10px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .order-details thead {
        background-color: #e9ecef;
    }

    .order-details td img {
        margin-right: 8px;
        vertical-align: middle;
    }

    .text-end {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }
    .order-summary {
        width: 300px;
        float: right;
        margin-top: 20px;
        padding: 15px;
        font-size: 15px;
    }

    .order-summary p {
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
    }

    .order-summary p strong {
        font-weight: 600;
    }
    .status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: bold;
        color: white;
    }
    .status-0 { background-color: #ffc107; }  
    .status-1 { background-color: #17a2b8; }  
    .status-2 { background-color: #28a745; } 
    .status-3 { background-color: #007bff; } 
    .status-4 { background-color: #6f42c1; }  
    .status-5 { background-color: #dc3545; }  
    .status-default { background-color: #6c757d; }
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
            <div style="max-width: 700px; margin: 0 auto; font-family: Arial, sans-serif; line-height: 1.5; display: flex; gap: 30px; flex-wrap: wrap;">
                <div style="flex: 1 1 300px;">
                    <div style="margin-bottom: 12px;">
                        <strong>Mã Hóa Đơn:</strong> <span>{{ $order->ma_hoa_don }}</span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Cửa hàng:</strong> <span>{{ $order->ma_cua_hang }}</span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Nhân viên:</strong> <span>{{ $order->ma_nhan_vien ?? '' }}</span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Thời gian:</strong> <span>{{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Số điện thoại:</strong> <span>{{ $order->so_dien_thoai }}</span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Email:</strong> <span>{{ $order->email }}</span>
                    </div>
                </div>
                <div style="flex: 1 1 300px;">
                    @if($order->ma_khach_hang)
                    <div style="margin-bottom: 6px;">
                        <strong>Khách hàng:</strong> <span>{{ $order->ten_khach_hang }}</span>
                    </div>
                    <div style="margin-bottom: 12px; font-size: 0.9em; color: #555; display: flex; gap: 15px;">
                        <span>Điểm hiện tại: {{ $order->khachHang->diem_thanh_vien }}</span>
                        <span>Hạng: {{ $order->khachHang->hang_thanh_vien }}</span>
                    </div>
                    @else
                    <div style="margin-bottom: 12px;">
                        <strong>Khách hàng:</strong> <span>Guest - {{ $order->ten_khach_hang }}</span>
                    </div>
                    @endif
                    <div style="margin-bottom: 12px;">
                        <strong>{{ $order->phuong_thuc_nhan_hang === "pickup" ? 'Phương thức nhận hàng:' : 'Giao hàng đến:' }}</strong>
                        <span>{{ $order->dia_chi }}</span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Phương thức thanh toán:</strong>
                        @if ($order->phuong_thuc_thanh_toan === "COD")
                            <span>Thanh toán khi nhận hàng (COD)</span>
                            <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                                <strong>Trạng thái thanh toán</strong>
                                @php
                                    $statusColors = [
                                        0 => '#ff9800', 1 => '#2196f3','default' => '#9e9e9e',
                                    ];
                                    $statusTexts = [
                                        0 => 'Chưa thanh toán', 1 => 'Đã thanh toán', 
                                    ];
                                    $st = $order->trang_thai_thanh_toan;
                                    $color = $statusColors[$st] ?? $statusColors['default'];
                                    $text = $statusTexts[$st] ?? 'Không xác định';
                                @endphp
                                <span style="padding: 6px 14px; border-radius: 20px; background-color: {{ $color }}; color: white; font-weight: 600;">
                                    {{ $text }}
                                </span>
                            </div>  
                        @elseif ($order->phuong_thuc_thanh_toan === "NAPAS247")
                            <span>Chuyển khoản qua ngân hàng MB Bank </span>
                            <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                                <strong>Trạng thái thanh toán</strong>
                                @php
                                    $statusColors = [
                                        'PENDING' => '#ff9800', 
                                        'CANCELLED' => '#9e9e9e',
                                        'SUCCESS' => '#2e7d32', 
                                        'FAILED' => '#f44336',                            
                                        'default' => '#9e9e9e',
                                    ];
                                    $statusTexts = [
                                        'PENDING' => 'Đang xử lý', 
                                        'CANCELLED' => 'Đã hủy giao dịch',
                                        'SUCCESS' => 'Đã thanh toán',
                                        'FAILED' => 'Thanh toán thất bại',
                                    ];
                                    $st = $order->transaction->trang_thai;
                                    $color = $statusColors[$st] ?? $statusColors['default'];
                                    $text = $statusTexts[$st] ?? 'Không xác định';
                                @endphp
                                <span style="padding: 6px 14px; border-radius: 20px; background-color: {{ $color }}; color: white; font-weight: 600;">
                                    {{ $text }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                        <strong>Trạng thái đơn hàng:</strong>
                        @php
                            $statusColors = [
                            0 => '#ff9800', 1 => '#2196f3', 2 => '#4caf50',
                            3 => '#03a9f4', 4 => '#2e7d32', 5 => '#f44336',
                            'default' => '#9e9e9e',
                            ];
                            $statusTexts = [
                            0 => 'Chờ xác nhận', 1 => 'Đã xác nhận', 2 => 'Hoàn tất đơn hàng',
                            3 => $order->phuong_thuc_nhan_hang === 'pickup' ? 'Chờ nhận hàng' : 'Đang giao', 4 => 'Đã nhận', 5 => 'Đã hủy',
                            ];
                            $st = $order->trang_thai;
                            $color = $statusColors[$st] ?? $statusColors['default'];
                            $text = $statusTexts[$st] ?? 'Không xác định';
                        @endphp
                        <span style="padding: 6px 14px; border-radius: 20px; background-color: {{ $color }}; color: white; font-weight: 600;">
                            {{ $text }}
                        </span>
                    </div>
                    @if ($order->trang_thai === 3 && $order->phuong_thuc_nhan_hang !== 'pickup')
                        <div style="margin-bottom: 6px;">
                            <strong>Shipper: </strong> <span>{{ $order->giaoHang->ho_ten_shipper ?? 'Chưa có' }}</span>
                        </div>
                        <div style="margin-bottom: 12px; font-size: 0.9em; color: #555; display: flex; gap: 15px;">
                            <span>Số điện thoại: {{ $order->giaoHang->so_dien_thoai ?? 'Chưa có' }}</span>
                            <span>Trạng thái: 
                                @if ( $order->giaoHang->trang_thai === 0)
                                    Đang giao hàng    
                                @elseif ( $order->giaoHang->trang_thai === 1)       
                                    Giao hàng thàng công
                                @elseif ( $order->giaoHang->trang_thai === 2)   
                                    Giao hàng không thành công
                                @endif
                            </span>
                        </div>
                    @elseif($order->trang_thai === 5)
                        @php
                            $huy = $order->lichSuHuyDonHang->first();
                        @endphp
                        @if ($order->trang_thai === 5 && $huy)
                            <div style="margin-bottom: 6px;">
                                <strong>Người hủy: </strong> 
                                <span>
                                    @if ($huy->ma_khach_hang !== null)
                                        Khách hàng
                                    @elseif ($huy->ma_nhan_vien !== null)
                                        Nhân viên {{ optional($huy->nhanVien)->ho_ten_nhan_vien ?? '(Không rõ tên)' }}
                                    @elseif ($order->ma_khach_hang === null)
                                        Khách (Guest)
                                    @else
                                        Admin
                                    @endif
                                </span>
                            </div>

                            <div style="margin-bottom: 12px; font-size: 0.9em; color: #555; display: flex; gap: 15px;">
                                <span>Lý do hủy: {{ $huy->ly_do_huy }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <h6 class="mt-4 mb-3"><strong>Chi tiết đơn hàng:</strong></h6>
            <div style="overflow-x:auto;">
                <table class="table table-striped align-middle" style="min-width: 600px;">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Sản phẩm</th>
                            <th style="width: 80px;" class="text-center">Số lượng</th>
                            <th style="width: 120px;" class="text-end">Đơn giá</th>
                            <th style="width: 140px;" class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->chiTietHoaDon as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('storage/' . ($item->sanPham->hinh_anh ?? 'default.png')) }}" alt="Ảnh" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                                    <div>
                                        <div>{{ $item->ten_san_pham }} - {{ $item->ten_size }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->so_luong }}</td>
                            <td class="text-end">{{ number_format($item->don_gia + $item->gia_size, 0, ',', '.') }} đ</td>
                            <td class="text-end">{{ number_format($item->thanh_tien, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div  style="max-width: 320px; margin-left: auto; font-size: 1rem;">
                <p class="d-flex justify-content-between"><strong>Tạm tính:</strong> <span>{{ number_format($order->tam_tinh, 0, ',', '.') }} đ</span></p>
                <p class="d-flex justify-content-between"><strong>Giảm giá:</strong> <span>- {{ number_format($order->giam_gia, 0, ',', '.') }} đ</span></p>
                <p class="d-flex justify-content-between"><strong>Phí ship:</strong> <span>{{ number_format($order->tien_ship, 0, ',', '.') }} đ</span></p>
                <hr>
                <p class="d-flex justify-content-between fw-bold fs-5 text-danger"><strong>Thành tiền:</strong> <span>{{ number_format($order->tong_tien, 0, ',', '.') }} đ</span></p>
            </div>
            <div>
                @if ($order->trang_thai < 2)
                <form id="cancelOrderForm" action="{{ route('customer.orders.cancel', $order->ma_hoa_don) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="cancel_reason" id="lyDoHuyInput">
                </form> 
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