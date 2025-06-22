document.addEventListener("DOMContentLoaded", function () {
    // Toggle nhóm nguyên liệu khi bật/tắt size
    document.querySelectorAll('.toggle-size').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const size = this.dataset.size;
            const group = document.querySelector(`.ingredient-group[data-size-group="${size}"]`);
            if (this.checked) {
                group.classList.remove('d-none');
            } else {
                group.classList.add('d-none');
                group.querySelectorAll('input').forEach(input => input.value = '');
                group.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            }
        });
    });

    // Thêm / Xóa nguyên liệu trong từng nhóm size
    document.querySelectorAll('.ingredient-group').forEach(group => {
        group.addEventListener('click', function (e) {
            const target = e.target;

            // Thêm (+)
            if (target.classList.contains('add-ingredient-btn')) {
                const block = target.closest('.ingredient-block');
                const newBlock = block.cloneNode(true);

                newBlock.querySelectorAll('input').forEach(input => input.value = '');
                newBlock.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                block.after(newBlock);
            }

            // Xóa (-)
            if (target.classList.contains('remove-ingredient-btn')) {
                const block = target.closest('.ingredient-block');
                const blocks = group.querySelectorAll('.ingredient-block');

                if (blocks.length > 1) {
                    block.remove();
                } else {
                    block.querySelectorAll('input').forEach(input => input.value = '');
                    block.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                }
            }
        });
    });
});

// Validate khi submit form
$(document).ready(function () {
    $('#thanh-phan-form').submit(function (e) {
        let errorMessage = "";

        let productId = $('input[name="ma_san_pham"]').val().trim();
        if (productId === "") {
            errorMessage += "Mã sản phẩm không được để trống.<br>";
        }

        let checkedSizes = $('.toggle-size:checked');
        if (checkedSizes.length === 0) {
            errorMessage += "Phải chọn ít nhất 1 size.<br>";
        }

        checkedSizes.each(function () {
            let size = $(this).data('size');
            let group = $(`.ingredient-group[data-size-group="${size}"]`);
            let ingValues = [];
            let countValid = 0;

            group.find('.ingredient-block').each(function () {
                let ing = $(this).find('select[name^="ingredients"]').val();
                let qty = $(this).find('input[name^="dinh_luongs"]').val();
                let unit = $(this).find('input[name^="don_vis"]').val();

                if (ing && qty && unit) {
                    if (!/^[a-zA-Z]+$/.test(unit)) {
                        errorMessage += `• Size ${size}: Đơn vị chỉ nên gồm chữ cái (vd: ml, g, ly).<br>`;
                    }

                    ingValues.push(ing);
                    countValid++;
                } else {
                    errorMessage += `• Size ${size}: Vui lòng điền đầy đủ nguyên liệu, định lượng và đơn vị.<br>`;
                }
            });

            if (countValid < 2) {
                errorMessage += `• Size ${size}: Phải chọn ít nhất 2 nguyên liệu.<br>`;
            }

            let uniqueIng = [...new Set(ingValues)];
            if (uniqueIng.length !== ingValues.length) {
                errorMessage += `• Size ${size}: Nguyên liệu không được trùng nhau.<br>`;
            }
        });

        // ✅ Thêm hidden input 'sizes[]' trước khi submit
        $('input[name="sizes[]"]').remove();
        checkedSizes.each(function () {
            const size = $(this).data('size');
            const input = $('<input>').attr({
                type: 'hidden',
                name: 'sizes[]',
                value: size
            });
            $('#thanh-phan-form').append(input);
        });

        if (errorMessage !== "") {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi nhập liệu',
                html: errorMessage,
                confirmButtonText: 'Đã hiểu'
            });
            e.preventDefault();
        }
    });
});
