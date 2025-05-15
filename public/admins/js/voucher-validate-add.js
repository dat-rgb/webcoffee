$(document).ready(function(){
    $('#voucher-form').submit(function(e){
        
        let ma_voucher =  $('input[name="ma_voucher"]').val().trim();
        let ten_voucher = $('input[name-"ten_voucher"]').val().trim();
        let hinh_anh = $('input[name="hinh_anh"]').val().trim();
        let gia_tri_giam = $('input[name="gia_tri_giam"]').val();
        let giam_gia_max = $('input[name="giam_gia_max"]').val();
        let so_luong = $('input[name="so_luong"]').val();
        let dieu_kien_ap_dung = $('input[name="dieu_kien_ap_dung"]').val();
        let ngay_bat_dau = $('input[name="ngay_bat_dau"]').val();
        let ngay_ket_thuc = $('input[name="ngay_ket_thuc"]').val();

        errorMessage = ""
        
        //Kiểm tra mã voucher
        if(ma_voucher.length > 50 ){
            errorMessage += "Mã voucher không quá 50 ký tự. <br>";
        }
        if(ma_voucher.length < 2 ){
            errorMessage += "Mã voucher ít nhất 2 ký tự. <br>";
        }
        if(/\s/.test(ma_voucher)){
            errorMessage += "Mã voucher không chứa khoảng trắng. <br>";
        }

        //kiểm tra tên voucher
        if(ten_voucher.length < 2){
            errorMessage += "Tên voucher ít nhất 2 ký tự. <br>";
        }
        if(ten_voucher.length > 255){
            errorMessage += "Tên voucher không quá 255 ký tự. <br>";
        }

        // kiểm tra hình ảnh
        if(hinh_anh.length > 255){
            errorMessage += "Tên file ảnh không quá 255 ký tự. <br>";
        }
        if(gia_tri_giam >= 0){
            errorMessage += "Giá trị giảm phải lớn hơn hoặc bằng 0. <br>";
        }
        if(giam_gia_max >= 0){
            errorMessage += "Giá trị giảm tối đa phải lớn hơn hoặc bằng 0. <br>";
        }
        if(so_luong >= 0){
            errorMessage += "Giá trị giảm phải lớn hơn hoặc bằng 0. <br>";
        }
        if(dieu_kien_ap_dung >= 0){
            errorMessage += "Điều kiện áp dụng phải lớn hơn hoặc bằng 0. <br>";
        }

        let startDate = new Date(ngay_bat_dau);
        let endDate = new Date(ngay_ket_thuc);

        // Kiểm tra ngày
        if (endDate < startDate) {
            errorMessage += "Ngày kết thúc không được trước ngày bắt đầu.<br>";
        }
        
        // Hiển thị lỗi nếu có
        if (errorMessage != "") {
            toastr.error(errorMessage, "Lỗi", {timeOut: 5000}); // Set thời gian hiển thị cho toastr
            e.preventDefault(); // Ngừng gửi form nếu có lỗi
        }
    });
})
