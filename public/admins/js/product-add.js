document.addEventListener('DOMContentLoaded', () => {
    // 1. Xử lý checkbox size show/hide nguyên liệu
    document.querySelectorAll('input[type="checkbox"][name]').forEach(cb => {
      // bỏ qua checkbox "san_pham_pha_che"
      if (cb.name === 'san_pham_pha_che') return;
  
      cb.addEventListener('change', () => {
        const container = document.getElementById('ingredientContainer' + cb.name);
        if (container) {
          container.style.display = cb.checked ? 'block' : 'none';
        }
      });
    });
  
    // 2. Checkbox sản phẩm pha chế ẩn toàn bộ card thành phần
    const phaCheCheckbox = document.querySelector('input[name="san_pham_pha_che"]');
    const thanhPhanCard = document.querySelector('#thanh_phan'); 
  
    if (phaCheCheckbox && thanhPhanCard) {
      phaCheCheckbox.addEventListener('change', () => {
        if (phaCheCheckbox.checked) {
          thanhPhanCard.style.display = 'none';
          // Tự động uncheck hết các size luôn cho sạch
          document.querySelectorAll('input[type="checkbox"][name]').forEach(cb => {
            if(cb.name !== 'san_pham_pha_che') cb.checked = false;
            const container = document.getElementById('ingredientContainer' + cb.name);
            if (container) container.style.display = 'none';
          });
        } else {
          thanhPhanCard.style.display = 'block';
        }
      });
    }
  });
  

document.addEventListener('DOMContentLoaded', function () {
  // Khi check chọn size thì show phần nhập N nguyên liệu
  document.querySelectorAll('.selectgroup-input').forEach(checkbox => {
      checkbox.addEventListener('change', function () {
          const sizeId = this.id.replace('checkbox', '');
          const container = document.querySelector(`#ingredientContainer${sizeId}`);
          container.style.display = this.checked ? 'block' : 'none';
      });
  });

  // Khi bấm nút "Tạo"
  document.querySelectorAll('.btn-generate-ingredients').forEach(btn => {
      btn.addEventListener('click', function () {
          const container = this.closest('.ingredient-container');
          const sizeId = container.id.replace('ingredientContainer', '');
          const numInput = container.querySelector('.num-ingredients');
          const num = parseInt(numInput.value);

          if (!num || num < 1 || num > 10) return alert('Nhập số nguyên liệu hợp lệ');

          const ingredientsList = container.querySelector('.ingredients-list');
          ingredientsList.innerHTML = ''; // clear cũ

          for (let i = 0; i < num; i++) {
              const html = `
                  <div class="ingredient-form mb-3">
                      <select class="form-select mb-2" name="sizes[${sizeId}][ingredients][${i}][ma_nguyen_lieu]">
                          <option value="">Chọn nguyên liệu ${i+1}</option>
                          @foreach ($ingredients as $ing)
                              <option value="{{ $ing->ma_nguyen_lieu }}">{{ $ing->ten_nguyen_lieu }}</option>
                          @endforeach
                      </select>
                      <input type="number" class="form-control mb-2" name="sizes[${sizeId}][ingredients][${i}][dinh_luong]" placeholder="Định lượng">
                      <select class="form-select mb-2" name="sizes[${sizeId}][ingredients][${i}][don_vi]">
                          <option value="g">g</option>
                          <option value="ml">ml</option>
                          <option value="ly">ly</option>
                      </select>
                  </div>
              `;
              ingredientsList.insertAdjacentHTML('beforeend', html);
          }
      });
  });
});