<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title',"Admin CDMT Coffee & Tea")</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('admins/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>
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

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('admins/css/demo.css') }}" />
    @stack('styles')

  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
              <img
                src="{{ asset('admins/img/kaiadmin/logo_light.svg') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
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
              <!-- DoashBroad cho quản lý cửa hàng -->
              <!-- {{ Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 1 }} -->
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
                        <a href="#">
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
                        <a href="#">
                          <span class="sub-item">Danh sách Đơn hàng</span>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <span class="sub-item">Thêm tiếp các mục (nếu có).</span>
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
                        <a href="">
                          <span class="sub-item">Danh sách sản phẩm</span>
                        </a>
                      </li>
                      <li>
                        <a href="">
                          <span class="sub-item">Sản phẩm ngưng bán</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
              @endif
              @if (Auth::guard('staff')->user()->nhanvien->chucVu->ma_chuc_vu == 1)
                <!-- Quản lý kho nguyên liệu của hàng -->
                <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#tables">
                    <i class="fas fa-leaf"></i>
                    <p>Nguyên liệu</p>
                    <span class="caret"></span>
                  </a>
                  <div class="collapse" id="tables">
                    <ul class="nav nav-collapse">
                      <li>
                        <a href="{{ route('admins.material.index') }}">
                          <span class="sub-item">Quản lý tồn kho</span>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('admins.material.create') }}">
                          <span class="sub-item">Nhập kho nguyên liệu</span>
                        </a>
                      </li>
                      <li>
                          <a href="{{ route('admins.material.archive.index') }}">
                              <span class="sub-item">Nguyên liệu ẩn</span>
                          </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <!-- Chức năng cho quản lý cửa hàng -->
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
                              <a href="{{ route('admins.nhanvien.index') }}">
                                <span class="sub-item">Danh sách nhân viên</span>
                              </a>
                            </li>
                            <li>
                              <a href="{{ route('admins.nhanvien.archived') }}">
                                  <span class="sub-item">Danh sách nhân viên nghỉ việc</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->
      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="#" class="logo">
                <img
                  src="{{ asset('admins/img/kaiadmin/logo_light.svg') }}"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
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
                            <h4>{{ Auth::guard('staff')->user()->nhanvien->ho_ten_nhan_vien ?? 'Admin' }}</h4>
                            <p class="text-muted">{{ Auth::guard('staff')->user()->email }}</p>
                            <a href="profile.html" class="btn btn-xs btn-secondary btn-sm" >View Profile</a>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">My Profile</a>
                        <a class="dropdown-item" href="#">My Balance</a>
                        <a class="dropdown-item" href="#">Inbox</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Account Setting</a>
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
          <!-- End Navbar -->
        </div>

        <div class="container">
            @yield('content')
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-center">
            <div class="copyright">
              2025, Đồ án tốt nghiệp của sinh viên
              <a href="#"> Chí Đạt & Minh Tân.</a>
            </div>
          </div>
        </footer>
      </div>
    </div>

  <!-- Load Toastr before your custom scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <!-- Thêm thư viện SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Core JS Files -->
  <script src="{{ asset('admins/js/core/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('admins/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('admins/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('admins/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
  <!-- jQuery Scrollbar -->
  <script src="{{ asset('admins/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
  <!-- Kaiadmin JS -->
  <script src="{{ asset('admins/js/kaiadmin.min.js') }}"></script>
  <!-- Kaiadmin DEMO methods, don't include it in your project! -->
  <script src="{{ asset('admins/js/setting-demo2.js') }}"></script>
  <script src="{{ asset('js/sweet-alert.js') }}"></script> 
  @stack('scripts')

  </body>
</html>
