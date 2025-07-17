$(document).ready(function () {
    $('#admin-setting-form').submit(function (e) {
        let errorMessage = "";

        let phiShip = $('#phi_ship').val().trim();
        let nguongMienPhiShip = $('#nguong_mien_phi_ship').val().trim();
        let vatMacDinh = $('#vat_mac_dinh').val().trim();
        let soLuongToiThieu = $('#so_luong_toi_thieu').val().trim();
        let soLuongToiDa = $('#so_luong_toi_da').val().trim();
        let tyLeDiemThuong = $('#ty_le_diem_thuong').val().trim();
        let banKinhGiaoHang = $('#ban_kinh_giao_hang').val().trim();
        let banKinhHienThi = $('#ban_kinh_hien_thi_cua_hang').val().trim();

        // Validate required fields
        if (phiShip === "" || isNaN(phiShip) || parseFloat(phiShip) < 0) {
            errorMessage += "Phí ship không được để trống hoặc nhỏ hơn 0.<br>";
        }

        if (nguongMienPhiShip === "" || isNaN(nguongMienPhiShip) || parseFloat(nguongMienPhiShip) < 0) {
            errorMessage += "Ngưỡng miễn phí ship không được để trống hoặc nhỏ hơn 0.<br>";
        }

        if (vatMacDinh === "" || isNaN(vatMacDinh) || parseFloat(vatMacDinh) < 0 || parseFloat(vatMacDinh) > 100) {
            errorMessage += "VAT mặc định phải từ 0 đến 100.<br>";
        }

        if (soLuongToiThieu === "" || isNaN(soLuongToiThieu) || parseInt(soLuongToiThieu) < 1) {
            errorMessage += "Số lượng tối thiểu phải lớn hơn hoặc bằng 1.<br>";
        }

        if (soLuongToiDa === "" || isNaN(soLuongToiDa) || parseInt(soLuongToiDa) < 1) {
            errorMessage += "Số lượng tối đa phải lớn hơn hoặc bằng 1.<br>";
        }

        if (tyLeDiemThuong === "" || isNaN(tyLeDiemThuong) || parseFloat(tyLeDiemThuong) < 1) {
            errorMessage += "Tỷ lệ điểm thưởng phải lớn hơn hoặc bằng 1.<br>";
        }

        if (banKinhGiaoHang === "" || isNaN(banKinhGiaoHang) || parseFloat(banKinhGiaoHang) < 0) {
            errorMessage += "Bán kính giao hàng không được để trống hoặc nhỏ hơn 0.<br>";
        }

        if (banKinhHienThi === "" || isNaN(banKinhHienThi) || parseFloat(banKinhHienThi) < 0) {
            errorMessage += "Bán kính hiển thị cửa hàng không được để trống hoặc nhỏ hơn 0.<br>";
        }

        // So sánh số lượng tối thiểu và tối đa
        if (
            soLuongToiThieu !== "" &&
            soLuongToiDa !== "" &&
            !isNaN(soLuongToiThieu) &&
            !isNaN(soLuongToiDa) &&
            parseInt(soLuongToiThieu) > parseInt(soLuongToiDa)
        ) {
            errorMessage += "Số lượng tối thiểu không được lớn hơn số lượng tối đa.<br>";
        }

        // Hiển thị lỗi nếu có
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
