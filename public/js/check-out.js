// Lấy tỉnh/thành
fetch('https://provinces.open-api.vn/api/p/')
  .then(res => res.json())
  .then(data => {
    const provinceSelect = document.getElementById('provinceSelect');
    data.forEach(province => {
      const option = document.createElement('option');
      option.value = province.code;
      option.textContent = province.name;
      provinceSelect.appendChild(option);
    });
  });

// Khi chọn tỉnh/thành, lấy quận/huyện tương ứng
document.getElementById('provinceSelect').addEventListener('change', function() {
  const provinceCode = this.value;
  const districtSelect = document.getElementById('districtSelect');
  const wardSelect = document.getElementById('wardSelect');
  
  // Reset quận và xã, disable xã luôn
  districtSelect.innerHTML = '<option value="" selected>Chọn quận/huyện</option>';
  districtSelect.disabled = true;
  wardSelect.innerHTML = '<option value="" selected>Chọn xã/phường</option>';
  wardSelect.disabled = true;
  
  if (!provinceCode) return; // không chọn gì thì dừng
  
  fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
    .then(res => res.json())
    .then(data => {
      data.districts.forEach(district => {
        const option = document.createElement('option');
        option.value = district.code;
        option.textContent = district.name;
        districtSelect.appendChild(option);
      });
      districtSelect.disabled = false;
    });
});

// Khi chọn quận/huyện, lấy xã/phường tương ứng
document.getElementById('districtSelect').addEventListener('change', function() {
  const districtCode = this.value;
  const wardSelect = document.getElementById('wardSelect');
  
  // Reset xã/phường
  wardSelect.innerHTML = '<option value="" selected>Chọn xã/phường</option>';
  wardSelect.disabled = true;
  
  if (!districtCode) return; // không chọn gì thì dừng
  
  fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
    .then(res => res.json())
    .then(data => {
      data.wards.forEach(ward => {
        const option = document.createElement('option');
        option.value = ward.code;
        option.textContent = ward.name;
        wardSelect.appendChild(option);
      });
      wardSelect.disabled = false;
    });
});

