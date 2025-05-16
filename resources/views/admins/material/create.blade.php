@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

@section('content')
<div class="page-inner">
    <h3 class="mb-4">{{ $subtitle }}</h3>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admins.material.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Mã nguyên liệu</label>
            <input type="text" name="ma_nguyen_lieu" class="form-control" value="{{ $newCode }}" readonly>
        </div>


        <div class="form-group">
            <label>Tên nguyên liệu</label>
            <input type="text" name="ten_nguyen_lieu" class="form-control" value="{{ old('ten_nguyen_lieu') }}" required>
        </div>

        <div class="form-group">
            <label>Nhà cung cấp</label>
            <select name="ma_nha_cung_cap" class="form-control" >
                <option value="">-- Chọn nhà cung cấp --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->ma_nha_cung_cap }}">{{ $supplier->ten_nha_cung_cap }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Số lượng (kg/ lít)</label>
            <input type="number" name="so_luong" class="form-control" min="0" required>
        </div>

        <div class="form-group">
            <label>Giá</label>
            <input type="number" name="gia" class="form-control" min="0" required>
        </div>

        <div class="form-group">
            <label>Đơn vị</label>
            <select name="don_vi" class="form-control" required>
                <option value="">-- Chọn đơn vị --</option>
                <option value="g/túi">g/túi</option>
                <option value="ly/thùng">ly/thùng</option>
                <option value="ml/hộp">ml/hộp</option>
                <option value="g/hộp">g/hộp</option>
                <option value="g/chai">g/chai</option>
                <option value="ml/chai">ml/chai</option>
                <option value="g/gói">g/gói</option>
            </select>
        </div>

        <div class="form-group">
            <label>Loại</label>
            <select name="loai_nguyen_lieu" class="form-control" required>
                <option value="0">Nguyên liệu</option>
                <option value="1">Vật liệu</option>
            </select>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="trang_thai" class="form-control" required>
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admins.material.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
