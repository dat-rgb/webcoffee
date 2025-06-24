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

function loadProvinces(selectId, selectedCode = '', hiddenInputId = '') {
  fetch("https://provinces.open-api.vn/api/p")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById(selectId);
      select.innerHTML = `<option disabled selected>Chọn tỉnh/thành</option>`;
      data.forEach(p => {
        const opt = new Option(p.name, p.code);
        if (p.code == selectedCode) {
          opt.selected = true;
          // Gán tên tỉnh vào input hidden
          if (hiddenInputId) document.getElementById(hiddenInputId).value = p.name;
        }
        select.add(opt);
      });
    });
}
function loadDistricts(selectId, provinceCode, selectedCode = '', hiddenInputId = '') {
  fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById(selectId);
      select.innerHTML = `<option disabled selected>Chọn quận/huyện</option>`;
      select.disabled = false;
      data.districts.forEach(d => {
        const opt = new Option(d.name, d.code);
        if (d.code == selectedCode) {
          opt.selected = true;
          if (hiddenInputId) document.getElementById(hiddenInputId).value = d.name;
        }
        select.add(opt);
      });
    });
}
function loadWards(selectId, districtCode, selectedCode = '', hiddenInputId = '') {
  fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById(selectId);
      select.innerHTML = `<option disabled selected>Chọn xã/phường</option>`;
      select.disabled = false;
      data.wards.forEach(w => {
        const opt = new Option(w.name, w.code);
        if (w.code == selectedCode) {
          opt.selected = true;
          if (hiddenInputId) document.getElementById(hiddenInputId).value = w.name;
        }
        select.add(opt);
      });
    });
}

$(document).on('click', '.btn-edit', function () {
  $('#editStoreId').val($(this).data('id'));
  $('#editMaCuaHang').val($(this).data('ma'));
  $('#editTenCuaHang').val($(this).data('ten'));
  $('#editSoDienThoai').val($(this).data('phone'));
  $('#editEmail').val($(this).data('email'));
  $('#editGioMoCua').val($(this).data('gio-mo'));
  $('#editGioDongCua').val($(this).data('gio-dong'));
  $('#editTrangThai').val($(this).data('trang-thai'));
  $('#editSoNha').val($(this).data('so-nha'));
  $('#editTenDuong').val($(this).data('ten-duong'));

  // Gọi load các select
  loadProvinces('editProvinceSelect', $(this).data('ma-tinh'), 'editProvinceName', $(this).data('ten-tinh'));
  loadDistricts('editDistrictSelect', $(this).data('ma-tinh'), $(this).data('ma-quan'), 'editDistrictName', $(this).data('ten-quan'));
  loadWards('editWardSelect', $(this).data('ma-quan'), $(this).data('ma-xa'), 'editWardName', $(this).data('ten-xa'));

  // Set action route
  const storeId = $(this).data('id');
  $('#editStoreForm').attr('action', `/admin/store/update/${storeId}`);

  // Show modal
  $('#editStoreModal').modal('show');
});


$(document).ready(function () {
  $('#addStoreForm').submit(function (e) {
      let tenCuaHang = $('input[name="ten_cua_hang"]').val().trim();
      let soDienThoai = $('input[name="so_dien_thoai"]').val().trim();
      let email = $('input[name="email"]').val().trim();
      let soNha = $('input[name="so_nha"]').val().trim();
      let tenDuong = $('input[name="ten_duong"]').val().trim();
      let gioMoCua = $('input[name="gio_mo_cua"]').val().trim();
      let gioDongCua = $('input[name="gio_dong_cua"]').val().trim();
      let province = $('#provinceSelect').val();
      let district = $('#districtSelect').val();
      let ward = $('#wardSelect').val();
      let trangThai = $('select[name="trang_thai"]').val();

      let errorMessage = "";

      if (tenCuaHang.length < 2 || tenCuaHang.length > 255) {
          errorMessage += "- Tên cửa hàng phải từ 2-255 ký tự.<br>";
      }

      let phoneRegex = /^0\d{9}$/;
      if (!soDienThoai || !phoneRegex.test(soDienThoai)) {
          errorMessage += "- Số điện thoại phải đúng định dạng 10 số, bắt đầu bằng 0.<br>";
      }

      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email || !emailRegex.test(email)) {
          errorMessage += "- Email không đúng định dạng.<br>";
      }

      if (!soNha || !tenDuong) {
          errorMessage += "- Vui lòng nhập đầy đủ số nhà và tên đường.<br>";
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

      if (!gioMoCua) {
          errorMessage += "- Vui lòng nhập giờ mở cửa.<br>";
      }
      if (!gioDongCua) {
          errorMessage += "- Vui lòng nhập giờ đóng cửa.<br>";
      }

      if (trangThai === null || trangThai === "") {
          errorMessage += "- Vui lòng chọn trạng thái hoạt động.<br>";
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
  $('#editStoreForm').submit(function (e) {
    let tenCuaHang = $('#editTenCuaHang').val().trim();
    let soDienThoai = $('#editSoDienThoai').val().trim();
    let email = $('#editEmail').val().trim();
    let soNha = $('#editSoNha').val().trim();
    let tenDuong = $('#editTenDuong').val().trim();
    let gioMoCua = $('#editGioMoCua').val().trim();
    let gioDongCua = $('#editGioDongCua').val().trim();
    let province = $('#editProvinceSelect').val();
    let district = $('#editDistrictSelect').val();
    let ward = $('#editWardSelect').val();
    let trangThai = $('#editTrangThai').val();

    let errorMessage = "";

    if (tenCuaHang.length < 2 || tenCuaHang.length > 255) {
      errorMessage += "- Tên cửa hàng phải từ 2-255 ký tự.<br>";
    }

    let phoneRegex = /^0\d{9}$/;
    if (!soDienThoai || !phoneRegex.test(soDienThoai)) {
      errorMessage += "- Số điện thoại phải đúng định dạng 10 số, bắt đầu bằng 0.<br>";
    }

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailRegex.test(email)) {
      errorMessage += "- Email không đúng định dạng.<br>";
    }

    if (!soNha || !tenDuong) {
      errorMessage += "- Vui lòng nhập đầy đủ số nhà và tên đường.<br>";
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

    if (!gioMoCua) {
      errorMessage += "- Vui lòng nhập giờ mở cửa.<br>";
    }
    if (!gioDongCua) {
      errorMessage += "- Vui lòng nhập giờ đóng cửa.<br>";
    }

    if (trangThai === null || trangThai === "") {
      errorMessage += "- Vui lòng chọn trạng thái hoạt động.<br>";
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
