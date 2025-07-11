@extends('layouts.staff')

@section('title', 'Hủy nguyên liệu')
@section('subtitle', 'Chọn lô để hủy nguyên liệu')

@section('content')
<div class="page-inner">
    @if ($materials->isEmpty())
        <div class="alert alert-warning">
            Không có nguyên liệu nào để hủy.
        </div>
    @else
        <form action="{{ route('staffs.shop_materials.destroy') }}" method="POST">
            @csrf
            <div class="card">
                <div class="shadow-sm card rounded-4">
                    <div class="card-header bg-light rounded-top-4">
                        <h3 class="fw-bold">Hủy nguyên liệu khỏi kho cửa hàng <strong>{{ $ma_cua_hang }}</strong></h3>
                        <h5>Ngày hủy: {{ $today }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive rounded-3">
                            <table class="table text-center align-middle table-bordered table-hover">
                                <thead class="table-danger">
                                    <tr>
                                        <th>Mã nguyên liệu</th>
                                        <th>Nguyên liệu</th>
                                        <th>Số lượng tồn</th>
                                        <th>Chọn lô cần hủy</th>
                                        <th>Số lượng hủy</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $material)
                                    <tr>
                                        <td>{{ $material->nguyenLieu->ma_nguyen_lieu }}</td>
                                        <td class="text-start">
                                            {{ $material->nguyenLieu->ten_nguyen_lieu }}
                                            ({{ rtrim(rtrim(number_format($material->nguyenLieu->so_luong, 2), '0'), '.') }} {{ $material->nguyenLieu->don_vi }})
                                        </td>
                                        @php
                                            $dinhLuong = $material->nguyenLieu->so_luong > 0 ? $material->nguyenLieu->so_luong : 1;
                                            $tonChia = $material->so_luong_ton / $dinhLuong;
                                        @endphp
                                        <td>
                                            {{ rtrim(rtrim(number_format($tonChia, 2), '0'), '.') }} {{ $material->don_vi }}
                                        </td>

                                        <td>
                                            <select name="batch[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                                class="form-select form-select-sm batch-select" required
                                                data-ma_cua_hang="{{ $material->ma_cua_hang }}"
                                                data-ma_nguyen_lieu="{{ $material->ma_nguyen_lieu }}">
                                                <option value="">-- Chọn lô --</option>
                                                @foreach ($material->available_batches as $lo)
                                                    @php
                                                        $dinhLuong = $material->nguyenLieu->so_luong > 0 ? $material->nguyenLieu->so_luong : 1;
                                                        $conLaiChia = $lo['con_lai'] / $dinhLuong;
                                                        $conLaiFormatted = rtrim(rtrim(number_format($conLaiChia, 2), '0'), '.');
                                                    @endphp
                                                    <option value="{{ $lo['so_lo'] }}" data-con_lai="{{ $lo['con_lai'] }}">
                                                        Lô {{ $lo['so_lo'] }} - Còn {{ $conLaiFormatted }} {{ $material->don_vi }} - HSD: {{ \Carbon\Carbon::parse($lo['han_su_dung'])->format('d/m/Y') }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td>
                                            <input type="number"
                                                name="quantity[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                                class="form-control form-control-sm quantity-input"
                                                step="any"
                                                min="0.01"
                                                max="{{ $material->so_luong_ton }}"
                                                data-ma_cua_hang="{{ $material->ma_cua_hang }}"
                                                data-ma_nguyen_lieu="{{ $material->ma_nguyen_lieu }}"
                                                required>
                                        </td>
                                        <td>
                                            <input type="text"
                                                name="note[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                                class="form-control form-control-sm"
                                                placeholder="Nhập ghi chú nếu có">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row">Xóa</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-danger">Xác nhận hủy hàng</button>
                        <a href="{{ route('staffs.shop_materials.index', ['ma_cua_hang' => $ma_cua_hang]) }}" class="btn btn-secondary">Quay lại</a>

                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Xóa dòng ---
    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            row.remove();

            Swal.fire({
                icon: 'success',
                title: 'Đã xóa nguyên liệu khỏi danh sách!',
                timer: 1000,
                showConfirmButton: false
            });

            const tbody = document.querySelector('table tbody');
            if (tbody.children.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Không còn nguyên liệu!',
                    text: 'Bạn sẽ được chuyển về trang danh sách.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('staffs.shop_materials.index', ['ma_cua_hang' => $ma_cua_hang]) }}";
                });
            }
        });
    });

    // --- Xác nhận trước khi submit ---
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn hủy hàng?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Có, hủy ngay!',
            cancelButtonText: 'Không, quay lại'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    // Hàm lấy input quantity dựa vào mã cửa hàng và mã nguyên liệu
    function getQuantityInput(maCuaHang, maNguyenLieu) {
        return document.querySelector(`input.quantity-input[data-ma_cua_hang="${maCuaHang}"][data-ma_nguyen_lieu="${maNguyenLieu}"]`);
    }

    // Xử lý khi chọn lô (batch)
    document.querySelectorAll('select.batch-select').forEach(select => {
        select.addEventListener('change', function () {
            const maCuaHang = this.dataset.ma_cua_hang;
            const maNguyenLieu = this.dataset.ma_nguyen_lieu;
            const selectedOption = this.options[this.selectedIndex];
            const conLai = selectedOption.dataset.con_lai ? parseFloat(selectedOption.dataset.con_lai) : 0;

            const quantityInput = getQuantityInput(maCuaHang, maNguyenLieu);
            if (quantityInput) {
                quantityInput.max = conLai;
                if (parseFloat(quantityInput.value) > conLai) {
                    quantityInput.value = conLai > 0 ? conLai : '';
                }
            }
        });
    });

    // Xử lý khi nhập số lượng
    document.querySelectorAll('input.quantity-input').forEach(input => {
        input.addEventListener('input', function () {
            const max = parseFloat(this.max) || 0;
            const val = parseFloat(this.value);
            if (val > max) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Số lượng vượt quá số lượng tồn trong lô!',
                    text: `Vui lòng nhập số lượng nhỏ hơn hoặc bằng ${max}.`,
                    timer: 2000,
                    showConfirmButton: false
                });
                this.value = max > 0 ? max : '';
            } else if (val < 0) {
                this.value = '';
            }
        });
    });
});


</script>
@endpush
