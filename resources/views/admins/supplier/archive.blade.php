@extends('layouts.admin')
@section('title', $title)
@section('subtitle',$subtitle)

@section('content')
    <div class="container">
        <h1 class="mb-4">{{ $title ?? 'Danh sách nhà cung cấp đã lưu trữ' }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if($suppliers->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Địa chỉ</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->ma_nha_cung_cap }}</td>
                            <td>{{ $supplier->ten_nha_cung_cap }}</td>
                            <td>{{ $supplier->dia_chi }}</td>
                            <td>{{ $supplier->so_dien_thoai }}</td>
                            <td>{{ $supplier->mail }}</td>
                            <td>
                                <form action="{{ route('admins.supplier.restore', $supplier->ma_nha_cung_cap) }}" method="POST" onsubmit="return confirm('Khôi phục nhà cung cấp này?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Khôi phục</button>
                                </form>
                                <form action="{{ route('admins.supplier.destroy', $supplier->ma_nha_cung_cap) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa nhà cung cấp này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">Không có nhà cung cấp nào đã lưu trữ.</div>
        @endif

        <a href="{{ route('admins.supplier.index') }}" class="mt-3 btn btn-secondary">Quay lại danh sách</a>
    </div>
@endsection


