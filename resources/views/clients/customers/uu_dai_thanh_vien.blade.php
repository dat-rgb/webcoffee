@extends('layouts.app')
@section('title', $title)
@push('styles')
<style>


</style>

@endpush
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                    <h1>Ưu đãi thành viên</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- orders section -->
<div class="contact-from-section mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 d-lg-none px-3 mb-2">
                <div class="toggle-menu-wrapper text-right">
                    <button class="btn btn-sm"
                            type="button"
                            data-toggle="collapse"
                            data-target="#accountMenu"
                            aria-expanded="false"
                            aria-controls="accountMenu">
                        <i class="fas fa-bars mr-1"></i> Menu
                    </button>
                </div>
            </div>

            @include('clients.customers.sub_layout_customer')
            <div class="col-lg-8">
                @if($voucherMember->isEmpty())
                    <div class="text-center text-muted">
                        <p>Hiện bạn chưa có ưu đãi thành viên nào. <a href="{{ route('product') }}">Mua sắm ngay để nhận ưu đãi</a></p>
                    </div>
                @else
                <div class="row">
                    @foreach ($voucherMember as $voucher)
                        <div class="col-md-12 mb-4">
                            <div class="card shadow-sm border rounded-2">
                                <div class="card-body d-flex justify-content-between flex-wrap align-items-center">
                                    {{-- Thông tin voucher --}}
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold text-dark">
                                            {{ $voucher->ten_voucher }}
                                        </h5>
                                        <div class="small text-muted">
                                            Mã: <code>{{ $voucher->ma_voucher }}</code><br>
                                            HSD: {{ \Carbon\Carbon::parse($voucher->ngay_ket_thuc)->format('d/m/Y') }}<br>
                                            Giảm: 
                                            @if($voucher->gia_tri_giam < 100)
                                                {{ $voucher->gia_tri_giam }}%
                                            @else
                                                {{ number_format($voucher->gia_tri_giam, 0, ',', '.') }}đ
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Nút sao chép mã --}}
                                    <div class="text-right mt-3 mt-md-0" style="min-width: 180px;">
                                        <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('{{ $voucher->ma_voucher }}')">
                                            <i class="bi bi-clipboard"></i> Sao chép mã
                                        </button>
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
function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Đã sao chép!',
                text: `Mã "${text}" đã được sao chép`,
                showConfirmButton: false,
                timer: 2000
            });
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể sao chép mã',
                confirmButtonText: 'OK'
            });
        });
}
</script>
@endpush
