@extends('layouts.admin')
@section('title',$title)
@section('subtitle',$subtitle)
@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
        <ul class="mb-3 breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Thêm nguyên liệu vào kho cửa hàng</a>
            </li>
        </ul>
    </div>
    <div class="mb-4 page-header d-flex justify-content-between align-items-center">
        <h4 class="page-title"> <strong>{{ $ten_cua_hang }}</strong></h4>
        <a href="{{ route('admins.shopmaterial.index', ['ma_cua_hang' => request('ma_cua_hang')]) }}" class="btn btn-outline-secondary">
            ← Quay lại
        </a>
    </div>

    <div class="border-0 shadow-sm card rounded-4">
        <div class="p-4 card-body">

            {{-- Hiển thị lỗi nếu có --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form thêm nguyên liệu --}}
            <form id="form-them-nguyen-lieu" action="{{ route('admins.shopmaterial.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ma_cua_hang" value="{{ request('ma_cua_hang') }}">

                <div class="mb-4">
                    <label for="ma_nguyen_lieu" class="form-label fw-semibold">Chọn nguyên liệu:</label>
                    <select name="ma_nguyen_lieu" id="ma_nguyen_lieu" class="form-select" required>
                        <option value="" disabled selected>-- Chọn nguyên liệu --</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->ma_nguyen_lieu }}" {{ old('ma_nguyen_lieu') == $material->ma_nguyen_lieu ? 'selected' : '' }}>
                                {{ $material->ten_nguyen_lieu }} ({{ $material->don_vi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="so_luong_ton_min" class="form-label fw-semibold">Số lượng tồn tối thiểu:</label>
                        <input type="number" name="so_luong_ton_min" class="form-control" min="0" placeholder="Nhập số lượng min..." required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="so_luong_ton_max" class="form-label fw-semibold">Số lượng tối đa trong kho:</label>
                        <input type="number" name="so_luong_ton_max" class="form-control" min="0" placeholder="Nhập số lượng max..." required>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" id="btn-luu-nguyen-lieu" class="px-4 btn btn-primary">
                        Lưu nguyên liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-luu-nguyen-lieu').addEventListener('click', function () {
        Swal.fire({
            title: 'Xác nhận',
            text: 'Bạn có muốn thêm nguyên liệu này vào cửa hàng không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Có, thêm vào!',
            cancelButtonText: 'Hủy',
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-them-nguyen-lieu').submit();
            }
        });
    });
</script>
@endpush
