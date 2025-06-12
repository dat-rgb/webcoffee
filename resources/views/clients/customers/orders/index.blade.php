@extends('layouts.app')

@section('title', $title)
@push('styles')
<style>
.star.selected {
    color: gold;
}
</style>
@endpush
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
                            <div class="card shadow-sm border rounded-2">
                                <div class="card-body d-flex justify-content-between flex-wrap align-items-center">
                                    {{-- Thông tin đơn hàng --}}
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">
                                            <a href="#" data-toggle="modal" data-target="#orderModal-{{ $order->id }}" class="text-decoration-none text-primary">
                                                Mã đơn: {{ $order->ma_hoa_don }}
                                            </a>
                                        </h5>
                                        <div class="small text-muted">
                                            Ngày đặt: {{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}
                                        </div>

                                        <div class="mt-2">
                                            <strong>Tổng tiền:</strong>
                                            <span class="text-danger fw-bold fs-5">
                                                {{ number_format($order->tong_tien, 0, ',', '.') }}đ
                                            </span>
                                        </div>

                                        {{-- Trạng thái đơn --}}
                                        <div class="mt-2">
                                            <span class="badge badge-pill {{
                                                [
                                                    0 => 'badge-warning text-white', 
                                                    1 => 'badge-primary text-white', 
                                                    2 => 'badge-info text-white',
                                                    3 => 'badge-info text-white', 
                                                    4 => 'badge-success text-white', 
                                                    5 => 'badge-danger text-white'
                                                ][$order->trang_thai] ?? 'badge-secondary'
                                            }}">
                                                {{
                                                    [
                                                        0 => 'Chờ xác nhận',
                                                        1 => 'Đã xác nhận',
                                                        2 => 'Đơn hàng đã hoàn tất',
                                                        3 => $order->phuong_thuc_nhan_hang === 'pickup' ? 'Chờ nhận hàng' : 'Đang giao hàng',
                                                        4 => 'Đã nhận',
                                                        5 => 'Đã hủy'
                                                    ][$order->trang_thai] ?? 'Không rõ'
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Hành động bên phải --}}
                                    <div class="text-right mt-3 mt-md-0" style="min-width: 240px;">
                                        @if($order->trang_thai < 2)
                                            {{-- Nút hủy đơn --}}
                                            <form id="cancelOrderForm" action="{{ route('customer.orders.cancel', $order->ma_hoa_don) }}" method="POST" style="display: none;">
                                                @csrf
                                                <input type="hidden" name="cancel_reason" id="lyDoHuyInput">
                                            </form>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="showCancelPrompt()">
                                                <i class="bi bi-x-circle"></i> Hủy đơn hàng
                                            </button>

                                        @elseif($order->trang_thai == 3)
                                            {{-- Trạng thái giao hàng --}}
                                            @if($order->phuong_thuc_nhan_hang !== 'pickup')
                                                <div class="small">
                                                    <div><strong>Mã vận đơn:</strong> {{ $order->giaoHang->ma_van_don ?? 'Chưa cập nhật' }}</div>
                                                    <div><strong>Shipper:</strong> {{ $order->giaoHang->ho_ten_shipper ?? 'Chưa cập nhật' }}</div>
                                                    <div><strong>SDT:</strong> {{ $order->giaoHang->so_dien_thoai ?? 'Chưa cập nhật' }}</div>
                                                    <div>
                                                        <strong>Trạng thái:</strong> 
                                                        <span class="badge badge-info">
                                                            {{
                                                                [
                                                                    0 => 'Đang giao hàng',
                                                                    1 => 'Giao hàng thành công',
                                                                    2 => 'Giao hàng thất bại'
                                                                ][$order->giaoHang->trang_thai] ?? 'Chưa rõ'
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="small">
                                                    <strong>Nhận tại quầy</strong>
                                                    <p class="mb-0">{{ $order->dia_chi }}</p>
                                                </div>
                                            @endif
                                        @elseif ($order->trang_thai == 4)
                                            @php
                                                // Kiểm tra có sản phẩm nào chưa được đánh giá trong đơn này không
                                                $hasPendingReview = false;
                                                foreach ($order->chiTietHoaDon as $item) {
                                                    $exists = \App\Models\Review::where('ma_san_pham', $item->ma_san_pham)
                                                        ->where('ma_hoa_don', $order->ma_hoa_don)
                                                        ->where('ma_khach_hang', Auth::user()->khachHang->ma_khach_hang)
                                                        ->doesntExist();

                                                    if ($exists) {
                                                        $hasPendingReview = true;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            @if ($hasPendingReview)
                                                @foreach ($order->chiTietHoaDon as $item)
                                                    @php
                                                        $hasReviewed = \App\Models\Review::where('ma_san_pham', $item->ma_san_pham)
                                                            ->where('ma_hoa_don', $order->ma_hoa_don)
                                                            ->where('ma_khach_hang', Auth::user()->khachHang->ma_khach_hang)
                                                            ->exists();
                                                    @endphp

                                                    @if (!$hasReviewed)
                                                        <button 
                                                            class="btn btn-warning btn-sm mb-1" 
                                                            onclick="showReviewPrompt('{{ $item->ma_san_pham }}', '{{ $item->ten_san_pham }}', '{{ $order->ma_hoa_don }}')">
                                                            Đánh giá: {{ $item->ten_san_pham }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            @else
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#reviewModal-{{ $order->ma_hoa_don }}">
                                                    Xem đánh giá của bạn
                                                </button>
                                            @endif
                                        @endif
                                    </div>
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
                                        <div class="mt-3">
                                            <small class="d-block">Cửa hàng: <strong>{{ $order->ma_cua_hang }}</strong></small>
                                            <small class="d-block">Ngày đặt:<strong>{{ \Carbon\Carbon::parse($order->ngay_lap_hoa_don)->format('d/m/Y H:i:s') }}</strong></small>
                                            <small class="d-block">Giao hàng: <strong>{{ $order->dia_chi }}</strong></small>
                                            <small class="d-block">Phương thức thanh toán: <strong>{{ $order->phuong_thuc_thanh_toan === 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</strong></small>
                                            <small class="d-block">Trạng thái thanh toán: 
                                                <span class="badge {{
                                                [ 1 =>'badge-success', 0 =>'badge-danger'][$order->trang_thai_thanh_toan ?? ''] ?? 'badge-secondary'
                                                }}">
                                                {{
                                                    [ 1 =>'Đã thanh toán', 0 =>'Chưa thanh toán'][$order->trang_thai_thanh_toan ?? ''] ?? 'Chưa thanh toán'
                                                }}
                                                </span>
                                            </small>
                                            <small class="d-block">Trạng thái đơn hàng: 
                                                <span class="badge {{
                                                    [
                                                        0 => 'badge-warning text-white',     
                                                        1 => 'badge-primary text-white',                
                                                        2 => 'badge-info text-white',       
                                                        3 => 'badge-info text-white',                 
                                                        4 => 'badge-success text-white',             
                                                        5 => 'badge-danger text-white'                 
                                                    ][$order->trang_thai] ?? 'badge-secondary text-white'
                                                }}">
                                                {{
                                                    [
                                                        0 => 'Chờ xác nhận',
                                                        1 => 'Đã xác nhận',
                                                        2 => 'Đơn hàng đã hoàn tất',
                                                        3 => $order->phuong_thuc_nhan_hang === 'pickup' ? 'Chờ nhận hàng' : 'Đang giao hàng',
                                                        4 => 'Đã nhận hàng',
                                                        5 => 'Đã hủy'
                                                    ][$order->trang_thai] ?? 'Không rõ'
                                                }}
                                                </span>
                                            </small>
                                        </div>
                                        <hr>
                                        @forelse ($order->chiTietHoaDon as $item)
                                            <div class="d-flex align-items-start mb-3">
                                                <img src="{{ asset('storage/' . $item->sanPham->hinh_anh) }}" alt="{{ $item->ten_san_pham }}" class="rounded mr-3" width="60" height="60">
                                                <div>
                                                    <div><strong>{{ $item->ten_san_pham }}</strong> <small class="text-muted">({{ $item->ten_size }})</small></div>
                                                    <div class="text-muted small">{{ $item->so_luong }} x {{ number_format($item->don_gia + $item->gia_size, 0, ',', '.') }}đ</div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">Không có sản phẩm nào</p>
                                        @endforelse
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span>Tạm tính:</span>
                                            <strong>{{ number_format($order->tam_tinh ?? 0, 0, ',', '.') }}đ</strong>
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
                                            <strong>{{ number_format($order->tong_tien, 0, ',', '.') }}đ</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal đánh giá sản phẩm -->
                        <div class="modal fade" id="reviewModal-{{ $order->ma_hoa_don }}" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel-{{ $order->ma_hoa_don }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content rounded shadow">
                                    <div class="modal-header d-flex align-items-center">
                                        <h5 class="modal-title flex-grow-1" id="reviewModalLabel-{{ $order->ma_hoa_don }}">
                                            Đánh giá các sản phẩm đơn hàng #{{ $order->ma_hoa_don }}
                                        </h5>
                                        <button type="button" class="close text-black" data-dismiss="modal" aria-label="Đóng">
                                            <span aria-hidden="true" style="font-size:1.6rem;">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        @foreach ($order->chiTietHoaDon as $item)
                                            @php
                                              $review = \App\Models\Review::where([
                                                    'ma_san_pham'   => $item->ma_san_pham,
                                                    'ma_hoa_don'    => $order->ma_hoa_don,
                                                    'ma_khach_hang' => Auth::user()->khachHang->ma_khach_hang,
                                                ])->first();
                                            @endphp

                                            <div class="card mb-3 shadow-sm border-0">
                                                    <div class="card-body p-4">
                                                        <div class="d-flex align-items-start mb-3">
                                                            <img src="{{ asset('storage/' . $item->sanPham->hinh_anh) }}" alt="{{ $item->ten_san_pham }}" class="rounded mr-3" width="60" height="60">
                                                        <div>
                                                            <div><strong>{{ $item->ten_san_pham }}</strong> <small class="text-muted">({{ $item->ten_size }})</small></div>
                                                            <div class="text-muted small">{{ $item->so_luong }} x {{ number_format($item->don_gia + $item->gia_size, 0, ',', '.') }}đ</div>
                                                        </div>
                                                    </div>
                                                    @if($review)
                                                        <div class="d-flex align-items-center mb-2">
                                                            <strong class="mr-3">Điểm đánh giá:</strong>
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    <i class="fas fa-star text-warning mr-1"></i>
                                                                @else
                                                                    <i class="far fa-star text-muted mr-1"></i>
                                                                @endif
                                                            @endfor
                                                            <small class="text-muted ml-3">({{ $review->rating }}/5)</small>
                                                        </div>
                                                        <p class="font-italic text-secondary mb-0" style="white-space: pre-line;">{{ $review->danh_gia }}</p>
                                                    @else
                                                        <p class="text-muted font-italic mb-0">Bạn chưa đánh giá sản phẩm này.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
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

function showReviewPrompt(maSanPham, tenSanPham, maHoaDon) {
    console.log('maHoaDon:', maHoaDon); // Debug thử
    Swal.fire({
        title: `Đánh giá sản phẩm: ${tenSanPham}`,
        html: `
            <div id="starRating" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;">
                ${[1,2,3,4,5].map(i => `
                    <i class="bi bi-star"
                    id="star-${i}"
                    onclick="setRating(${i})"
                    style="font-size: 2rem; cursor: pointer; color: gray; transition: transform 0.2s;"
                    onmouseover="this.style.transform='scale(1.2)'"
                    onmouseout="this.style.transform='scale(1)'"
                    ></i>
                `).join('')}
            </div>
            <textarea id="reviewText" class="swal2-textarea mt-3" placeholder="Nhận xét của bạn (không bắt buộc)"></textarea>
            <input type="hidden" id="ratingValue" value="0">
            <input type="hidden" id="maHoaDon" value="${maHoaDon}">
        `,
        showCancelButton: true,
        confirmButtonText: 'Gửi đánh giá',
        cancelButtonText: 'Hủy',
        preConfirm: () => {
            const rating = parseInt(document.getElementById('ratingValue').value);
            const comment = document.getElementById('reviewText').value;
            const maHoaDonInput = document.getElementById('maHoaDon').value;

            if (rating < 1) {
                Swal.showValidationMessage('Bạn cần chọn ít nhất 1 sao!');
                return false;
            }

            return {
                maSanPham,
                maHoaDon: maHoaDonInput,  // lấy giá trị từ input ẩn
                rating,
                comment
            };
        }
    }).then((result) => {
        if(result.isConfirmed) {
            const { maSanPham, maHoaDon, rating, comment } = result.value;
            sendReview(maSanPham, maHoaDon, rating, comment);
        }
    });
}

function setRating(star) {
    document.getElementById('ratingValue').value = star;
    for (let i = 1; i <= 5; i++) {
        const starIcon = document.getElementById(`star-${i}`);
        if (i <= star) {
            starIcon.classList.remove('bi-star');
            starIcon.classList.add('bi-star-fill');
            starIcon.style.color = 'gold';
        } else {
            starIcon.classList.add('bi-star');
            starIcon.classList.remove('bi-star-fill');
            starIcon.style.color = 'gray';
        }
    }
}

function sendReview(maSanPham, maHoaDon, rating, comment) {
    console.log('🟡 Sending Review Request:', {
        maHoaDon,
        maSanPham,
        rating,
        comment
    });

    fetch('/customer/review', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ maSanPham, maHoaDon, rating, comment })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Thành công', 'Cảm ơn bạn đã đánh giá!', 'success')
                .then(() => location.reload());
        } else {
            Swal.fire('Lỗi', res.message ?? 'Đánh giá thất bại', 'error');
        }
    })
    .catch(err => {
        console.error('🔴 Fetch Error:', err);
        Swal.fire('Lỗi mạng', 'Thử lại sau nhé!', 'error');
    });
}

</script>
@endpush