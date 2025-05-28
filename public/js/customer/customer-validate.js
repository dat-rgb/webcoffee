
$(document).ready(function(){
    $('#customer-info-form').submit(function (e){
        let hoTen = $('#hoTen').val().trim();
        let soDienThoai = $('#soDienThoai').val().trim();
        let ngaySinh = $('#ngaySinh').val();
        let gioiTinh = $('#gioiTinh').val();

        let errorMessage = "";

        // Validate họ tên
        if (hoTen === "") {
            errorMessage += "Họ tên không được để trống.<br/>";
        }

        // Validate số điện thoại
        let phoneRegex = /^0\d{9}$/;
        if (soDienThoai === "") {
            errorMessage += "Số điện thoại không được để trống.<br/>";
        } else if (!phoneRegex.test(soDienThoai)) {
            errorMessage += "Số điện thoại không đúng định dạng (VD: 0912345678).<br/>";
        }

        // Validate ngày sinh
        if (ngaySinh === "") {
            errorMessage += "Ngày sinh không được để trống.<br/>";
        } else {
            let today = new Date();
            let birthDate = new Date(Date.parse(ngaySinh + 'T00:00:00')); // đảm bảo đúng ngày và không lệch múi giờ

            if (birthDate > today) {
                errorMessage += "Ngày sinh không hợp lệ (không thể lớn hơn ngày hiện tại).<br/>";
            } else {
                // Tính tuổi chính xác
                let age = today.getFullYear() - birthDate.getFullYear();
                let m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                if(age > 100){
                    errorMessage += "Tuổi không hợp lệ.<br/>";
                }
                if (age < 16) {
                    errorMessage += "Bạn phải đủ 16 tuổi trở lên.<br/>";
                }
            }
        }
        // Validate giới tính
        if (gioiTinh === "") {
            errorMessage += "Vui lòng chọn giới tính.<br/>";
        }

        if (errorMessage !== "") {
            e.preventDefault();
            Swal.fire({
                title: 'Lỗi!',
                html: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});

