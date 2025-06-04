@extends('layouts.app')

@section('title', $title)

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>Customer</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- orders section -->
<div class="contact-from-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            @include('clients.customers.sub_layout_customer')

            <div class="col-lg-8">
                @if($orders->isEmpty())
                    <div class="text-center text-muted">
                        <p>Bạn chưa có đơn hàng nào. <a href="{{ route('product') }}">Mua sắm ngay</a></p>
                    </div>
                @else
                <div class="row">
                    @foreach ($orders as $order)
                        <div class="col-md-12 mb-4">
                            <div class="card shadow-sm rounded-lg border">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="#" data-toggle="modal" data-target="#orderModal-{{ $order->id }}">
                                            #{{ $order->ma_hoa_don }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                        <div class="mt-1">
                                            <strong>Tổng tiền:</strong> 
                                            <small class="text-danger fw-bold fs-5">
                                            {{ number_format($order->tong_tien - $order->giam_gia + $order->tien_ship, 0, ',', '.') }}đ
                                            </small>
                                        </div>
                                        <span class="badge badge-pill {{
                                            [
                                                0 => 'badge-warning text-dark', 
                                                1 => 'badge-primary', 
                                                2 => 'badge-info text-dark',
                                                3 => 'badge-info', 
                                                4 => 'badge-success', 
                                                5 => 'badge-danger'
                                            ][$order->trang_thai] ?? 'badge-secondary'
                                        }}">
                                            {{
                                            [
                                                0 => 'Chờ xác nhận', 
                                                1 => 'Đã xác nhận', 
                                                2 => 'Đơn hàng đã hoàn tất',
                                                3 => 'Đang giao hàng', 
                                                4 => 'Đã nhận', 
                                                5 => 'Đã hủy'
                                            ][$order->trang_thai] ?? 'Không rõ'
                                            }}
                                        </span>
                                    </div>
                                    @if($order->trang_thai < 2)
                                    <form id="cancelOrderForm" action="{{ route('customer.orders.cancel', $order->ma_hoa_don) }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="cancel_reason" id="lyDoHuyInput">
                                    </form> 
                                    <!-- Nút hủy dùng để kích hoạt SweetAlert -->
                                    <button type="button" class="btn btn-danger" onclick="showCancelPrompt()">
                                        <i class="bi bi-x-circle"></i> Hủy đơn hàng
                                    </button>
                                    @elseif($order->trang_thai == 3)
                                    <div class="ml-3 text-right" style="min-width: 200px;">
                                        <div><strong>Mã vận đơn:</strong> {{ $order->giaoHang->ma_van_don ?? 'Chưa cập nhật' }}</div>
                                        <div><strong>Người giao hàng:</strong> {{ $order->giaoHang->ho_ten_shipper ?? 'Chưa cập nhật' }}</div>
                                        <div><strong>SDT:</strong> {{ $order->giaoHang->so_dien_thoai ?? 'Chưa cập nhật' }}</div>
                                        <div>
                                            <strong>Trạng thái giao hàng:</strong> 
                                            <span class="badge badge-info">
                                            {{
                                                [
                                                0 => 'Đang giao hàng',
                                                1 => 'Giao hàng thành công',
                                                2 => 'Giao hàng không thành công'
                                                ][$order->giaoHang->trang_thai] ?? 'Chưa rõ'
                                            }}
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- Modal chi tiết --}}
                        <div class="modal fade" id="orderModal-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="orderLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">    
                                <div class="modal-content rounded-lg">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="orderLabel-{{ $order->id }}">Chi tiết đơn hàng #{{ $order->ma_hoa_don }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @forelse ($order->chiTietHoaDon as $item)
                                    <div class="d-flex align-items-start mb-3">
                                        <img src="{{ asset('storage/' . $item->sanPham->hinh_anh) }}" alt="{{ $item->ten_san_pham }}" class="rounded mr-3" width="60" height="60">
                                        <div>
                                        <div><strong>{{ $item->ten_san_pham }}</strong> <small class="text-muted">({{ $item->ten_size }})</small></div>
                                        <div class="text-muted small">{{ $item->so_luong }} x {{ number_format($item->don_gia, 0, ',', '.') }}đ</div>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-muted">Không có sản phẩm nào</p>
                                    @endforelse

                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Tạm tính:</span>
                                        <strong>{{ number_format($order->tong_tien ?? 0, 0, ',', '.') }}đ</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Phí ship:</span>
                                        <strong>{{ number_format($order->tien_ship ?? 0, 0, ',', '.') }}đ</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Giảm giá:</span>
                                        <strong>{{ number_format($order->giam_gia ?? 0, 0, ',', '.') }}đ</strong>
                                    </div>
                                    <div class="d-flex justify-content-between text-danger">
                                        <span>Tổng cộng:</span>
                                        <strong>{{ number_format(($order->tong_tien - $order->giam_gia), 0, ',', '.') }}đ</strong>
                                    </div>

                                    <div class="mt-3">
                                    <small class="d-block">Phương thức thanh toán: <strong>{{ $order->phuong_thuc_thanh_toan === 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</strong></small>
                                    <small>Trạng thái thanh toán: 
                                        <span class="badge {{
                                        ['SUCCESS'=>'badge-success', 'FAILED'=>'badge-danger', 'PENDING'=>'badge-info', 'CANCELLED'=>'badge-warning'][$order->transaction->trang_thai ?? ''] ?? 'badge-secondary'
                                        }}">
                                        {{
                                            ['SUCCESS'=>'Thành công', 'FAILED'=>'Thất bại', 'PENDING'=>'Đang xử lý', 'CANCELLED'=>'Đã hủy'][$order->transaction->trang_thai ?? ''] ?? 'Chưa thanh toán'
                                        }}
                                        </span>
                                    </small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @if($order->trang_thai < 2)
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
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
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