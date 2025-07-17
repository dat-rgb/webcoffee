<!-- Store Popup -->
<div id="store-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body p-4 position-relative">
          <button type="button" class="close position-absolute" style="top: 10px; right: 15px; font-size: 1.5rem;" onclick="closeStoreModal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

          <!-- Nút vị trí của bạn -->
          <div class="text-left mb-4">
              <button class="btn btn-sm rounded-pill px-4 d-inline-flex align-items-center gap-2"
                style="border: 1px solid #F28123; color: #F28123; background-color: transparent; transition: all 0.2s ease;"
                onclick="getCurrentLocation()">
                <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>
                <span>Vị trí hiện tại của quý khách</span>
              </button>
            <div id="addressBox" class="mt-2 text-muted small fst-italic"></div>
          </div>

          <!-- Store List -->
          <ul class="list-group" id="storeList" data-selected-store="{{ session('selected_store_id') }}"></ul>
      </div>
    </div>
  </div>
</div>
