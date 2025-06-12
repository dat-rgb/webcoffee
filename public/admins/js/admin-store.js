document.addEventListener("DOMContentLoaded", () => {
    const provinceSelect = document.getElementById("provinceSelect");
    const districtSelect = document.getElementById("districtSelect");
    const wardSelect = document.getElementById("wardSelect");
  
    const provinceName = document.getElementById("provinceName");
    const districtName = document.getElementById("districtName");
    const wardName = document.getElementById("wardName");
  
    // Load tỉnh/thành phố
    fetch("https://provinces.open-api.vn/api/p")
      .then(res => res.json())
      .then(data => {
        provinceSelect.innerHTML = `<option selected disabled>Chọn tỉnh/thành</option>`;
        data.forEach(p => {
          const opt = new Option(p.name, p.code);
          provinceSelect.add(opt);
        });
      });
  
    // Khi chọn tỉnh → load quận/huyện
    provinceSelect.addEventListener("change", () => {
      const code = provinceSelect.value;
      provinceName.value = provinceSelect.options[provinceSelect.selectedIndex].text;
  
      districtSelect.innerHTML = '<option selected>Chọn quận/huyện</option>';
      wardSelect.innerHTML = '<option selected>Chọn xã/phường</option>';
      wardSelect.disabled = true;
  
      if (!code) return;
  
      fetch(`https://provinces.open-api.vn/api/p/${code}?depth=2`)
        .then(res => res.json())
        .then(data => {
          districtSelect.disabled = false;
          data.districts.forEach(d => {
            const opt = new Option(d.name, d.code);
            districtSelect.add(opt);
          });
        });
    });
  
    // Khi chọn quận → load xã
    districtSelect.addEventListener("change", () => {
      const code = districtSelect.value;
      districtName.value = districtSelect.options[districtSelect.selectedIndex].text;
  
      wardSelect.innerHTML = '<option selected>Chọn xã/phường</option>';
  
      if (!code) {
        wardSelect.disabled = true;
        return;
      }
  
      fetch(`https://provinces.open-api.vn/api/d/${code}?depth=2`)
        .then(res => res.json())
        .then(data => {
          wardSelect.disabled = false;
          data.wards.forEach(w => {
            const opt = new Option(w.name, w.code);
            wardSelect.add(opt);
          });
        });
    });
  
    // Gán tên xã vào input hidden
    wardSelect.addEventListener("change", () => {
      wardName.value = wardSelect.options[wardSelect.selectedIndex].text;
    });
});

$(document).ready(function () {
    $('#addStoreForm').submit(function (e) {
        let tenCuaHang = $('input[name="ten_cua_hang"]').val().trim();
        let soDienThoai = $('input[name="so_dien_thoai"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let diaChi = $('input[name="dia_chi"]').val().trim();
        let gioMoCua = $('input[name="gio_mo_cua"]').val().trim();
        let gioDongCua = $('input[name="gio_dong_cua"]').val().trim();
        let province = $('#provinceSelect').val();
        let district = $('#districtSelect').val();
        let ward = $('#wardSelect').val();

        let errorMessage = "";

        if (tenCuaHang.length < 2) {
            errorMessage += "- Tên cửa hàng ít nhất 2 ký tự.<br>";
        }
        if (tenCuaHang.length > 255) {
            errorMessage += "- Tên cửa hàng không vượt quá 255 ký tự.<br>";
        }

        let phoneRegex = /^0[0-9]{9}$/;
        if (!soDienThoai) {
            errorMessage += "- Số điện thoại không được để trống.<br>";
        } else if (!phoneRegex.test(soDienThoai)) {
            errorMessage += "- Số điện thoại phải đúng 10 chữ số và bắt đầu bằng số 0.<br>";
        }        

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            errorMessage += "- Email không được để trống.<br>";
        } else if (!emailRegex.test(email)) {
            errorMessage += "- Email không đúng định dạng.<br>";
        }

        if (!diaChi) {
            errorMessage += "- Địa chỉ không được để trống.<br>";
        }

        if (!province) {
            errorMessage += "- Vui lòng chọn tỉnh/thành phố.<br>";
        }
        if (!district) {
            errorMessage += "- Vui lòng chọn quận/huyện.<br>";
        }
        if (!ward) {
            errorMessage += "- Vui lòng chọn phường/xã.<br>";
        }

        if(!gioMoCua){
            errorMessage += "- Vui lòng nhập giờ mở cửa<br>";
        }
        if(!gioDongCua){
            errorMessage += "- Vui lòng nhập giờ mở cửa<br>";
        }

        if (errorMessage != "") {
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
