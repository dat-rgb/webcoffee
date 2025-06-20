<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập hệ thống - {{ $thongTinWebsite['ten_website'] }}</title>
  <!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('images/'.$thongTinWebsite['favicon']) }}">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }
    .background-radial-gradient {
      background-color: hsl(218, 41%, 15%);
      background-image: radial-gradient(650px circle at 0% 0%,
          hsl(218, 41%, 35%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%),
        radial-gradient(1250px circle at 100% 100%,
          hsl(218, 41%, 45%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%);
    }
    #radius-shape-1,
    #radius-shape-2 {
      position: absolute;
      overflow: hidden;
    }
    #radius-shape-1 {
      height: 220px;
      width: 220px;
      top: -60px;
      left: -130px;
      background: radial-gradient(#44006b, #ad1fff);
      border-radius: 50%;
    }
    #radius-shape-2 {
      bottom: -60px;
      right: -110px;
      width: 300px;
      height: 300px;
      background: radial-gradient(#44006b, #ad1fff);
      border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
    }
    .bg-glass {
      background-color: rgba(255, 255, 255, 0.9);
      backdrop-filter: saturate(200%) blur(25px);
      border-radius: 1rem;
    }
  </style>
</head>
<body>
  <!-- Section -->
  <section class="background-radial-gradient overflow-hidden vh-100 d-flex align-items-center">
    <div class="container px-4 py-5 text-center text-lg-start">
      <div class="row align-items-center">
        <div class="col-lg-6 text-white">
          <h1 class="display-5 fw-bold mb-4">Đăng nhập<br><span class="text-info">Hệ thống quản lý cửa hàng CDMT Coffee & Tea</span></h1>
        </div>
        <div class="col-lg-6 position-relative">
          <div id="radius-shape-1" class="shadow-lg"></div>
          <div id="radius-shape-2" class="shadow-lg"></div>
            <div class="card bg-glass">
            <div class="card-body px-5 py-5" style="height: 380px;">
                    <h3 class="text-center mb-4 fw-bold text-primary">Đăng nhập</h3>
                    <form method="POST" action="{{ route('admin.login') }}" id="login-form">
                        @csrf
                        <div class="mb-4">
                            <input  type="email"  id="email"  name="email"  class="form-control form-control-lg"  placeholder="Email"  required/>
                        </div>
                        <div class="mb-4 position-relative">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Mật khẩu" required />
                            <span id="togglePassword" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;">
                                <i class="fa-solid fa-eye-slash"></i>
                            </span>
                        </div>
                        <button type="submit"  class="btn btn-primary w-100 btn-lg">Đăng nhập</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/form-validate.js') }}"></script>
  <script>
      document.getElementById('togglePassword').addEventListener('click', function () {
          const passwordInput = document.getElementById('password');
          const icon = this.querySelector('i');

          const isPassword = passwordInput.getAttribute('type') === 'password';
          passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
      });
  </script>
</body>
</html>
