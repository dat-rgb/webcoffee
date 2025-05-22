@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)

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
                <li class="nav-supplier">
                    <a href="{{ route('admins.supplier.index') }}">Danh Sách Nhà Cung Cấp</a>
                </li>
            </ul>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Danh sách nhà cung cấp</h4>
                    <a href="{{ route('admins.supplier.create') }}" class="btn btn-primary btn-sm">Thêm nhà cung cấp</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Mã NCC</th> --}}
                                    <th>Tên nhà cung cấp</th>
                                    <th>Địa chỉ</th>
                                    <th>Số điện thoại</th>
                                    <th>Mail</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        {{-- <td>{{ $supplier->ma_nha_cung_cap }}</td> --}}
                                        <td>{{ $supplier->ten_nha_cung_cap }}</td>
                                        <td>{{ $supplier->dia_chi }}</td>
                                        <td>{{ $supplier->so_dien_thoai }}</td>
                                        <td>{{ $supplier->mail ?? 'Không có' }}</td>
                                        <td>
                                            @if($supplier->trang_thai == 1)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- bật tắt hoạt động --}}
                                            <form action="{{ route('admins.supplier.toggleStatus', $supplier->ma_nha_cung_cap) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn {{ $supplier->trang_thai == 1 ? 'tắt' : 'bật' }} hoạt động nhà cung cấp này không?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $supplier->trang_thai == 1 ? 'btn-danger' : 'btn-success' }}">
                                                    <i class="fas {{ $supplier->trang_thai == 1 ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                    {{ $supplier->trang_thai == 1 ? 'Tắt' : 'Bật' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('admins.supplier.edit', $supplier->ma_nha_cung_cap) }}" class="p-0 btn btn-sm me-4">
                                                <i class="fas fa-cog text-warning" title="Chỉnh sửa"></i>
                                            </a>
                                            <form action="{{ route('admins.supplier.archive', $supplier->ma_nha_cung_cap) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="p-0 btn btn-sm supplier-archive-btn" style="border: none; background: none;">
                                                    <i class="fas fa-archive " title="Lưu trữ"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Không có nhà cung cấp nào. <a href="{{ route('admins.supplier.create') }}">Thêm mới</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive -->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('admins/js/alert.js') }}"></script>
@endpush
