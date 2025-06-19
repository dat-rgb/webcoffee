@extends('layouts.app')
@section('title',$title)
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & tea</p>
                    <h1>Thông tin đơn hàng</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Nội dung kết quả thanh toán -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            @if (isset($error))
                <div class="alert alert-danger p-4 rounded shadow-sm">
                    <h4 class="mb-2"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Thất bại</h4>
                    <p>{{ $error }}</p>
                    <a href="{{ route('home') }}" class="btn btn-outline-danger mt-3">
                        <i class="fas fa-home me-1"></i> Quay về trang chủ
                    </a>
                </div>
            @else
                <div class="card border-0 shadow-lg p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if($hoaDon->trang_thai == 0)
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h3 class="text-success">Đặt hàng thành công!</h3>
                                <p>Đơn hàng của bạn đã được ghi nhận và đang chờ cửa hàng xác nhận.</p>
                            @elseif($hoaDon->trang_thai == 1)
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h3 class="text-success">Đơn hàng đã được xác nhận!</h3>
                                <p>Cửa hàng đang chuẩn bị món của bạn.</p>
                            @elseif($hoaDon->trang_thai == 2)
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h3 class="text-success">Đơn hàng đã hoàn tất</h3>
                                <p>Cửa hàng đang chuẩn bị món của bạn.</p>
                                @elseif($hoaDon->trang_thai == 3)
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                @if($hoaDon->phuong_thuc_nhan_hang === 'pickup')
                                    <h3 class="text-success">Chờ nhận món</h3>
                                    <p>Mời bạn đến cửa hàng để nhận món.</p>
                                @else
                                    <h3 class="text-success">Đang giao hàng</h3>
                                    <p>Shipper đang giao hàng đến bạn. Vui lòng giữ điện thoại bên cạnh để tiện liên lạc.</p>
                                    <p>Thông tin giao hàng</p>
                                @endif
                            @elseif($hoaDon->trang_thai == 4)
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h3 class="text-success">Hoàn thành</h3>
                                <p>Cảm ơn bạn đã sử dụng dịch vụ!</p>
                            @elseif($hoaDon->trang_thai == 5)
                                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                                <h3 class="text-danger">Đơn hàng đã bị hủy</h3>
                                <p>Đơn hàng đã được hủy. Cảm ơn bạn đã ghé thăm cửa hàng!</p>
                            @else
                                <h3 class="text-success">Trạng thái đơn hàng</h3>
                                <p>Đơn hàng của bạn đang được xử lý.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="mb-1"><strong>Mã hóa đơn:</strong> {{ $hoaDon->ma_hoa_don }}</p>
                        </div>

                        @php
                            $trangThai = $hoaDon->trang_thai;
                            $isNapas = $hoaDon->phuong_thuc_thanh_toan === 'NAPAS247';
                            $isPickup = $hoaDon->phuong_thuc_nhan_hang === 'pickup';

                            $giaoHangLabel = $isPickup ? 'Chờ nhận món' : 'Đang giao hàng';

                            $timelineSteps = $isNapas
                                ? ['Đã thanh toán', 'Đã xác nhận', 'Đã hoàn tất', $giaoHangLabel, 'Hoàn thành']
                                : ['Chờ xác nhận', 'Đã xác nhận', 'Đã hoàn tất', $giaoHangLabel, 'Hoàn thành', 'Đã thanh toán'];
                            
                            $icons = ['fas fa-money-check-alt', 'fas fa-clipboard-check', 'fas fa-cogs', 'fas fa-motorcycle', 'fas fa-check-circle', 'fas fa-dollar-sign'];

                        @endphp
                        @if($hoaDon->trang_thai < 5)
                        <div class="timeline mb-4">
                            <h6 class="font-weight-bold mb-3">Trạng thái đơn hàng</h6>
                            <div class="position-relative" style="overflow-x: auto;">
                                <div class="d-flex align-items-center position-relative" style="min-width: 600px; padding: 0 16px;">
                                    @foreach($timelineSteps as $index => $step)
                                        @php
                                            $stepIndex = $isNapas ? $index : ($index < 5 ? $index : 5);
                                            $active = $isNapas
                                                ? $trangThai >= $stepIndex
                                                : ($index < 5 ? $trangThai >= $stepIndex : $hoaDon->trang_thai_thanh_toan == 1);
                                        @endphp

                                        <div class="text-center flex-grow-1 position-relative" style="min-width: 100px; z-index: 1;">
                                            <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background-color: {{ $active ? '#28a745' : '#ccc' }};
                                                color: white; font-size: 14px;">
                                                <i class="{{ $icons[$index] }}"></i>
                                            </div>
                                            <small class="{{ $active ? 'text-success' : 'text-muted' }}">{{ $step }}</small>
                                        </div>
                                    @endforeach

                                    {{-- Đường kẻ nền xám --}}
                                    <div class="position-absolute" style="top: 16px; left: 28px; right: 28px; height: 2px; background-color: #ccc; z-index: 0;"></div>

                                    {{-- Đường kẻ tiến trình --}}
                                    @php
                                        $totalSteps = count($timelineSteps);
                                        $progressPercent = $isNapas
                                            ? min($trangThai + 1, $totalSteps) / $totalSteps * 100
                                            : ($hoaDon->trang_thai_thanh_toan == 1 ? 100 : (($trangThai + 1) / 6) * 100);
                                    @endphp
                                    <div class="position-absolute" style="top: 16px; left: 28px; height: 2px;
                                        background-color: #28a745; z-index: 0; width: calc({{ $progressPercent }}% - 56px);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($hoaDon->trang_thai < 1)
                        <div class="text-center mb-4">
                            <h6 class="text-success">Đang đợi cửa hàng xác nhận đơn hàng của bạn!</h6>
                            <h6 class="text-success">Nếu quá 5 phút mà chưa tiếp nhận bạn có thể nhấn vào gọi okay!</h6>
                        </div>
                        @endif
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 font-weight-bold">Thông tin đơn hàng</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle text-nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="w-50">Sản phẩm</th>
                                                <th class="text-center">SL</th>
                                                <th class="text-end">Đơn giá</th>
                                                <th class="text-end">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($hoaDon->chiTietHoaDon as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->ten_san_pham ?? 'N/A' }}
                                                        @if (!empty($item->ten_size))
                                                        <small class="text-muted"> - {{ $item->ten_size }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $item->so_luong }}</td>
                                                    <td class="text-end">{{ number_format($item->don_gia + ($item->gia_size ?? 0), 0, ',', '.') }} đ</td>
                                                    <td class="text-end">{{ number_format($item->thanh_tien, 0, ',', '.') }} đ</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3">Chi tiết thanh toán</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($hoaDon->tam_tinh, 0, ',', '.') }} đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giảm giá:</span>
                                    <span>-{{ number_format($hoaDon->giam_gia, 0, ',', '.') }} đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí ship:</span>
                                    <span>{{ number_format($hoaDon->tien_ship, 0, ',', '.') }} đ</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold text-danger">
                                    <span>Tổng tiền:</span>
                                    <span>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left me-1"></i> Quay về trang chủ
                        </a>
                
                        @if ($hoaDon->trang_thai < 2)
                            <form id="cancelOrderForm" action="{{ route('customer.orders.cancel', $hoaDon->ma_hoa_don) }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="cancel_reason" id="lyDoHuyInput">
                            </form> 
                            <button type="button" class="btn btn-danger" onclick="showCancelPrompt()">
                                <i class="bi bi-x-circle"></i> Hủy đơn hàng
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
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