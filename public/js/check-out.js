document.addEventListener("DOMContentLoaded", function () {
  const deliverySection = document.getElementById("deliverySection");
  const pickupSection = document.getElementById("pickupSection");
  const shippingMethods = document.querySelectorAll("input[name='shippingMethod']");
  const shippingFeeText = document.getElementById("shippingFeeText");
  const shippingFeeInput = document.getElementById("shippingFeeInput");

  const originalFee = parseInt(shippingFeeInput.dataset.originalFee); 

  shippingMethods.forEach(method => {
    method.addEventListener("change", () => {
      if (method.value === "delivery" && method.checked) {
        deliverySection.style.display = "block";
        pickupSection.style.display = "none";
        shippingFeeText.innerText = formatCurrency(originalFee);
        shippingFeeInput.value = originalFee;
      } else if (method.value === "pickup" && method.checked) {
        deliverySection.style.display = "none";
        pickupSection.style.display = "block";
        shippingFeeText.innerText = "0 đ";
        shippingFeeInput.value = 0;
      }

      updateTotal(); 
    });
  });

  function updateTotal() {
    const subtotal = parseInt(document.getElementById("subtotal").innerText.replace(/\D/g, ""));
    const shippingFee = parseInt(shippingFeeInput.value);
    const discount = parseInt(document.getElementById("discount").innerText.replace(/\D/g, ""));
    const total = subtotal + shippingFee - discount;
    document.getElementById("total").innerText = formatCurrency(total);
  }

  function formatCurrency(number) {
    return number.toLocaleString('vi-VN') + ' đ';
  }
});


document.addEventListener("DOMContentLoaded", () => {
  const provinceSelect = document.getElementById("provinceSelect");
  const districtSelect = document.getElementById("districtSelect");
  const wardSelect = document.getElementById("wardSelect");

  const provinceName = document.getElementById("provinceName");
  const districtName = document.getElementById("districtName");
  const wardName = document.getElementById("wardName");

  // Load danh sách tỉnh/thành phố
  fetch("https://provinces.open-api.vn/api/?depth=1")
    .then(res => res.json())
    .then(data => {
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
    districtSelect.disabled = true;
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

  // Khi chọn quận/huyện → load xã/phường
  districtSelect.addEventListener("change", () => {
    const code = districtSelect.value;
    districtName.value = districtSelect.options[districtSelect.selectedIndex].text;

    wardSelect.innerHTML = '<option selected>Chọn xã/phường</option>';
    wardSelect.disabled = true;

    if (!code) return;

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

  // Gán tên xã/phường vào input hidden
  wardSelect.addEventListener("change", () => {
    wardName.value = wardSelect.options[wardSelect.selectedIndex].text;
  });
});
$(document).ready(function(){
  $('#check-out-form').submit(function (e) { 
    e.preventDefault(); 

    let ho_ten_khach_hang = $('input[name="ho_ten_khach_hang"]').val().trim();
    let email = $('input[name="email"]').val().trim();
    let so_nha = $('input[name="so_nha"]').val().trim();
    let ten_duong = $('input[name="ten_duong"]').val().trim();
    let so_dien_thoai = $('input[name="so_dien_thoai"]').val().trim();
    let shippingMethod = $('input[name="shippingMethod"]:checked').val();
    let province = $('input[name="provinceName"]').val().trim();
    let district = $('input[name="districtName"]').val().trim();
    let ward = $('input[name="wardName"]').val().trim();
    let paymentMethod = $('input[name="paymentMethod"]:checked').val();

    let errorMessage = "";

    if(ho_ten_khach_hang === ""){
      errorMessage += "- Họ tên không được để trống.<br>";
    }

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email === "" || !emailRegex.test(email)){
      errorMessage += "- Email không hợp lệ hoặc để trống.<br>";
    }

    let phoneRegex = /^[0-9]{9,11}$/;
    if(!phoneRegex.test(so_dien_thoai)){
      errorMessage += "- Số điện thoại không hợp lệ.<br>";
    }

    if(shippingMethod === "delivery"){
      if(so_nha === "" || ten_duong === ""){
        errorMessage += "- Vui lòng nhập đầy đủ Số nhà và Tên đường.<br>";
      }
      if(province === "" || district === "" || ward === ""){
        errorMessage += "- Vui lòng chọn Tỉnh, Quận và Phường.<br>";
      }
    }

    if(!paymentMethod){
      errorMessage += "- Vui lòng chọn phương thức thanh toán.<br>";
    }

    if(errorMessage !== ""){
      Swal.fire({
        title: 'Thiếu thông tin!',
        html: errorMessage,
        icon: 'warning',
        confirmButtonText: 'OK'
      });
      return;
    }

    Swal.fire({
      title: 'Xác nhận đặt hàng?',
      text: 'Bạn có chắc chắn muốn đặt đơn hàng này?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Đặt hàng',
      cancelButtonText: 'Hủy'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Đang xử lý...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        setTimeout(() => {
          e.target.submit();
        }, 800);
      }
    });
  });
});


$(document).ready(function () {
  $(document).on('change', '.voucher-checkbox', function () {
    // ❌ Bỏ hết các checkbox khác
    $('.voucher-checkbox').not(this).prop('checked', false);

    const isChecked = $(this).is(':checked');

    let subtotal = parseFloat($('#subtotal').text().replace(/\./g, '').replace(' đ', ''));
    let shippingFee = parseFloat($('#shippingFeeText').text().replace(/\./g, '').replace(' đ', ''));

    function formatCurrency(num) {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ";
    }

    if (!isChecked) {
      // ❌ Nếu bỏ chọn → reset giá
      $('#discount').text("0 đ");
      $('#total').text(formatCurrency(subtotal + shippingFee));
      return;
    }

    const giaTriGiam = parseFloat($(this).data('gia-tri-giam'));
    const giamGiaMax = parseFloat($(this).data('giam-gia-max'));
    const dieuKien = parseFloat($(this).data('dieu-kien'));

    if (subtotal < dieuKien) {
      Swal.fire({
        icon: 'warning',
        title: 'Không thể áp dụng voucher!',
        text: 'Đơn hàng chưa đủ điều kiện để sử dụng voucher này.',
      });
      $(this).prop('checked', false);
      $('#discount').text("0 đ");
      $('#total').text(formatCurrency(subtotal + shippingFee));
      return;
    }

    let discount = 0;
    if (giaTriGiam <= 100) {
      discount = subtotal * (giaTriGiam / 100);
      if (discount > giamGiaMax) discount = giamGiaMax;
    } else {
      discount = giaTriGiam;
    }

    let total = subtotal + shippingFee - discount;
    if (total < 0) total = 0;

    $('#discount').text(formatCurrency(discount));
    $('#total').text(formatCurrency(total));
  });
});







  
  
  