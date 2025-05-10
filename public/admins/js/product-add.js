 // Xử lý hiển thị theo checkbox
 const checkboxSmall = document.getElementById('sizeSmallCheckbox');
 const containerSmall = document.getElementById('ingredientContainerSmall');

 const checkboxMedium = document.getElementById('sizeMediumCheckbox');
 const containerMedium = document.getElementById('ingredientContainerMedium');

 const checkboxLarge = document.getElementById('sizeLargeCheckbox');
 const containerLarge = document.getElementById('ingredientContainerLarge');

 checkboxSmall.addEventListener('change', () => {
     containerSmall.style.display = checkboxSmall.checked ? 'block' : 'none';
 });

 checkboxMedium.addEventListener('change', () => {
     containerMedium.style.display = checkboxMedium.checked ? 'block' : 'none';
 });

 checkboxLarge.addEventListener('change', () => {
     containerLarge.style.display = checkboxLarge.checked ? 'block' : 'none';
 });

 // Xử lý nút + cho từng size
 document.querySelectorAll('.addIngredientBtn').forEach(button => {
     button.addEventListener('click', () => {
         const container = button.parentElement;
         const form = container.querySelector('.ingredient-form');
         const newForm = form.cloneNode(true);
         container.insertBefore(newForm, button);
     });
 });

 