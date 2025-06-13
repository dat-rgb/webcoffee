@extends('layouts.admin')

@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
    th {
        white-space: nowrap;
        font-size: 14px;
        padding: 8px 10px;
        text-align: center;
    }
</style>
@endpush

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="mb-3 fw-bold">{{ $subtitle }}</h3>
            <ul class="mb-3 breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.contact.list') }}">contact</a>
                </li>
                @if(request()->routeIs('admin.products.hidden.list'))
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="">contact đã ẩn</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="row g-2 align-items-center">
                                {{-- Tìm kiếm --}}
                                <div class="col-12 col-lg-4">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control"
                                            placeholder="Nhập tên hoặc mã contact để tìm kiếm..."
                                            value="{{ request('search') }}"
                                            autocomplete="off"
                                        >
                                        <button type="submit" class="bg-white input-group-text">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                    </div>
                                </div>
                                {{-- Thao tác nhanh --}}
                                <div class="col-6 col-lg-2">
                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(request()->routeIs('admin.products.hidden.list'))
                                                <li><button type="button" class="dropdown-item" id="show-products">Hiển thị các contact đã chọn</button></li>
                                            @else
                                                <li><button type="button" class="dropdown-item" id="hide-products">Ẩn các contact đã chọn</button></li>
                                            @endif
                                            <li><button type="button" class="dropdown-item text-danger" id="delete-products">Xóa các contact đã chọn</button></li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Bộ lọc --}}
                                <div class="col-6 col-lg-2">
                                    @if(request()->routeIs('admin.products.hidden.list'))
                                        <a href="" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-eye-fill me-1"></i> contact hiển thị
                                        </a>
                                    @else
                                        <a href="" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-eye-slash-fill me-1"></i> contact ẩn
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    @if($contacts->isEmpty())
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-box-open fa-3x text-muted"></i>
                            <h5 class="text-muted">Không có liên hệ nào trong danh sách</h5>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="add-row" class="table table-striped table-hover align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" style="width: 40px;"><input type="checkbox" id="checkAll"></th>
                                                            <th style="width: 300px;">Tiêu đề</th>
                                                            <th>Thông tin khách hàng</th>
                                                            <th>Trạng thái</th>
                                                            <th>Ngày gửi</th>
                                                            <th>Nội dung</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($contacts as $contact)
                                                            <tr class="product-row">
                                                                <td class="text-center">
                                                                    <input type="checkbox" class="product-checkbox" value="{{ $contact->id }}">
                                                                </td>
                                                                <td>
                                                                    <span title="{{ $contact->tieu_de }}">
                                                                        {{ \Illuminate\Support\Str::limit($contact->tieu_de, 60) }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-start align-middle" style="min-width: 200px;">
                                                                    <div style="font-weight: 600; color: #333;">
                                                                        {{ $contact->ma_khach_hang ? 'Mã KH: ' . $contact->ma_khach_hang : 'Guest' }}
                                                                    </div>
                                                                    <div style="font-size: 14px; color: #222; margin-top: 2px;">
                                                                        {{ $contact->ho_ten }}
                                                                    </div>
                                                                    <div style="font-size: 13px; color: #555; margin-top: 1px;">
                                                                        {{ $contact->so_dien_thoai }}
                                                                    </div>
                                                                    <div style="font-size: 13px; color: #555; margin-top: 1px;">
                                                                        {{ $contact->email }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if ($contact->trang_thai == 1)
                                                                        <span class="badge bg-success">Đã xử lý</span>
                                                                    @elseif ($contact->trang_thai == 0)
                                                                        <span class="badge bg-danger">Chưa xử lý</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Không xác định</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($contact->ngay_gui)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    {!! \Illuminate\Support\Str::limit(strip_tags($contact->noi_dung), 100, '...') !!}
                                                                </td>
                                                                <td class="text-center">
                                                                    <!-- Nút mở modal -->
                                                                    <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#replyModal-{{ $contact->id }}">
                                                                        <i class="fas fa-envelope"></i> Email
                                                                    </button>

                                                                    <a href="tel:{{ $contact->so_dien_thoai }}" class="btn btn-sm btn-success">
                                                                        <i class="fas fa-phone-alt"></i> Gọi
                                                                    </a>
                                                                </td>
                                                                <div class="modal fade" id="replyModal-{{ $contact->id }}" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <form action="{{ route('admin.contact.reply') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="email" value="{{ $contact->email }}">
                                                                            <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                <h5 class="modal-title">Phản hồi đến {{ $contact->email }}</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="subject" class="form-label">Chủ đề</label>
                                                                                    <input type="text" class="form-control" name="subject" required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="message" class="form-label">Nội dung</label>
                                                                                    <textarea class="form-control" name="message" rows="5" required></textarea>
                                                                                </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                                <button type="submit" class="btn btn-primary">Gửi</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                                <ul class="pagination">
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end dataTables_wrapper -->
                            </div> <!-- end table-responsive -->
                        </div> <!-- end card-body -->
                    @endif
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
