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
                <a href="{{ route('admin.contact.list') }}">Liên hệ</a>
            </li>

            @if(request('trang_Thai') == 1 || request('trang_Thai') == 0)
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <span>
                        {{ request('trang_Thai') == 1 ? 'Liên hệ đã xử lý' : 'Liên hệ chưa xử lý' }}
                    </span>
                </li>
            @endif
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('admin.contact.list') }}" method="GET">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-lg-4">
                                <div class="input-group">
                                    <input  type="text" name="search" class="form-control"
                                            placeholder="Tìm tên, email hoặc tiêu đề..."
                                            value="{{ request('search') }}">
                                    <button class="bg-white input-group-text">
                                        <i class="fa fa-search text-muted"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-lg-2">
                                <div class="dropdown w-100">
                                    <button class="btn btn-outline-primary dropdown-toggle w-100" data-bs-toggle="dropdown">
                                        Sắp xếp
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item {{ $sortBy == 'latest' ? 'active' : '' }}"
                                            href="{{ request()->fullUrlWithQuery(['sort_by' => 'latest', 'page' => 1]) }}">
                                                Liên hệ mới nhất
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ $sortBy == 'oldest' ? 'active' : '' }}"
                                            href="{{ request()->fullUrlWithQuery(['sort_by' => 'oldest', 'page' => 1]) }}">
                                                Liên hệ cũ nhất
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-6 col-lg-2">
                                <div class="dropdown w-100">
                                    <button class="btn btn-outline-primary dropdown-toggle w-100" data-bs-toggle="dropdown">
                                        Thao tác
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($status == 1)
                                            <li><button type="button" class="dropdown-item" id="bulk-unread">Đánh dấu chưa xử lý</button></li>
                                        @else
                                            <li><button type="button" class="dropdown-item" id="bulk-read">Đánh dấu đã xử lý</button></li>
                                        @endif
                                        <li><button type="button" class="dropdown-item text-danger" id="bulk-delete">Xoá liên hệ đã chọn</button></li>
                                    </ul>

                                </div>
                            </div>

                            <div class="col-6 col-lg-2">
                                @if($status == 1)
                                    <a href="{{ route('admin.contact.list', array_merge(request()->except('page'), ['trang_Thai'=>0])) }}"
                                    class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-inbox me-1"></i> Liên hệ chưa xử lý
                                    </a>
                                @else
                                    <a href="{{ route('admin.contact.list', array_merge(request()->except('page'), ['trang_Thai'=>1])) }}"
                                    class="btn btn-outline-success w-100">
                                        <i class="bi bi-check2-circle me-1"></i> Liên đã xử lý
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                @if($contacts->isEmpty())
                    <div class="py-5 my-5 text-center">
                        <i class="mb-3 fa fa-box-open fa-3x text-muted"></i>
                        @if(request('search'))
                            <h5 class="text-muted">
                                Không tìm thấy kết quả nào với từ khóa <strong>"{{ request('search') }}"</strong>
                            </h5>
                        @elseif(request('trang_Thai') == 1)
                            <h5 class="text-muted">Không có liên hệ nào đã xử lý</h5>
                        @elseif(request('trang_Thai') == 0)
                            <h5 class="text-muted">Không có liên hệ nào chưa xử lý</h5>
                        @else
                            <h5 class="text-muted">Không có liên hệ nào trong danh sách</h5>
                        @endif
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
                                                        <th>Thao tác</th>
                                                        <th>Thông tin khách hàng</th>
                                                        <th>Trạng thái</th>
                                                        <th>Ngày gửi</th>
                                                        <th>Nội dung</th>
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
                                                                    {{ \Illuminate\Support\Str::limit($contact->tieu_de, 50) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-start align-middle" style="min-width: 200px;">
                                                                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                                                    <!-- Chi tiết -->
                                                                    <button type="button"
                                                                        class="btn btn-icon btn-round btn-info text-white"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#detailModal-{{ $contact->id }}"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Xem chi tiết">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>

                                                                    <!-- Email -->
                                                                    <button type="button"
                                                                        class="btn btn-icon btn-round btn-primary"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#replyModal-{{ $contact->id }}"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Gửi Email">
                                                                        <i class="fas fa-envelope"></i>
                                                                    </button>

                                                                    <!-- Gọi -->
                                                                    <a href="tel:{{ $contact->so_dien_thoai }}"
                                                                        class="btn btn-icon btn-round btn-success"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Gọi ngay">
                                                                        <i class="fas fa-phone"></i>
                                                                    </a>
                                                                </div>
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
                                                            <td class="text-start align-middle" style="min-width: 300px;">
                                                                {!! \Illuminate\Support\Str::limit(strip_tags($contact->noi_dung), 50, '...') !!}
                                                            </td>
                                                            <!-- Modal -->
                                                            <!-- Modal Chi tiết -->
                                                            <div class="modal fade" id="detailModal-{{ $contact->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Chi tiết liên hệ từ {{ $contact->ho_ten }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p><strong>Họ tên:</strong> {{ $contact->ho_ten }}</p>
                                                                            <p><strong>Email:</strong> {{ $contact->email }}</p>
                                                                            <p><strong>Số điện thoại:</strong> {{ $contact->so_dien_thoai }}</p>
                                                                            <p><strong>Tiêu đề:</strong> {{ $contact->tieu_de }}</p>
                                                                            <p><strong>Nội dung:</strong></p>
                                                                            <div class="border p-2" style="white-space: pre-wrap;">
                                                                                {{ $contact->noi_dung }}
                                                                            </div>
                                                                            <p class="mt-3"><strong>Ngày gửi:</strong> {{ \Carbon\Carbon::parse($contact->ngay_gui)->format('d/m/Y H:i') }}</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--  -->
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
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info">
                                            Hiển thị {{ $contacts->firstItem() }} đến {{ $contacts->lastItem() }} của {{ $contacts->total() }} liên hệ
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            <ul class="pagination justify-content-end mb-0">
                                                {{-- Previous Page --}}
                                                <li class="paginate_button page-item {{ $contacts->onFirstPage() ? 'disabled' : '' }}">
                                                    <a href="{{ $contacts->previousPageUrl() ?? '#' }}" class="page-link">Trước</a>
                                                </li>

                                                {{-- Page Numbers --}}
                                                @for ($i = 1; $i <= $contacts->lastPage(); $i++)
                                                    <li class="paginate_button page-item {{ $contacts->currentPage() == $i ? 'active' : '' }}">
                                                        <a href="{{ $contacts->url($i) }}" class="page-link">{{ $i }}</a>
                                                    </li>
                                                @endfor

                                                {{-- Next Page --}}
                                                <li class="paginate_button page-item {{ !$contacts->hasMorePages() ? 'disabled' : '' }}">
                                                    <a href="{{ $contacts->nextPageUrl() ?? '#' }}" class="page-link">Kế tiếp</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                @endif
            </div> 
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    const csrf = '{{ csrf_token() }}';

    document.getElementById('checkAll').addEventListener('change', function(){
        document.querySelectorAll('.product-checkbox')
            .forEach(cb => cb.checked = this.checked);
    });

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.product-checkbox:checked'))
                    .map(cb => cb.value);
    }

    async function handleBulk(url, method = 'POST', successMsg = 'Hoàn tất!') {
        const ids = getSelectedIds();
        if (!ids.length) {
            return Swal.fire('Nhắc nhỡ', 'Hãy chọn ít nhất 1 liên hệ.', 'warning');
        }

        const confirm = await Swal.fire({
            title: 'Bạn chắc chứ?',
            text: `Đang áp dụng cho ${ids.length} liên hệ`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Huỷ'
        });
        if (!confirm.isConfirmed) return;

        Swal.showLoading();
        try {
            const res = await fetch(url, {
                method,
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf},
                body: JSON.stringify({ids})
            });
            const json = await res.json();
            if (json.ok) {
                Swal.fire('Thành công', successMsg, 'success')
                    .then(()=>location.reload());
            } else throw Error();
        } catch {
            Swal.fire('Lỗi', 'Có lỗi xảy ra, thử lại!', 'error');
        }
    }

    // buttons
    document.getElementById('bulk-read')?.addEventListener('click', () =>
        handleBulk('{{ route('admin.contact.bulk.read') }}', 'POST', 'Đã đánh dấu đã xử lý'));
    document.getElementById('bulk-unread')?.addEventListener('click', () =>
        handleBulk('{{ route('admin.contact.bulk.unread') }}', 'POST', 'Đã đánh dấu chưa xử lý'));
    document.getElementById('bulk-delete')?.addEventListener('click', () =>
        handleBulk('{{ route('admin.contact.bulk.delete') }}', 'DELETE', 'Đã xoá liên hệ'));
</script>
@endpush

