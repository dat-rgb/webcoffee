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

        <!-- Nút vị trí của bạn -->
        <div class="text-center mb-3">
          <button class="btn btn-sm btn-success" onclick="getCurrentLocation()">
            <i class="fas fa-map-marker-alt"></i> Vị trí của bạn
          </button>
        </div>

        <!-- Store List -->
        <ul class="list-group" id="storeList">
        
        </ul>
      </div>
    </div>
  </div>
</div>
