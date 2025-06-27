<!-- Sidebar menu -->
<div class="col-lg-4 mb-4">
    <div class="collapse d-lg-block" id="accountMenu">
        <div class="p-3 border rounded shadow-sm">
            <ul class="sidebar-menu list-unstyled mb-0">
                <li class="sidebar-item {{ request()->routeIs('customer.index') ? 'active' : '' }}">
                    <a href="{{ route('customer.index') }}">
                        <i class="fas fa-user"></i> Thông tin tài khoản
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
                <li class="sidebar-item {{ request()->routeIs('customer.sanPhamDaXem') ? 'active' : '' }}">
                    <a href="{{ route('customer.sanPhamDaXem') }}">
                        <i class="fas fa-eye"></i> Sản phẩm đã xem
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('customer.sanPhamDaMua') ? 'active' : '' }}">
                    <a href="{{ route('customer.sanPhamDaMua') }}">
                        <i class="fas fa-box-open"></i> Sản phẩm đã mua
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('customer.uuDaiThanhVien') ? 'active' : '' }}">
                    <a href="{{ route('customer.uuDaiThanhVien') }}">
                        <i class="fas fa-gift"></i> Ưu đãi thành viên
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('forgotPassword.show') ? 'active' : '' }}">
                    <a href="{{ route('forgotPassword.show') }}">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.toggle-menu-wrapper {
    margin: 0.25rem 0; 
    text-align: right;
}

.toggle-menu-wrapper button {
    background-color: #F28123;
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
}

.toggle-menu-wrapper button:hover {
    background-color: #e6761f;
}


/* Sidebar container */
.p-3.border.rounded.shadow-sm {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 12px;
}

.p-3 h5 {
    color: #07212e;
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Sidebar menu layout */
.sidebar-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-left: 0;
    margin-bottom: 0;
}

@media (min-width: 992px) {
    .sidebar-menu {
        flex-direction: column;
        gap: 0.5rem;
    }
}

/* Sidebar items */
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

/* Sidebar item link */
.sidebar-item a {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    background-color: transparent;
    border-radius: 12px;
    color: #07212e;
    font-weight: 500;
    text-decoration: none;
    transition: 0.25s;
    position: relative;
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

/* Hover effect */
.sidebar-item a:hover {
    background-color: #fef1e8;
    color: #F28123;
}

/* Active item */
.sidebar-item.active a {
    background-color: #F28123;
    color: #fff;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

.sidebar-item.active a i {
    color: #fff;
}

/* Collapse (mobile) */
@media (max-width: 991.98px) {
    #accountMenu.collapse:not(.show) {
        display: none;
    }
}
</style>

