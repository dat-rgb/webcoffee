@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
    #phieuModal table tbody tr {
        line-height: 1;              /* Giảm khoảng cách dòng */
    }

    #phieuModal table tbody td {
        padding-top: 4px;
        padding-bottom: 4px;
        white-space: nowrap;         /* Không cho xuống dòng */
        vertical-align: middle;      /* Căn giữa nội dung */
    }
    #phieuModal table thead th {
        padding-top: 4px;
        padding-bottom: 4px;
        white-space: nowrap;         /* Không cho xuống dòng */
        text-align: center;      /* Căn giữa nội dung */
    }

    #phieuModal .modal-body {
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
                <ul class="mb-3 breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Cửa hàng nguyên liệu</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('admins.shopmaterial.showAllPhieu') }}">Phiếu nhập xuất hủy</a></li>
                </ul>
            </div>

            <div class="card">
                <div class="card-header">
                    <form id="formFilter" action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-12 col-lg-3">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Tìm kiếm..."
                                    value="{{ request('search') }}"
                                    autocomplete="off"
                                >
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fa fa-search text-muted"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="loai_phieu" id="selectLoaiPhieu" class="form-select">
                                <option value="">-- Các loại phiếu --</option>
                                <option value="0" {{ request('loai_phieu') === '0' ? 'selected' : '' }}>Nhập</option>
                                <option value="1" {{ request('loai_phieu') === '1' ? 'selected' : '' }}>Xuất</option>
                                <option value="2" {{ request('loai_phieu') === '2' ? 'selected' : '' }}>Hủy</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Mã cửa hàng</th>
                                    <th>Mã nhân viên</th>
                                    <th>Tổng tiền</th>
                                    <th>Loại phiếu</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($danhSachPhieu as $index => $phieu)
                                    <tr class="text-center clickable-row"
                                        data-ma-nv="{{ $phieu->ma_nhan_vien ?? 'ADMIN' }}"
                                        data-loai-phieu="{{ $phieu->loai_phieu }}"
                                        data-ngay-tao="{{ $phieu->ngay_tao_phieu }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $phieu->ma_cua_hang }}</td>
                                        <td>{{ $phieu->ma_nhan_vien ?? 'ADMIN' }}</td>
                                        <td style="white-space: nowrap; text-align: center;">
                                            {{ number_format($phieu->tong_tien, 0, ',', '.') }} đ
                                        </td>

                                        <td>
                                            @php
                                                $badgeClass = ['badge-success', 'badge-primary', 'badge-danger'][$phieu->loai_phieu] ?? 'badge-secondary';
                                                $label = ['Nhập', 'Xuất', 'Hủy'][$phieu->loai_phieu] ?? 'Không rõ';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($phieu->ngay_tao_phieu)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="100%">
                                        <div class="py-5 my-5 text-center">
                                            <i class="mb-3 fa fa-file-invoice fa-3x text-muted"></i>
                                            @if (!empty(request('search')))
                                                <h5 class="text-muted">Không tìm thấy phiếu nào với từ khóa "<strong>{{ request('search') }}</strong>".</h5>
                                                <p>Thử từ khóa khác hoặc kiểm tra lại thông tin tìm kiếm.</p>
                                            @else
                                                <h5 class="text-muted">Không có phiếu nào.</h5>
                                                <p>Hiện tại chưa có phiếu nào được tạo.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="phieuModal" tabindex="-1" aria-labelledby="phieuModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Chi Tiết Nguyên Liệu Trong Phiếu <span id="modal_ma_lo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                  </div>
                  <div class="modal-body">
                        <p><strong>Người tạo phiếu: </strong> <span id="modal_nguoi_lam"></span></p>
                        <p><strong>Loại phiếu: </strong> <span id="modal_loai_phieu"></span></p>
                        <p><strong>Thời gian tạo: </strong> <span id="modal_thoi_gian"></span></p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã nguyên liệu</th>
                                        <th>Tên nguyên liệu</th>
                                        <th>Số lượng</th>
                                        <th>Lô</th>
                                        <th>Giá</th>
                                        <th>Tổng tiền</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody id="chiTietPhieuBody"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
              </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', () => {
            const maNv = row.dataset.maNv;
            const loaiPhieu = row.dataset.loaiPhieu;
            const ngayTao = row.dataset.ngayTao;

            document.getElementById('modal_nguoi_lam').textContent = maNv;

            fetch(`/admin/shop-materials/phieu-chi-tiet/${encodeURIComponent(ngayTao)}/${loaiPhieu}/${maNv}`)
                .then(response => response.json())
                .then(data => {
                    const meta = data.meta;
                    const chiTiet = data.chi_tiet;

                    document.getElementById('modal_ma_lo').textContent = (loaiPhieu == 0) ? 'Không có' : meta.so_lo;
                    document.getElementById('modal_nguoi_lam').textContent = meta.ma_nhan_vien ?? 'ADMIN';

                    const loaiPhieuLabel = ['Nhập', 'Xuất', 'Hủy'][parseInt(meta.loai_phieu)] ?? 'Không rõ';
                    document.getElementById('modal_loai_phieu').textContent = loaiPhieuLabel;

                    const ngayTao = new Date(meta.ngay_tao_phieu);
                    document.getElementById('modal_thoi_gian').textContent = ngayTao.toLocaleString('vi-VN', {
                        day: '2-digit', month: '2-digit', year: 'numeric',
                        hour: '2-digit', minute: '2-digit'
                    });

                    const tbody = document.getElementById('chiTietPhieuBody');
                    tbody.innerHTML = '';
                    chiTiet.forEach(item => {
                        const tr = document.createElement('tr');
                        const soLoText = loaiPhieu == 0 ? 'Không có' : item.so_lo ?? 'Không có';
                        tr.innerHTML = `
                            <td>${item.ma_nguyen_lieu}</td>
                            <td>${item.ten_nguyen_lieu}</td>
                            <td>${item.so_luong}</td>
                            <td>${soLoText}</td>
                            <td>${item.gia_tien.toLocaleString('vi-VN')} đ</td>
                            <td>${item.tong_tien.toLocaleString('vi-VN')} đ</td>
                            <td>${item.ghi_chu ?? ''}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                    new bootstrap.Modal(document.getElementById('phieuModal')).show();
                });
        });
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectLoaiPhieu = document.getElementById('selectLoaiPhieu');
    const form = document.getElementById('formFilter');

    if (selectLoaiPhieu && form) {
        selectLoaiPhieu.addEventListener('change', function () {
            form.submit(); // Tự động submit khi thay đổi select
        });
    }
});
</script>
@endpush
@endsection
