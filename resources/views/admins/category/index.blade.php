@extends('layouts.admin')
@section('title', $title)
@section('subtitle', $subtitle)


@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h3 class="mb-3 fw-bold" style="margin-bottom: 1.5rem;">{{ $subtitle }}</h3>
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
                            <a href="{{ route('admins.category.index') }}">Danh sách danh mục</a>
                        </li>
                    </ul>
                </div>
                <div class="card">

                    {{-- Form tìm kiếm --}}
                    <div class="card-header">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="flex-wrap gap-2 d-flex align-items-center">
                                    <div class="col-12 col-lg-3">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                name="search"
                                                class="form-control"
                                                placeholder="Tìm kiếm..."
                                                value="{{ request('search') }}"
                                                autocomplete="off"
                                            >
                                            <button type="submit" class="bg-white input-group-text">
                                                <i class="fa fa-search text-muted"></i>
                                            </button>
                                        </div>
                                    </div>
                                <div class="col-12 col-lg-3">
                                    <select name="trang_thai" class="form-select" onchange="this.form.submit()">
                                        <option value="" {{ request('trang_thai') === null ? 'selected' : '' }}>Tất cả trạng thái</option>
                                        <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="2" {{ request('trang_thai') == '2' ? 'selected' : '' }}>Không hoạt động</option>
                                        <option value="3" {{ request('trang_thai') == '3' ? 'selected' : '' }}>Xóa tạm</option>
                                    </select>
                                </div>
                                {{-- THAO TÁC --}}
                                <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Thao tác
                                </button>
                                <ul class="dropdown-menu">
                                    @if(request('trang_thai') == 3)
                                    <li>
                                        <button
                                        type="button"
                                        class="dropdown-item btn-bulk-restore"
                                        data-action="{{ route('admins.category.bulk-restore') }}"
                                        >Khôi phục</button>
                                    </li>
                                    <li>
                                        <button
                                        type="button"
                                        class="dropdown-item text-danger btn-bulk-delete"
                                        data-action="{{ route('admins.category.bulk-delete') }}"
                                        >Xóa vĩnh viễn</button>
                                    </li>
                                    @else
                                    <li>
                                        <button
                                        type="button"
                                        class="dropdown-item btn-bulk-archive"
                                        data-action="{{ route('admins.category.bulk-archive') }}"
                                        >Tạm xóa</button>
                                    </li>
                                    @endif
                                </ul>
                                </div>
                                {{-- THÊM MỚI --}}
                                <div class="col-6 col-lg-2">
                                    <a href="{{ route('admins.category.create') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-plus"></i> Thêm mới
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>

                    {{-- Form thao tác bulk và bảng danh sách --}}
                    @if($categories->isEmpty())
                        <div class="py-5 my-5 text-center">
                            <i class="mb-3 fa fa-folder-open fa-3x text-muted"></i>
                            <h5 class="text-muted">Không có danh mục nào trong danh sách</h5>
                            <p>Hãy thêm danh mục mới để bắt đầu quản lý sản phẩm.</p>
                            <a href="{{ route('admins.category.create') }}" class="mt-3 btn btn-primary">
                                <i class="fa fa-plus"></i> Thêm danh mục mới
                            </a>
                        </div>
                    @else
                        <form id="bulk-action-form" method="POST" >
                            @csrf
                            <div class="card-body">
                                {{-- <div class="mb-3 row">
                                    <div class="col-6 col-lg-2">
                                        <div class="dropdown w-100">
                                            <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                                Thao tác
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(request('trang_thai') == 3)
                                                    <li>
                                                        <button type="submit" formaction="{{ route('admins.category.bulk-restore') }}" class="dropdown-item btn-bulk-restore">
                                                            Khôi phục các danh mục đã chọn
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="submit" formaction="{{ route('admins.category.bulk-delete') }}" class="dropdown-item text-danger btn-bulk-delete">
                                                            Xóa các danh mục đã chọn
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="submit" formaction="{{ route('admins.category.bulk-archive') }}" class="dropdown-item btn-bulk-archive">
                                                            Tạm xóa danh mục đã chọn
                                                        </button>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="table-responsive">
                                    <table class="table display table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><label style="display: inline-block; width: 100%; height: 100%;">
                                                    <input type="checkbox" id="checkAll" style="pointer-events: none;">
                                                </label></th>
                                                {{-- <th>#</th> --}}
                                                <th>Tên danh mục</th>
                                                <th>Danh mục cha</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $index => $cat)
                                                <tr>
                                                    <td><input type="checkbox" name="selected_ids[]" value="{{ $cat->ma_danh_muc }}"></td>
                                                    {{-- <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td> --}}
                                                    <td>{{ $cat->ten_danh_muc }}</td>
                                                    <td>{{ $cat->parent ? $cat->parent->ten_danh_muc : 'Không có' }}</td>
                                                    <td>
                                                        @if ($cat->trang_thai == 1)
                                                            <span class="badge badge-success">Hoạt động</span>
                                                        @elseif ($cat->trang_thai == 2)
                                                            <span class="badge badge-danger">Không hoạt động</span>
                                                        @elseif ($cat->trang_thai == 3)
                                                            <span class="badge badge-warning"> ẩn</span>
                                                        @else
                                                            <span class="badge badge-light">Không xác định</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="form-button-action">
                                                            <a href="{{ route('admins.category.edit', $cat->ma_danh_muc) }}" class="btn btn-icon btn-round btn-info" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center t-3">
                                    {{ $categories->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
  const form       = $('#bulk-action-form');
  const checkAll   = $('#checkAll');
  const checkboxes = $('input[name="selected_ids[]"]');

  // Check all
  checkAll.on('change', () => {
    checkboxes.prop('checked', checkAll.prop('checked'));
  });
  checkboxes.on('change', () => {
    checkAll.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);
  });

  // Toggle khi click dòng
  $('table tbody tr').on('click', function(e){
    if ($(e.target).is('a, button, input[type=checkbox]')) return;
    const cb = $(this).find('input[type=checkbox]');
    cb.prop('checked', !cb.prop('checked'));
    checkAll.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);
  });

  // Bulk action
  $('.btn-bulk-archive, .btn-bulk-restore, .btn-bulk-delete').on('click', function(e){
    e.preventDefault();
    const btn       = $(this);
    const actionUrl = btn.data('action');
    const total     = checkboxes.filter(':checked').length;

    if (!total) {
      return Swal.fire('Chưa chọn mục nào', 'Vui lòng chọn ít nhất một danh mục.', 'info');
    }

    // cấu hình confirm
    const cfg = {
      'btn-bulk-archive': { title:'Xác nhận tạm xóa?',    text:'Danh mục sẽ bị tạm xóa.',           icon:'warning',  btn:'btn-warning' },
      'btn-bulk-restore': { title:'Xác nhận khôi phục?',   text:'Danh mục sẽ hiển thị lại.',     icon:'question', btn:'btn-success' },
      'btn-bulk-delete':  { title:'Xác nhận xóa vĩnh viễn?', text:'Không thể phục hồi.',         icon:'error',    btn:'btn-danger'  }
    };
    const key = btn.hasClass('btn-bulk-archive') ? 'btn-bulk-archive'
              : btn.hasClass('btn-bulk-restore') ? 'btn-bulk-restore'
              : 'btn-bulk-delete';
    const a = cfg[key];

    Swal.fire({
      title: a.title,
      text: a.text,
      icon: a.icon,
      showCancelButton: true,
      confirmButtonText: 'Xác nhận',
      cancelButtonText:  'Hủy',
      customClass: {
        confirmButton: `btn ${a.btn} me-2`,
        cancelButton:  'btn btn-secondary'
      },
      buttonsStyling: false
    }).then(res => {
      if (res.isConfirmed) {
        form.attr('action', actionUrl).submit();
      }
    });
  });
});
</script>
@endpush







