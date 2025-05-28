<!-- Toggle menu button (mobile) -->
<div class="text-right d-block d-lg-none toggle-menu-wrapper">
    <button class="btn btn-sm btn-outline-dark"
            type="button"
            data-toggle="collapse"
            data-target="#accountMenu"
            aria-expanded="false"
            aria-controls="accountMenu">
        <i class="fas fa-bars mr-1"></i> Menu
    </button>
</div>

<!-- Sidebar menu -->
<div class="col-lg-4 mb-4">
    <div class="collapse d-lg-block" id="accountMenu">
        <div class="p-3 border rounded shadow-sm">
            <h5 class="mb-3 text-uppercase font-weight-bold">Tài khoản của bạn</h5>
            <ul class="sidebar-menu list-unstyled mb-0">
                <li class="sidebar-item {{ request()->routeIs('customer.index') ? 'active' : '' }}">
                    <a href="{{ route('customer.index') }}">
                        <i class="fas fa-user"></i> Hồ sơ
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#">
                        <i class="far fa-address-book"></i> Sổ địa chỉ
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('favorite.show') ? 'active' : '' }}">
                    <a href="{{ route('favorite.show') }}">
                        <i class="fas fa-heart"></i> Sản phẩm yêu thích
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('customer.order.history') ? 'active' : '' }}">
                    <a href="{{ route('customer.order.history') }}">
                        <i class="fas fa-history"></i> Lịch sử mua hàng
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#">
                        <i class="fas fa-eye"></i> Sản phẩm đã xem
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
/* Toggle button margin */
.toggle-menu-wrapper {
    margin: 0.5rem 0;
}

/* Sidebar menu base */
.sidebar-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-left: 0;
    margin-bottom: 0;
}

/* Kiểu danh sách ngang (dạng tab) */
@media (min-width: 992px) {
    .sidebar-menu {
        flex-direction: column;
        gap: 0.5rem;
    }
}

/* Sidebar item */
.sidebar-item {
    list-style: none;
    flex: 1 1 auto;
    text-align: center;
}

@media (min-width: 992px) {
    .sidebar-item {
        flex: none;
        text-align: left;
    }
}

.sidebar-item a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    background-color: transparent; /* Không nền */
    border-radius: 0.5rem;
    color: #495057;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s ease, color 0.2s ease;
}

@media (min-width: 992px) {
    .sidebar-item a {
        justify-content: flex-start;
    }
}

.sidebar-item a i {
    margin-right: 10px;
    font-size: 1.1rem;
    width: 22px;
    text-align: center;
    color: #6c757d;
}

/* Hover */
.sidebar-item a:hover {
    background-color: #e9ecef;
    color: #212529;
}

/* Active */
.sidebar-item.active a {
    background-color: #007bff;
    color: #fff;
}

.sidebar-item.active a i {
    color: #fff;
}

/* Responsive - collapse behavior */
@media (max-width: 991.98px) {
    #accountMenu.collapse:not(.show) {
        display: none;
    }
}
</style>
