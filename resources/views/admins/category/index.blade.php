@extends('layouts.admin')
@section('title', 'Danh sách Danh mục')
@section('content')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}




<div class="container-fluid">
    <h1 class="mb-4 text-gray-800 h3">Danh Sách Danh Mục</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert"></button> --}}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert"></button> --}}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert"></button> --}}
        </div>
    @endif


    <BR></BR>{{-- <a href="{{ route('admins.category.create') }}" class="mb-3 btn btn-primary">+ Thêm danh mục mới</a> --}}
    <div class="mb-3">
        <a href="{{ route('admins.category.index') }}" class="btn btn-secondary {{ request('trang_thai') == null ? 'active' : '' }}">Tất cả</a>
        <a href="{{ route('admins.category.index', ['trang_thai' => 1]) }}" class="btn btn-success {{ request('trang_thai') == 1 ? 'active' : '' }}">Đang hoạt động</a>
        <a href="{{ route('admins.category.index', ['trang_thai' => 2]) }}" class="btn btn-danger {{ request('trang_thai') == 2 ? 'active' : '' }}">Không hoạt động</a>
        <a href="{{ route('admins.category.index', ['trang_thai' => 3]) }}" class="btn btn-warning {{ request('trang_thai') == 3 ? 'active' : '' }}">Thùng rác</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên danh mục</th>
                <th>Danh mục cha</th>
                <th>Mô tả</th>
                <td>Trạng thái</td>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                    <td>{{ $category->ten_danh_muc }}</td>
                    <td>{{ $category->parent ? $category->parent->ten_danh_muc : 'Không có' }}</td>
                    <td>{{ $category->mo_ta }}</td>
                    <td>
                        @if ($category->trang_thai == 1)
                            <span style="color: green;">Hoạt động</span>
                        @elseif ($category->trang_thai == 2)
                            <span style="color: red;">Không hoạt động</span>
                        @elseif ($category->trang_thai == 3)
                            <span style="color: gray;">Lưu trữ</span>
                        @else
                            <span>Không xác định</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap: 5px;">
                            @if ($category->trang_thai == 3)
                                <!-- Khôi phục -->
                                <form action="{{ route('admins.category.restore', $category->ma_danh_muc) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc chắn muốn khôi phục danh mục này?')">Khôi phục</button>
                                </form>

                                <!-- Xóa vĩnh viễn -->
                                <form action="{{ route('admins.category.destroy', ['id' => $category->ma_danh_muc]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?')">Xóa vĩnh viễn</button>
                                </form>
                            @else
                                <!-- Sửa danh mục -->
                                <a href="{{ route('admins.category.edit', ['id' => $category->ma_danh_muc]) }}" class="btn btn-sm btn-warning">Sửa</a>

                                <!-- Lưu trữ -->
                                <form action="{{ route('admins.category.archive', ['id' => $category->ma_danh_muc]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn chuyển danh mục này vào thùng rác?')">Xóa</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Không có danh mục nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3 d-flex justify-content-center">
        {{ $categories->withQueryString()->links() }}
    </div>

</div>
@endsection
