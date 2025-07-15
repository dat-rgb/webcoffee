<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title',"Staff CDMT Coffee & Tea")</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ma-cua-hang" content="{{ Auth::guard('staff')->user()->nhanvien->ma_cua_hang ?? '' }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="{{ asset('admins/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('admins/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('admins/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admins/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admins/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admins/css/demo.css') }}" />
    <style>
      #toast-container {
          top: 12px;
          left: 12px;
          left: auto;
      }
      #toast-container > .toast-success {
          background-color: #28a745 !important; /* Xanh lá */
          color: white !important;
      }

    </style>
    @stack('styles')
    @vite('resources/js/app.js')
  </head>
  <body>
    <div class="wrapper">
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{ route('staff') }}" class="logo">
              <img
                src="{{ asset('img/logo.png') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="50"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
                @if(Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 1)
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false" >
                    <i class="fas fa-home"></i>
                    <p>Cửa hàng</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="dashboard">
                    <ul class="nav nav-collapse">
                        <li>
                        <a href="{{ route('staff.dashboard') }}">
                            <span class="sub-item">{{ Auth::guard('staff')->user()->nhanvien->cuaHang->ten_cua_hang }}</span>
                        </a>
                        </li>
                    </ul>
                    </div>
                </li>
                @endif
                <li class="nav-section">
                  <span class="sidebar-mini-icon">
                      <i class="fa fa-ellipsis-h"></i>
                  </span>
                  <h4 class="text-section">Thao Tác</h4>
                </li>
                @if (Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 1 || Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 3)
                <!-- Đơn hàng -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#base">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Đơn hàng</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base">
                    <ul class="nav nav-collapse">
                        <li>
                        <a href="{{ route('staff.orders.list') }}">
                            <span class="sub-item">Danh sách Đơn hàng</span>
                        </a>
                        </li>
                    </ul>
                    </div>
                </li>
                <!-- Sản phẩm -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts">
                    <i class="fas fa-coffee"></i>
                    <p>Sản phẩm</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                    <ul class="nav nav-collapse">
                        <li>
                          <a href="{{ route('staff.productStore',['status' => 1]) }}">
                            <span class="sub-item">Danh sách sản phẩm</span>
                          </a>
                        </li>
                    </ul>
                    </div>
                </li>
                @endif
                @if (Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 1)
                <!-- Quản lý kho nguyên liệu cửa hàng -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#materials">
                    <i class="fas fa-laptop"></i>
                    <p>Cửa hàng nguyên liệu</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="materials">
                    <ul class="nav nav-collapse">
                        <li>
                        <a href="{{ route('staffs.shop_materials.index') }}">
                            <span class="sub-item">Kho cửa hàng nguyên liệu</span>
                        </a>
                        </li>
                        <li>
                            <a href="{{ route('staffs.shop_materials.showAllPhieu') }}">
                                <span class="sub-item">Phiếu nhập xuất hủy nguyên liệu</span>
                            </a>
                        </li>
                    </ul>
                    </div>
                </li>
                <!-- Chức năng cho quản lý nhân viên cửa hàng -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#submenu">
                    <i class="fas fa-user-tie"></i>
                    <p>Quản trị viên</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="submenu">
                    <ul class="nav nav-collapse">
                        <!-- Quản lý nhân viên cửa hàng -->
                        <li>
                        <a data-bs-toggle="collapse" href="#subnav1">
                            <span class="sub-item">Nhân viên</span>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="subnav1">
                            <ul class="nav nav-collapse subnav">
                            <li>
                                <a href="{{ route('staffs.nhanviens.index') }}">
                                <span class="sub-item">Danh sách nhân viên</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Danh sách nhân viên nghỉ việc</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                        </li>
                    </ul>
                    </div>
                </li>
                @elseif(Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu != 1
                    && Auth::guard('staff')->user()->nhanvien->ma_cua_hang)
                    <li class="nav-item">
                        <a href="{{ route('staffs.nhanviens.lich.tuan') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <p>Lịch làm việc</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
      </div>
    </div>
    <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="#" class="logo">
                <img src="{{ asset('admins/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand" height="20"/>
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <nav class="p-0 navbar navbar-header-left navbar-expand-lg navbar-form nav-search d-none d-lg-flex">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input type="text" placeholder="Search ..." class="form-control"/>
                </div>
              </nav>
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                  <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"  href="#" role="button" aria-expanded="false" aria-haspopup="true">
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input type="text" placeholder="Search ..." class="form-control"/>
                      </div>
                    </form>
                  </ul>
                </li>
                <!--  -->
                <li class="nav-item topbar-icon dropdown hidden-caret submenu">
                  <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="notification"></span>
                  </a>
                  <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                      <div class="dropdown-title">
                       
                      </div>
                    </li>
                    <li>
                      <div class="scroll-wrapper notif-scroll scrollbar-outer" style="position: relative;"><div class="notif-scroll scrollbar-outer scroll-content" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 244px;">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-icon notif-primary">
                              <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="notif-content">

                            </div>
                          </a>
                        </div>
                      </div><div class="scroll-element scroll-x"><div class="scroll-element_outer"><div class="scroll-element_size"></div><div class="scroll-element_track"></div><div class="scroll-bar" style="width: 0px;"></div></div></div><div class="scroll-element scroll-y"><div class="scroll-element_outer"><div class="scroll-element_size"></div><div class="scroll-element_track"></div><div class="scroll-bar" style="height: 0px;"></div></div></div></div>
                    </li>
                  </ul>
                </li>
                <!--  -->
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false" >
                    <div class="avatar-sm">
                      <img src="{{ asset('admins/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                    <span class="op-7">Xin chào,</span>
                      <span class="fw-bold">{{ Auth::guard('staff')->user()->nhanvien->ho_ten_nhan_vien ?? 'Nhân viên' }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img  src="{{ asset('admins/img/profile.jpg') }}"  alt="image profile" class="rounded avatar-img"/>
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::guard('staff')->user()->nhanvien->ho_ten_nhan_vien ?? 'Nhân viên' }}</h4>
                            <p class="text-muted">{{ Auth::guard('staff')->user()->email }}</p>
                            <a href="{{ route('staff.profile') }}" class="btn btn-xs btn-secondary btn-sm" >View Profile</a>
                          </div>
                        </div>
                      </li>
                      <li>
                          <div class="dropdown-divider"></div>
                          <a href="#" id="change-password-btn" class="dropdown-item">
                              <i class="fas fa-key" style="margin-right:6px;"></i>Đổi mật khẩu
                          </a>
                          <div class="dropdown-divider"></div>
                          <button type="button" id="logout-btn" class="dropdown-item">
                              <i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Đăng xuất
                          </button>
                          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                              @csrf
                            
                          </form>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
      </div>

    <div class="container">
        @yield('content')
    </div>
    <form id="reset-password-form" method="POST" action="{{ route('forgotPassword.send') }}">
        @csrf
        <input type="hidden" name="email" value="{{ Auth::guard('staff')->user()->email }}">
    </form>
    <audio id="order-sound" src="{{ asset('sounds/am_thanh_thong_bao.mp3') }}" preload="auto"></audio>
    <footer class="footer">
        <div class="container-fluid d-flex justify-content-center">
            <div class="copyright">
            {{ $thongTinWebsite['footer_text'] }}
            </div>
        </div>
    </footer>
  </div>
    <!-- Scripts -->
    <script src="{{ asset('admins/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('admins/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admins/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admins/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('admins/js/kaiadmin.min.js') }}"></script>
    <script src="{{ asset('admins/js/setting-demo2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      document.getElementById('logout-btn').addEventListener('click', function (e) {
          e.preventDefault();

          const chucVu = @json(Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu);
          if (chucVu === 1 || chucVu === 3) {
              Swal.fire({
                  title: 'Nhập thông tin kết ca',
                  html:
                      `<input type="number" id="tien-dau-ca" class="swal2-input" placeholder="Tiền đầu ca" value="">` +
                      `<input type="number" id="tien-thuc-nhan" class="swal2-input" placeholder="Tiền nhận (gồm cả tiền đầu ca)">`,
                  showCancelButton: true,
                  confirmButtonText: 'Xác nhận & In phiếu',
                  cancelButtonText: 'Huỷ',
                  preConfirm: () => {
                      const tienDauCa = parseFloat(document.getElementById('tien-dau-ca').value);
                      const tienThucNhan = parseFloat(document.getElementById('tien-thuc-nhan').value);
                      if (isNaN(tienDauCa) || isNaN(tienThucNhan)) {
                          Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin!');
                          return false;
                      }
                      return { tien_dau_ca: tienDauCa, tien_thuc_nhan: tienThucNhan };
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                      const { tien_dau_ca, tien_thuc_nhan } = result.value;

                      // Tạo form tạm để gửi POST in phiếu
                      const form = document.createElement('form');
                      form.method = 'POST';
                      form.action = '{{ route("admin.ketca") }}';
                      form.target = '_blank';

                      const csrf = document.createElement('input');
                      csrf.type = 'hidden';
                      csrf.name = '_token';
                      csrf.value = '{{ csrf_token() }}';
                      form.appendChild(csrf);

                      const tienDauCaInput = document.createElement('input');
                      tienDauCaInput.type = 'hidden';
                      tienDauCaInput.name = 'tien_dau_ca';
                      tienDauCaInput.value = tien_dau_ca;
                      form.appendChild(tienDauCaInput);

                      const tienThucNhanInput = document.createElement('input');
                      tienThucNhanInput.type = 'hidden';
                      tienThucNhanInput.name = 'tien_thuc_nhan';
                      tienThucNhanInput.value = tien_thuc_nhan;
                      form.appendChild(tienThucNhanInput);

                      document.body.appendChild(form);
                      form.submit();

                      // Delay vài giây rồi logout
                      setTimeout(() => {
                          document.getElementById('logout-form').submit();
                      }, 3000); // đợi 3s để tải PDF
                  }
              });
          } else {
              // Không phải ca trưởng thì logout luôn
              document.getElementById('logout-form').submit();
          }
      });

      document.addEventListener('DOMContentLoaded', function () {
        fetch('/staff/orders/count')
          .then(res => res.json())
          .then(data => {
            // Đếm số
            const count = data.orderCount ?? 0;
            document.querySelector('.notification').innerText = count;
            document.querySelector('.dropdown-title').innerText = `Bạn có ${count} đơn hàng mới`;

            // Cập nhật dropdown nếu có danh sách đơn hàng
            const notifCenter = document.querySelector('.notif-center');
            if (Array.isArray(data.orders)) {
              notifCenter.innerHTML = ''; // clear cũ
              data.orders.forEach(order => {
                notifCenter.insertAdjacentHTML('beforeend', `
                 <a href="/staff/orders?highlight=${order.ma_hoa_don}">
                    <div class="notif-icon notif-primary"><i class="fas fa-check-circle"></i></div>
                    <div class="notif-content">
                      <span class="block">Đơn hàng ${order.ma_hoa_don}</span>
                      <span class="time">${new Date(order.ngay_lap_hoa_don).toLocaleTimeString()}</span>
                    </div>
                  </a>
                `);
              });
            }
          })
          .catch(err => console.error("Lỗi lấy đơn hàng mới:", err));
      });

      
      document.getElementById('change-password-btn').addEventListener('click', function(e) {
          e.preventDefault(); // Ngăn chặn hành vi mặc định

          const email = @json(Auth::guard('staff')->user()->email);

          Swal.fire({
              title: 'Bạn có muốn đổi mật khẩu?',
              text: `Thông tin xác nhận sẽ được gửi về email của bạn: ${email}`,
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: 'Có, gửi xác nhận',
              cancelButtonText: 'Không',
              reverseButtons: true
          }).then((result) => {
              if (result.isConfirmed) {
                  // Submit form bằng JS
                  document.getElementById('reset-password-form').submit();
              }
          });
      });

      @if (
          Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 1 ||
          Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 3
      )
      document.addEventListener('DOMContentLoaded', function () {
        const maCuaHang = document.querySelector('meta[name="ma-cua-hang"]').content;
        const audio = document.getElementById('order-sound');
        if (typeof window.Echo !== 'undefined') {
          console.log("Subscribing to: orders." + maCuaHang);
          window.Echo.channel('orders.' + maCuaHang)
            .listen('.order.created', (e) => {
              console.log("Đơn mới:", e);
              toastr.success(`Bạn có đơn hàng mới: ${e.order.ma_hoa_don}`, 'Thông báo', {
                positionClass: 'toast-top-right',
                closeButton: true,
                progressBar: true
              });
              audio.play();

              // Tăng số lượng
              const bellCount = document.querySelector('.notification');
              let currentCount = parseInt(bellCount.innerText || 0);
              bellCount.innerText = currentCount + 1;

              // Cập nhật title
              document.querySelector('.dropdown-title').innerText = `Bạn có ${currentCount + 1} đơn hàng mới`;

              // Thêm vào dropdown
              const newNotif = `
                <a href="/staff/orders?highlight=${e.order.ma_hoa_don}">
                  <div class="notif-icon notif-primary"><i class="fas fa-check-circle"></i></div>
                  <div class="notif-content">
                    <span class="block">Đơn hàng ${e.order.ma_hoa_don}</span>
                    <span class="time">Vừa xong</span>
                  </div>
                </a>
              `;

              document.querySelector('.notif-center').insertAdjacentHTML('afterbegin', newNotif);
              loadOrdersPartial();
            });
        } else {
          console.warn("window.Echo chưa load kịp.");
        }
      });
      @endif

      function loadOrdersPartial() {
        fetch('/staff/orders/partial')
            .then(res => res.text())
            .then(html => {
                const tbody = document.getElementById('order-tbody');
                if (tbody) {
                    tbody.innerHTML = html;
                    bindOrderStatusEvents(); 
                } else {
                    console.warn('Không tìm thấy order-tbody');
                }
            })
            .catch(err => console.error("Lỗi fetch đơn hàng mới:", err));
      }
    </script>
    @stack('scripts')
  </body>
</html>
