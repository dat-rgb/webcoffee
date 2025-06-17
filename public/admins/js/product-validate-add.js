$(document).ready(function () {
    $('#product-form').submit(function (e) {
        const selectedType = $('input[name="loai_san_pham"]:checked').val(); // 0: Pha chế, 1: Đóng gói
        let errorMessage = "";

        // Lấy vùng form đang hiển thị
        let formSection = selectedType === "0" ? $('#formPhaChe') : $('#formDongGoi');

        // Các input cần validate
        const productId = formSection.find('input[name="ma_san_pham"]').val()?.trim();
        const productName = formSection.find('input[name="ten_san_pham"]').val()?.trim();
        const price = formSection.find('input[name="gia"]').val()?.trim();
        const categoryId = formSection.find('select[name="ma_danh_muc"]').val();
        const status = formSection.find('select[name="trang_thai"]').val();
        const image = formSection.find('input[name="hinh_anh"]').val()?.trim();
        const description = formSection.find('textarea[name="mo_ta"]').val()?.trim();

        // Validate mã sản phẩm (readonly, vẫn kiểm tra)
        if (!productId || productId.length !== 10) {
            errorMessage += "- Mã sản phẩm phải đúng 10 ký tự.<br>";
        }

        // Validate tên sản phẩm
        if (!productName || productName.length < 2) {
            errorMessage += "- Tên sản phẩm phải có ít nhất 2 ký tự.<br>";
        } else if (productName.length > 255) {
            errorMessage += "- Tên sản phẩm không vượt quá 255 ký tự.<br>";
        }

        // Validate giá
        let priceNumber = parseFloat(price);
        if (price === "") {
            errorMessage += "- Vui lòng nhập giá sản phẩm.<br>";
        } else if (isNaN(priceNumber)) {
            errorMessage += "- Giá sản phẩm phải là số hợp lệ.<br>";
        } else {
            if (priceNumber < 1) {
                errorMessage += "- Giá sản phẩm phải lớn hơn 0.<br>";
            } else if (priceNumber > 10000000) {
                errorMessage += "- Giá sản phẩm không vượt quá 10 triệu.<br>";
            }
        }

        // Validate danh mục
        if (!categoryId) {
            errorMessage += "- Vui lòng chọn danh mục sản phẩm.<br>";
        }

        // Validate trạng thái
        if (!status) {
            errorMessage += "- Vui lòng chọn trạng thái sản phẩm.<br>";
        }

        // Hiện lỗi nếu có
        if (errorMessage !== "") {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi nhập liệu',
                html: errorMessage,
                confirmButtonText: 'Đã hiểu'
            });
            e.preventDefault(); // chặn submit
        }
    });
});
// edit
$(document).ready(function(){
    $('#product-edit-form').submit(function(e){
        let productId = $('input[name="ma_san_pham"]').val().trim();
        let productName = $('input[name="ten_san_pham"]').val().trim();
        let categpryId = $('select[name="ma_danh_muc"]').val();
        let image = $('input[name="hinh_anh"]').val().trim();
        let price = $('input[name="gia"]').val().trim();
        let description = $('textarea[name="mo_ta"]').val().trim();
        let status = $('select[name="trang_thai"]').val();

        let errorMessage = "";

        // Kiểm tra mã sản phẩm
        if(productId.length !== 10){
            errorMessage += "- Mã sản phẩm phải đủ 10 ký tự.<br>";
        }

        // Kiểm tra tên sản phẩm
        if(productName.length < 2){
            errorMessage += "- Tên sản phẩm ít nhất 2 ký tự.<br>";
        }
        if(productName.length > 255){
            errorMessage += "- Tên sản phẩm không vượt quá 255 ký tự.<br>";
        }
        // Kiểm tra danh mục
        if(!categpryId){
            errorMessage += "- Vui lòng chọn danh mục sản phẩm.<br>";
        }
        let priceNumber = parseFloat(price); // Ép kiểu về số
        if (!price) {
            errorMessage += "- Vui lòng nhập giá sản phẩm.<br>";
        } else {
            if (isNaN(priceNumber)) {
                errorMessage += "- Giá sản phẩm phải là số hợp lệ.<br>";
            } else {
                if (priceNumber < 1) {
                    errorMessage += "- Giá sản phẩm phải lớn hơn 0.<br>";
                }
                if (priceNumber > 10000000) {
                    errorMessage += "- Giá sản phẩm vượt mức quy định.<br>";
                }
            }
        }
        // Kiểm tra trạng thái
        if(!status){
            errorMessage += "- Vui lòng chọn trạng thái.<br>";
        }

        // Nếu có lỗi => hiện alert
        if(errorMessage != ""){
            Swal.fire({
                icon: 'error',
                title: 'Lỗi nhập liệu',
                html: errorMessage,
                confirmButtonText: 'Đã hiểu'
            });
            e.preventDefault(); // chặn submit
        }
    });
});
