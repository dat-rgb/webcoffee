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
                        <a href="profile.html" class="btn btn-xs btn-secondary btn-sm" >View Profile</a>
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
      $(document).on('click', '#logout-btn', function (e) {
        e.preventDefault();
        Swal.fire({
          title: 'Đăng xuất?',
          text: "Bạn chắc chắn muốn đăng xuất?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Có, đăng xuất!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#logout-form').submit();
          }
        });
      });
        document.getElementById('change-password-btn').addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn chuyển trang

            // Lấy email từ blade
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
                    window.location.href = "#";
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
            });
        } else {
          console.warn("window.Echo chưa load kịp.");
        }
      });
      @endif
    </script>
    @stack('scripts')
  </body>
</html>
