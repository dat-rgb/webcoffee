<!DOCTYPE html>
<html lang="en">
  <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>@yield('title',"Admin ". $thongTinWebsite['ten_website'] )</title>
      <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="icon" href="{{ asset('images/' . $thongTinWebsite['favicon']) }}" type="image/x-icon"/>
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
      <script src="https://cdn.tiny.cloud/1/pu87m0gh8r1tj8scy6wbxga6he21qzar3bf6356d19qwm6x6/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
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
        @php
          $isHomeActive = request()->routeIs('admin.home.*');
          $isOrderActive = request()->routeIs('admin.orders.*');
          $isProductActive = request()->routeIs('admin.products.*');
          $isCategoryActive = request()->routeIs('admins.category.*');
          $isMaterialActive = request()->routeIs('admins.material.*');
          $isShopMaterialActive = request()->routeIs('admins.shopmaterial.*');
          $isSupplierActive = request()->routeIs('admins.supplier.*');
          $isStoreActive = request()->routeIs('admin.store.*') || request()->routeIs('admin.product-shop.*');
          $isVoucherActive = request()->routeIs('admin.vouchers.*');
          $isBlogActive = request()->routeIs('admin.blog.*');
          $isNhanVienActive = request()->routeIs('admins.nhanvien.*');
          $isThongTinWebsite = request()->routeIs('admin.thongTinWebSite');
          $isContact = request()->routeIs('admin.contact.*');
        @endphp

      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
              <img
                src="{{ asset('images/' . $thongTinWebsite['logo']) }}"
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
              <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Home</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{ $isHomeActive ? 'show' : '' }}" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admin.dashboard') }}">
                        <span class="sub-item">Dashboard</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.home.thongTinWebSite') }}">
                        <span class="sub-item">Thông tin website</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.home.banner.show') }}">
                        <span class="sub-item">Banners</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Thao Tác</h4>
              </li>
              <!-- Đơn hàng -->
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-shopping-cart"></i>
                  <p>Đơn hàng</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{ $isOrderActive ? 'show' : '' }}" id="base">
                  <ul class="nav nav-collapse">
                    <li class="{{ $isOrderActive ? 'current-list-item' : '' }}">
                      <a href="{{ route('admin.orders.list') }}">
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
                <div class="collapse {{ $isProductActive ? 'show' : '' }}" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admin.products.list') }}">
                        <span class="sub-item">Danh sách sản phẩm</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.products.add') }}">
                        <span class="sub-item">Thêm sản phẩm</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- Danh mục sản phẩm -->
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#forms">
                  <i class="fas fa-wine-glass-alt"></i>
                  <p>Danh mục sản phẩm</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{ $isCategoryActive ? 'show' : '' }}" id="forms">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admins.category.index') }}">
                        <span class="sub-item">Danh mục</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admins.category.create') }}">
                        <span class="sub-item">Tạo danh mục</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- Nguyên liệu -->
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#tables">
                  <i class="fas fa-leaf"></i>
                  <p>Nguyên liệu</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{  $isMaterialActive ? 'show' : '' }}" id="tables">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admins.material.index') }}">
                        <span class="sub-item">Danh sách nguyên liệu</span>
                      </a>
                    </li>
                    <li>
                        <a href="{{ route('admins.material.archive.index') }}">
                            <span class="sub-item">Nguyên liệu tạm xóa</span>
                        </a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- Cửa hàng nguyên liệu -->
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#materials">
                  <i class="fas fa-blender"></i>
                  <p>Cửa hàng nguyên liệu</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{  $isShopMaterialActive ? 'show' : '' }}" id="materials">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admins.shopmaterial.index') }}">
                        <span class="sub-item">Kho cửa hàng nguyên liệu</span>
                      </a>
                    </li>
                    <li>
                        <a href="{{ route('admins.shopmaterial.showAllPhieu') }}">
                            <span class="sub-item">Phiếu nhập xuất hủy</span>
                        </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- Nhà cung cấp --}}
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#suppliers">
                  <i class="fas fa-boxes"></i>
                  <p>Nhà cung cấp</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{ $isSupplierActive ? 'show' : '' }}" id="suppliers">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admins.supplier.index') }}">
                        <span class="sub-item">Danh sách nhà cung cấp</span>
                      </a>
                    </li>
                    <li>
                        <a href="{{ route('admins.supplier.archived') }}">
                            <span class="sub-item">Danh sách nhà cung cấp bị ẩn</span>
                        </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- Cửa hàng --}}
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#store">
                  <i class="fas fa-store"></i>
                  <p>Cửa hàng</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{ $isStoreActive ? 'show' : '' }}" id="store">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admin.store.index') }}">
                        <span class="sub-item">Danh sách cửa hàng</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.product-shop.index') }}">
                        <span class="sub-item">Sản phẩm tại cửa hàng</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- Voucher --}}
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#voucher">
                  <i class="fas fa-gift"></i>
                  <p>Voucher</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{  $isVoucherActive ? 'show' : '' }}" id="voucher">
                  <ul class="nav nav-collapse">
                    <li>
                        <a href="{{ route('admin.vouchers.list') }}">
                          <span class="sub-item">Danh sách Vouchers</span>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('admin.vouchers.form') }}">
                            <span class="sub-item">Thêm Voucher</span>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('admin.vouchers.list-vouchers-off') }}">
                            <span class="sub-item">Voucher đã ẩn</span>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('admin.vouchers.deleted-list') }}">
                          <span class="sub-item">Voucher đã xóa</span>
                        </a>
                      </li>
                  </ul>
                </div>
              </li>
              {{-- Blog --}}
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#blog">
                  <i class="fas fa-newspaper"></i>
                  <p>Blog</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{  $isBlogActive ? 'show' : '' }}" id="blog">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admin.blog.index') }}">
                        <span class="sub-item">Danh sách blog</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.blog.add') }}">
                          <span class="sub-item">Thêm blog</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- Liên hệ --}}
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#contact">
                  <i class="fab fa-facebook-messenger"></i>
                  <p>Liên hệ</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse {{  $isContact ? 'show' : '' }}" id="contact">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('admin.contact.list') }}">
                        <span class="sub-item">Danh sách liên hệ</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- Admin --}}
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#submenu">
                  <i class="fas fa-user-tie"></i>
                  <p>Admin</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="submenu">
                  <ul class="nav nav-collapse">
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav1">
                        <span class="sub-item">Nhân viên</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse {{ $isNhanVienActive ? 'show' : '' }}" id="subnav1">
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
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link dropdown-toggle" href="#" id="messageDropdown"  role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                  </a>
                  <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                    <li>
                      <div
                        class="dropdown-title d-flex justify-content-between align-items-center">
                        Messages
                        <a href="#" class="small">Mark all as read</a>
                      </div>
                    </li>
                    <li>
                      <div class="message-notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-img">
                              <img src="{{ asset('admins/img/jm_denis.jpg') }}" alt="Img Profile"/>
                            </div>
                            <div class="notif-content">
                              <span class="subject">Jimmy Denis</span>
                              <span class="block"> How are you ? </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img src="{{ asset('admins/img/chadengle.jpg') }}" alt="Img Profile"/>
                            </div>
                            <div class="notif-content">
                              <span class="subject">Chad</span>
                              <span class="block"> Ok, Thanks ! </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img src="{{ asset('admins/img/mlane.jpg') }}"  alt="Img Profile"/>
                            </div>
                            <div class="notif-content">
                              <span class="subject">Jhon Doe</span>
                              <span class="block">
                                Ready for the meeting today...
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="{{ asset('admins/img/talha.jpg') }}"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Talha</span>
                              <span class="block"> Hi, Apa Kabar ? </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all messages<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link dropdown-toggle" href="#"  id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                  </a>
                  <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                      <div class="dropdown-title">
                        You have 4 new notification
                      </div>
                    </li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-icon notif-primary">
                              <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> New user registered </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-success">
                              <i class="fa fa-comment"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Rahmad commented on Admin
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="{{ asset('admins/img/profile2.jpg') }}"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Reza send messages to you
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-danger">
                              <i class="fa fa-heart"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> Farrah liked Admin </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all notifications<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link"  data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="mb-1 title">Quick Actions</span>
                      <span class="subtitle op-7">Shortcuts</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="m-0 row">
                          <a class="p-0 col-6 col-md-4" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="far fa-calendar-alt"></i>
                              </div>
                              <span class="text">Calendar</span>
                            </div>
                          </a>
                          <a class="p-0 col-6 col-md-4" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-warning rounded-circle">
                                <i class="fas fa-map"></i>
                              </div>
                              <span class="text">Maps</span>
                            </div>
                          </a>
                          <a class="p-0 col-6 col-md-4" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-file-excel"></i>
                              </div>
                              <span class="text">Reports</span>
                            </div>
                          </a>
                          <a class="p-0 col-6 col-md-4" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-success rounded-circle">
                                <i class="fas fa-envelope"></i>
                              </div>
                              <span class="text">Emails</span>
                            </div>
                          </a>
                          <a class="p-0 col-6 col-md-4" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-primary rounded-circle">
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Invoice</span>
                            </div>
                          </a>
                          <a class="p-0 col-6 col-md-4" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-secondary rounded-circle">
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Payments</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false" >
                    <div class="avatar-sm">
                      <img src="{{ asset('admins/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                    <span class="op-7">Xin chào,</span>
                      <span class="fw-bold">{{ Auth::guard('admin')->user()->nhanvien->ho_ten_nhan_vien ?? 'Admin' }}</span>
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
                            <h4>{{ Auth::guard('admin')->user()->nhanvien->ho_ten_nhan_vien ?? 'Admin' }}</h4>
                            <p class="text-muted">{{ Auth::guard('admin')->user()->email }}</p>
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
                {{ $thongTinWebsite['footer_text'] }}
                </div>
            </div>
        </footer>
      </div>
    </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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
  </script>
  @stack('scripts')
  </body>
</html>
