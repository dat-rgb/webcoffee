document.addEventListener("DOMContentLoaded", function () {
    const deliverySection = document.getElementById("deliverySection");
    const pickupSection = document.getElementById("pickupSection");
    const shippingMethods = document.querySelectorAll("input[name='shippingMethod']");

    shippingMethods.forEach(method => {
        method.addEventListener("change", () => {
            if (method.value === "delivery" && method.checked) {
                deliverySection.style.display = "block";
                pickupSection.style.display = "none";
            } else if (method.value === "pickup" && method.checked) {
                deliverySection.style.display = "none";
                pickupSection.style.display = "block";
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
  const provinceSelect = document.getElementById("provinceSelect");
  const districtSelect = document.getElementById("districtSelect");
  const wardSelect = document.getElementById("wardSelect");

  const provinceName = document.getElementById("provinceName");
  const districtName = document.getElementById("districtName");
  const wardName = document.getElementById("wardName");

  // Gắn cứng TP.HCM
  fetch("https://provinces.open-api.vn/api/p/79?depth=2") // 79 = TP.HCM
      .then(res => res.json())
      .then(data => {
          // Gán tên và mã tỉnh
          provinceSelect.innerHTML = `<option value="${data.code}" selected>${data.name}</option>`;
          provinceSelect.disabled = true; // Không cho đổi

          provinceName.value = data.name;

          // Load danh sách quận
          districtSelect.disabled = false;
          data.districts.forEach(d => {
              const opt = new Option(d.name, d.code);
              districtSelect.add(opt);
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

//Khi chọn quận/huyện, xã/phường sẽ tính lấy địa chỉ và tính shiping fee
//Lấy địa chỉ được lưu từ session 
//"selected_store_id" => "CH00000001"
//"selected_store_name" => "CDMT Coffee & Tea"
//"selected_store_dia_chi" => "72, đường 37, phường Tân Kiểng, Quận 7, TP Hồ Chí Minh"
//Nếu địa cách cửa hàng bán kính 5km shippingfee = 0 đ, hơn 5Km = 25.000 đ, hơn 10km = 50.000 đ.
//sau đó cập nhật ajax lên 
{/* <tbody class="checkout-details">
<tr>
    <td>Tạm tính: ({{ count(session('cart')) }} món)</td>
    <td>
        {{ number_format($total, 0, ',', '.') }} đ
    </td>
</tr>
<tr>
    <td>Shipping</td>
    <td> 0 đ</td>
</tr>
<tr>
    <td>Tổng cộng</td>
    <td>
        {{ number_format($total, 0, ',', '.') }} đ
    </td>
</tr>
</tbody> */}
