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
  
