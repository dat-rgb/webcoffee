$(document).ready(function(){
    $('#product-form').submit(function(e){
        let productId = $('input[name="ma_san_pham"]').val().trim();
        let productName= $('input[name="ten_san_pham"]').val().trim();
        let categpryId = $('select[name="ma_danh_muc"]').val();
        let image = $('input[name="hinh_anh"]').val().trim();
        let price = $('input[name="gia"]').val().trim();
        let description  = $('textarea[name="mo_ta"]').val().trim();

        let errorMessage = "";

        //Kiểm tra mã sản phẩm
        if(productId.length < 10 || productId.length > 10){
            errorMessage += "Mã sản phẩm phải đủ 10 ký tự. <br>";
        }
        //Kiểm tra tên sản phẩm
        if(productName.length < 2){
            errorMessage += "Tên sản phẩm ít nhất 2 ký tự. <br>";
        }
        if(productName.length > 255){
            errorMessage += "Tên sản phẩm không vượt quá 255 ký tự. <br>";
        }

        //Kiểm tra danh mục
        if(categpryId == 0){
            errorMessage += "Vui lòng chọn danh mục sản phẩm. <br>";
        }

        // Hiển thị lỗi nếu có
        if (errorMessage != "") {
            toastr.error(errorMessage, "Lỗi", {timeOut: 5000}); // Set thời gian hiển thị cho toastr
            e.preventDefault(); // Ngừng gửi form nếu có lỗi
        }
    });
})
