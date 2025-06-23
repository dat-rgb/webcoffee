document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.check-row');
    const form = document.getElementById('phieuNhapForm');

    function updateRowHighlight() {
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            row.classList.toggle('selected', cb.checked);
        });
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateRowHighlight();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const row = this.closest('tr');
            row.classList.toggle('selected', this.checked);
            checkAll.checked = [...checkboxes].every(box => box.checked);
        });
    });

    updateRowHighlight();

    if (form) {
        form.addEventListener('submit', function (e) {
            const checkedRows = document.querySelectorAll('.check-row:checked');
            if (checkedRows.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Chưa chọn nguyên liệu',
                    text: 'Bạn phải chọn ít nhất 1 nguyên liệu để xuất phiếu!',
                    confirmButtonText: 'Đã hiểu'
                });
                return;
            }

            let valid = true;
            checkedRows.forEach(cb => {
                const ma = cb.value;
                const sl = document.querySelector(`[name="so_luong_du_kien[${ma}]"]`);
                const dv = document.querySelector(`[name="don_vi_tinh[${ma}]"]`);
                if (!sl?.value || sl.value <= 0 || !dv?.value.trim()) {
                    valid = false;
                }
            });

            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng nhập đầy đủ Số lượng và Đơn vị tính cho các dòng đã chọn!',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});
