@extends('layouts.staff')

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
            <h3 class="fw-bold mb-3">{{ $subtitle }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('staff') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('staff.orders.list') }}">Danh sách đơn hàng</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                        <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                            <div class="col-12 col-lg-6"> 
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        id="searchInput"
                                        name="search" 
                                        class="form-control" 
                                        placeholder="Nhập mã đơn hàng hoặc tên khách hàng..." 
                                        autocomplete="off"
                                    >
                                     <button type="button" class="btn btn-outline-secondary" id="searchBtn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if($orders->isEmpty())
                        <div class="text-center my-5 py-5">
                            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có đơn hàng trong danh sách</h5>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Mã HĐ</th>
                                            <th>Ngày lập HĐ</th>
                                            <th>Thông tin khách hàng</th>
                                            <th>
                                                Phương thức thanh toán<br>
                                                <select id="pt_thanh_toan" class="form-select form-select-sm mt-1">
                                                    <option value="">Tất cả</option>
                                                    <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                                                    <option value="NAPAS247">Chuyển khoản</option>
                                                </select>
                                            </th>
                                            <th>
                                                Trạng thái đơn hàng<br>
                                                <select id="trang_thai" class="form-select form-select-sm mt-1">
                                                    <option value="">Tất cả</option>
                                                    <option value="0">Chờ xác nhận</option>
                                                    <option value="1">Đã xác nhận</option>
                                                    <option value="2">Hoàn tất đơn hàng</option>
                                                    <option value="3">Đang giao/Chờ nhận hàng</option>
                                                    <option value="4">Đã nhận</option>
                                                    <option value="5">Đã hủy</option>
                                                </select>
                                            </th>
                                            <th>
                                                Trạng thái thanh toán<br>
                                                <select id="trang_thai" class="form-select form-select-sm mt-1">
                                                    <option value="">Tất cả</option>
                                                    <option value="0">Chưa thanh toán</option>
                                                    <option value="1">Đã thanh toán</option>
                                                    <option value="2">Đang hoàn tiền</option>
                                                    <option value="3">Đã hoàn tiền</option>
                                                </select>
                                            </th>
                                        </tr>
                                    </thead>    
                                    <tbody id="order-tbody">
                                        @include('staffs.orders._order_tbody', ['orders' => $orders])
                                        <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true"> 
                                            <div class="modal-dialog modal-lg"> 
                                                <div class="modal-content">
                                                    <div id="order-detail-content"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.order-status-select').forEach(select => {
  // Lấy giá trị trạng thái trước để rollback khi cần
  let previousValue = parseInt(select.getAttribute('data-previous') || select.value);

  select.addEventListener('change', function () {
    const orderId = this.dataset.orderId;
    const newStatus = parseInt(this.value);
    const pt_nhan_hang = this.dataset.ptNhanHang;

    // Check trạng thái nếu không phải hủy đơn (5)
    if (newStatus !== 5 && newStatus - previousValue !== 1) {
      Swal.fire({
        icon: 'warning',
        title: 'Không hợp lệ',
        text: 'Không được phép bỏ qua trạng thái. Vui lòng chọn theo thứ tự.',
      });
      this.value = previousValue;
      return;
    }

    Swal.fire({
      title: 'Xác nhận thay đổi trạng thái?',
      text: 'Bạn có chắc muốn cập nhật trạng thái đơn hàng này không?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Xác nhận',
      cancelButtonText: 'Hủy',
    }).then(result => {
      if (result.isConfirmed) {
        if (newStatus === 3) {
          if (pt_nhan_hang === 'pickup') {
            updateOrderStatus(orderId, newStatus, {}, this);
          } else {
            showDeliverInfoModal(orderId, newStatus, this);
          }
        } else if (newStatus === 5) {
          showCancelReasonModal(orderId, newStatus, this);
        } else {
          updateOrderStatus(orderId, newStatus, {}, this);
        }
      } else {
        this.value = previousValue;
      }
    });
  });
});

function showDeliverInfoModal(orderId, newStatus, selectElement) {
    Swal.fire({
        title: 'Nhập thông tin giao hàng',
        html: `
            <p><strong>Mã đơn hàng:</strong> ${orderId}</p>
            <input type="text" id="shipperName" class="swal2-input" placeholder="Họ tên shipper">
            <input type="text" id="shipperPhone" class="swal2-input" placeholder="SĐT shipper">
            <textarea id="note" class="swal2-textarea" placeholder="Ghi chú (nếu có)"></textarea>
        `,
        confirmButtonText: 'Xác nhận',
        focusConfirm: false,
        preConfirm: () => {
            const name = document.getElementById('shipperName').value.trim();
            const phone = document.getElementById('shipperPhone').value.trim();
            const note = document.getElementById('note').value.trim();

            if (!name || !phone) {
                Swal.showValidationMessage(`Vui lòng nhập đầy đủ thông tin`);
                return false;  // dừng submit
            }
            if (name.length < 2) {
                Swal.showValidationMessage(`Tên ít nhất 2 ký tự`);
                return false;
            }
            if (name.length > 255){
                Swal.showValidationMessage(`Tên không vượt quá 255 ký tự`);
                return false;
            }

            const phoneRegex = /^0\d{9}$/;  // số 0 + 9 số còn lại = 10 số
            if (!phoneRegex.test(phone)) {
                Swal.showValidationMessage(`Số điện thoại phải đủ 10 số và bắt đầu bằng số 0`);
                return false;
            }

            if ( note.length > 255){
                Swal.showValidationMessage(`Ghi chú không vượt quá 255 ký tự`);
                return false;
            }

            return { name, phone, note };
        }

    }).then((result) => {
        if (result.isConfirmed) {
            updateOrderStatus(orderId, newStatus, {
                shipper_name: result.value.name,
                shipper_phone: result.value.phone,
                note: result.value.note,
            }, selectElement);
        } else {
            // reset select về giá trị cũ khi huỷ modal
            if (selectElement) selectElement.value = selectElement.getAttribute('data-previous');
        }
    });
}

function showCancelReasonModal(orderId, newStatus, selectElement) {
    Swal.fire({
        title: 'Lý do hủy đơn hàng',
        html: `
            <p><strong>Mã đơn hàng:</strong> ${orderId}</p>
            <textarea id="cancelReason" class="swal2-textarea" placeholder="Nhập lý do hủy đơn hàng"></textarea>
        `,
        confirmButtonText: 'Xác nhận',
        focusConfirm: false,
        preConfirm: () => {
            const reason = document.getElementById('cancelReason').value.trim();
            if (!reason) {
                Swal.showValidationMessage(`Vui lòng nhập lý do hủy`);
            }
            return { reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateOrderStatus(orderId, newStatus, {
                cancel_reason: result.value.reason,
            }, selectElement);
        } else {
            if (selectElement) selectElement.value = selectElement.getAttribute('data-previous');
        }
    });
}

function updateOrderStatus(orderId, status, extraData = {}, selectElement = null) {
    Swal.fire({
        title: 'Đang cập nhật...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    fetch('/staff/orders/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            order_id: orderId,
            status: status,
            ...extraData,
        }),
    })
    .then(async res => {
        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(errorText);
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: 'Cập nhật trạng thái đơn hàng thành công!',
            }).then(() => window.location.reload());
        } else {
            if (selectElement) selectElement.value = selectElement.getAttribute('data-previous');
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: data.message || 'Cập nhật thất bại!',
            });
        }
    })
    .catch(async (err) => {
        const errorText = await err?.response?.text?.() ?? err.message;
        console.error('Lỗi chi tiết:', errorText);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi Server',
            html: `<pre>${errorText}</pre>`,
        });
    })
    .finally(() => {
        if (selectElement) {
            // Cập nhật data-previous chỉ khi thành công (đã reload trang rồi nên cũng ko ảnh hưởng nhiều)
            selectElement.setAttribute('data-previous', status);
        }
    });
}
</script>
<script src="{{ asset('staffs/staff-orders.js') }}"></script>
@endpush
