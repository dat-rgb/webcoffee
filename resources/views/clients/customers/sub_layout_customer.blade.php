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
        <div class="p-4 border rounded-lg shadow bg-white">
            <h5 class="mb-4 text-uppercase font-weight-bold">Tài khoản của bạn</h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <a href="{{ route('customer.index') }}" class="sidebar-link">
                        <i class="fas fa-user text-primary"></i>
                        <span>Hồ sơ</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="sidebar-link">
                        <i class="far fa-address-book text-success"></i>
                        <span>Sổ địa chỉ</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-heart text-danger"></i>
                        <span>Sản phẩm yêu thích</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('customer.order.history') }}" class="sidebar-link">
                        <i class="fas fa-history text-info"></i>
                        <span>Lịch sử mua hàng</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-eye text-warning"></i>
                        <span>Sản phẩm đã xem</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-key text-secondary"></i>
                        <span>Đổi mật khẩu</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<style>
    .toggle-menu-wrapper {
    margin-top: 0.5rem; /* hoặc 0 nếu muốn sát */
    margin-bottom: 0.5rem; /* giảm margin dưới nếu muốn */
}

    .sidebar-link {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    background-color: #fff;
    border-radius: 0.5rem;
    color: #343a40;
    transition: all 0.2s ease-in-out;
    font-weight: 500;
    text-decoration: none;
}

.sidebar-link i {
    font-size: 1.1rem;
    margin-right: 10px;
    width: 22px;
    text-align: center;
}

.sidebar-link:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    color: #000;
    text-decoration: none;
}

@media (max-width: 991.98px) {
    #accountMenu.collapse:not(.show) {
        display: none;
    }
}

@media (max-width: 767.98px) {
    .sidebar-link {
        font-size: 0.95rem;
        padding: 10px 12px;
    }

    .sidebar-link i {
        font-size: 1rem;
    }
}

</style>