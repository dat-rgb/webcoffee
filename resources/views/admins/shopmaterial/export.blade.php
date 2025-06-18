@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)
@section('content')
<div class="page-inner">
    @if ($materials->isEmpty())
            <div class="alert alert-warning">
                Không có nguyên liệu nào để xuất.
        </div>
    @else
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admins.shopmaterial.export') }}" method="POST">
            @csrf
            <div class="card">
                <div class="shadow-sm card rounded-4">
                    <div class="card-header bg-light rounded-top-4">
                        @php
                            $firstMaterial = $materials->first();
                        @endphp
                        @if (optional($firstMaterial->cuaHang)->ten_cua_hang)
                            <h3 class="fw-bold">
                                Xuất nguyên liệu khỏi kho cửa hàng {{ $firstMaterial->cuaHang->ten_cua_hang }}
                            </h3>
                            <h5>
                                Ngày xuất: {{ $today }}
                            </h5>
                            <h5>
                                Số lô: {{ $soLo }}
                            </h5>
                            <input type="hidden" name="soLo" value="{{ $soLo }}">
                        @else
                            <h3 class="fw-bold">
                                Xuất nguyên liệu khỏi kho (Không xác định)
                            </h3>
                            <h5>
                                Ngày hiện tại: {{ $today }}
                            </h5>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive rounded-3">
                            <table class="table text-center align-middle table-bordered table-hover" style="white-space: nowrap;">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="px-2 py-2">Mã nguyên liệu</th>
                                        <th class="px-2 py-2">Tên nguyên liệu</th>
                                        <th class="px-2 py-2">Định lượng</th>
                                        <th class="px-2 py-2">Số lượng tồn</th>
                                        <th class="px-2 py-2">Số lượng tối thiểu</th>
                                        <th>Số lô còn hàng và số lượng hàng của lô đó</th>
                                        <th class="px-2 py-2" style="white-space: nowrap;">
                                            Số lượng xuất <br>
                                            <small>(kg, lít, gói, túi, thùng)</small>
                                        </th>
                                        {{-- <th class="px-2 py-2">NSX</th> --}}
                                        {{-- <th class="px-2 py-2">HSD(của lô hàng xa nhất)</th> --}}
                                        <th class="px-2 py-2" style="width: 200px;">Ghi chú</th>
                                        <th class="px-2 py-2">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $material)
                                    <tr>
                                        <td class="px-2 py-2">{{ $material->nguyenLieu->ma_nguyen_lieu }}</td>
                                        <td class="px-2 py-2">{{ $material->nguyenLieu->ten_nguyen_lieu }}</td>
                                        <td class="px-2 py-2">{{ $material->nguyenLieu->so_luong .' '. $material->nguyenLieu->don_vi }}</td>
                                        <td class="px-2 py-2">{{ $material->so_luong_ton .' '. $material->don_vi }}</td>
                                        <td class="px-2 py-2">{{ $material->so_luong_ton_min .' '. $material->don_vi }}</td>
                                        <td class="px-2 py-2 text-start">
                                            @if(!empty($material->available_batches))
                                                <ul class="mb-0">
                                                    @php
                                                        $hasBatch = false;
                                                    @endphp
                                                    @foreach ($material->available_batches as $lo)
                                                        @if ($lo['con_lai'] > 0)
                                                            @php $hasBatch = true; @endphp
                                                            <li>
                                                                Lô: <strong>{{ $lo['so_lo'] }}</strong> -
                                                                Còn lại: <strong>{{ $lo['con_lai'] }} {{ $material->nguyenLieu->don_vi }}</strong> -
                                                                HSD: <strong>{{ \Carbon\Carbon::parse($lo['han_su_dung'])->format('d/m/Y') }}</strong>
                                                            </li>
                                                        @endif
                                                    @endforeach

                                                    @if (!$hasBatch)
                                                        <li><span class="text-danger">Không còn lô hàng</span></li>
                                                    @endif
                                                </ul>

                                            @else
                                                <span class="text-danger">Không còn lô hàng</span>
                                            @endif
                                        </td>

                                        <td class="px-2 py-2">
                                            @php
                                                $maxExport = max(0, $material->so_luong_ton - $material->so_luong_ton_min);
                                            @endphp
                                            @if ($maxExport == 0)
                                                <input type="hidden" name="export[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]" value="0">
                                            @endif
                                            <input
                                                type="number"
                                                name="export[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]"
                                                class="form-control form-control-sm"
                                                step="any"
                                                min="0.01"
                                                max="{{ $maxExport }}"
                                                {{ $maxExport == 0 ? 'disabled' : '' }}
                                            >
                                        </td>

                                        <td class="px-2 py-2" style="width: 200px;">
                                            <input type="text" name="note[{{ $material->ma_cua_hang }}][{{ $material->ma_nguyen_lieu }}]" class="form-control form-control-sm" placeholder="nhập...">
                                        </td>
                                        <td class="px-2 py-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row">Xóa</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-danger">Xác nhận xuất hàng</button>
                        <a href="{{ route('admins.shopmaterial.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('admins/js/alert.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-row').forEach(button => {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                row.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Đã xóa nguyên liệu!',
                    showConfirmButton: false,
                    timer: 1000
                });
                const tbody = document.querySelector('table tbody');
                if (tbody.children.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Không còn nguyên liệu!',
                        text: 'Bạn sẽ được chuyển về trang danh sách.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('admins.shopmaterial.index') }}";
                    });
                }
            });
        });
    });
</script>
@endpush



