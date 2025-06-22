$(document).ready(function () {
    $('#blog-form').submit(function (e) {
        let tieuDe = $('input[name="tieu_de"]').val().trim();
        let subTieuDe = $('input[name="sub_tieu_de"]').val().trim();
        let noiDung = $('textarea[name="noi_dung"]').val().trim();
        let tacGia = $('input[name="tac_gia"]').val().trim();
        let maDanhMuc = $('select[name="ma_danh_muc"]').val();
        let trangThai = $('select[name="trang_thai"]').val();

        let errorMessage = "";

        // Tiêu đề
        if (tieuDe.length < 2) {
            errorMessage += "- Tiêu đề phải có ít nhất 2 ký tự.<br>";
        }
        if (tieuDe.length > 255) {
            errorMessage += "- Tiêu đề không được vượt quá 255 ký tự.<br>";
        }

        // Sub tiêu đề (nội dung ngắn)
        if (subTieuDe.length > 255) {
            errorMessage += "- Nội dung ngắn không được vượt quá 255 ký tự.<br>";
        }

        // Nội dung chính
        if (noiDung.length < 10) {
            errorMessage += "- Nội dung phải có ít nhất 10 ký tự.<br>";
        }

        // Tác giả
        if (tacGia.length < 2) {
            errorMessage += "- Tác giả phải có ít nhất 2 ký tự.<br>";
        }

        // Danh mục
        if (!maDanhMuc) {
            errorMessage += "- Vui lòng chọn danh mục Blog.<br>";
        }

        // Trạng thái
        if (!trangThai) {
            errorMessage += "- Vui lòng chọn trạng thái hiển thị.<br>";
        }

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
$(document).ready(function () {
    $('#blog-form-edit').submit(function (e) {
        let tieuDe = $('input[name="tieu_de"]').val().trim();
        let subTieuDe = $('input[name="sub_tieu_de"]').val().trim();
        let noiDung = $('textarea[name="noi_dung"]').val().trim();
        let tacGia = $('input[name="tac_gia"]').val().trim();
        let maDanhMuc = $('select[name="ma_danh_muc"]').val();
        let trangThai = $('select[name="trang_thai"]').val();

        let errorMessage = "";

        if (tieuDe.length < 2) {
            errorMessage += "- Tiêu đề phải có ít nhất 2 ký tự.<br>";
        }
        if (tieuDe.length > 255) {
            errorMessage += "- Tiêu đề không được vượt quá 255 ký tự.<br>";
        }

        if (subTieuDe.length > 255) {
            errorMessage += "- Nội dung ngắn không được vượt quá 255 ký tự.<br>";
        }

        if (noiDung.length < 10) {
            errorMessage += "- Nội dung phải có ít nhất 10 ký tự.<br>";
        }

        if (tacGia.length < 2) {
            errorMessage += "- Tác giả phải có ít nhất 2 ký tự.<br>";
        }

        if (!maDanhMuc) {
            errorMessage += "- Vui lòng chọn danh mục Blog.<br>";
        }

        if (!trangThai) {
            errorMessage += "- Vui lòng chọn trạng thái hiển thị.<br>";
        }

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
