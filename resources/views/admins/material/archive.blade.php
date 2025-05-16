@extends('layouts.admin') {{-- hoặc layout của bạn --}}

@section('content')
    <h2>Danh sách nguyên liệu đã lưu trữ</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tên</th>
                <th>Giá(VNĐ)</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
                <tr>
                    <td>{{ $material->ma_nguyen_lieu }}</td>
                    <td>{{ $material->ten_nguyen_lieu }}</td>
                    <td>{{ number_format($material->gia) }}</td>
                    <td>
                        <span class="badge bg-secondary">Đã lưu trữ</span>
                    </td>
                    <td style="min-width: 120px;">
                        <div class="d-flex align-items-center justify-content-start">
                            {{-- Nút khôi phục --}}
                        <form action="{{ route('admins.material.restore', $material->id) }}" method="POST" onsubmit="return confirm('Khôi phục nguyên liệu này?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success me-2">
                                <i class="fas fa-undo"></i>
                            </button>
                        </form>
                        {{-- Nút xóa --}}
                        <form action="{{ route('admins.material.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                        </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
