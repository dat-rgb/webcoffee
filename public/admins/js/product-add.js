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
  


// Lấy tất cả checkbox của size theo class chung
document.querySelectorAll('input[type="checkbox"][id^="checkbox"]').forEach(checkbox => {
    const sizeId = checkbox.id.replace('checkbox', ''); // Lấy phần số trong id checkbox1, checkbox2...
    const container = document.getElementById('ingredientContainer' + sizeId);

    if (container) {
        checkbox.addEventListener('change', () => {
            container.style.display = checkbox.checked ? 'block' : 'none';
        });
    }
});

// Xử lý nút + cho từng container (đã clone cả khối ingredient-form)
document.querySelectorAll('.addIngredientBtn').forEach(button => {
    button.addEventListener('click', () => {
        const container = button.parentElement;
        const form = container.querySelector('.ingredient-form');
        const newForm = form.cloneNode(true);
        container.insertBefore(newForm, button);
    });
});
