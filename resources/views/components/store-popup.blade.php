<!-- Store Popup -->
<div id="store-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body p-4 position-relative">
        <button type="button" class="close position-absolute" style="top: 10px; right: 15px; font-size: 1.5rem;" onclick="closeStoreModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        <h5 class="mb-3 text-center font-weight-bold">Danh sách cửa hàng</h5>

        <!-- Search bar -->
        <div class="input-group search-store mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" class="form-control" placeholder="Vui lòng nhập tên cửa hàng" id="searchStoreInput" onkeyup="filterStores()" />
        </div>

        <!-- Store List -->
        <ul class="list-group" id="storeList">
          @forelse ($stores as $store)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-store-name="{{ strtolower($store->ten_cua_hang) }}">
              <div>
                <strong>{{ $store->ten_cua_hang }}</strong><br />
                <small>{{ $store->dia_chi }}</small>
              </div>
              <button class="btn btn-sm btn-outline-primary" onclick="selectStore('{{ $store->ma_cua_hang }}')">
                Chọn
              </button>
            </li>
          @empty
            <li class="list-group-item text-center text-muted">Không tìm thấy cửa hàng.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
