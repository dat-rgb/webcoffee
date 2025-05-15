<!-- Store Popup -->
<div id="store-modal" class="store-modal d-none">
    <div class="store-modal-dialog">
        <div class="store-modal-body">
            <button class="store-close-btn" onclick="closeStoreModal()">&times;</button>

            <!-- Tabs -->
            <ul class="nav nav-tabs nav-justified mb-3" id="storeTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="delivery-tab" data-toggle="tab" href="#delivery" role="tab">GIAO HÀNG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pickup-tab" data-toggle="tab" href="#pickup" role="tab">ĐẾN LẤY</a>
                </li>
            </ul>

            <!-- Search bar -->
            <div class="input-group search-store mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Vui lòng nhập địa chỉ, tên cửa hàng">
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="delivery" role="tabpanel">
                    <!-- Danh sách giao hàng ở đây -->
                </div>
                <div class="tab-pane fade" id="pickup" role="tabpanel">
                    <!-- Danh sách cửa hàng đến lấy ở đây -->
                </div>
            </div>
        </div>
    </div>
</div>
