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
    background-color: #012738;
  }

  .background-radial-gradient {
    background: linear-gradient(to right, #012738, #02374a);
  }

  #radius-shape-1,
  #radius-shape-2 {
    position: absolute;
    overflow: hidden;
  }

  #radius-shape-1 {
    height: 200px;
    width: 200px;
    top: -60px;
    left: -130px;
    background: radial-gradient(#F28123, #ffb066);
    border-radius: 50%;
    opacity: 0.8;
  }

  #radius-shape-2 {
    bottom: -60px;
    right: -110px;
    width: 280px;
    height: 280px;
    background: radial-gradient(#0dcaf0, #012738);
    border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
    opacity: 0.6;
  }


  .bg-glass {
    background-color: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(18px) saturate(160%);
    border-radius: 1rem;
    box-shadow: 0 0 30px rgba(1, 39, 56, 0.2);
  }

  .form-control {
    background: #fefefe !important;
    color: #012738;
  }

  .btn-primary-custom {
    background-color: #012738;
    color: #fff;
  }

  .btn-warning-custom {
    background-color: #F28123;
    color: #fff;
  }

  .btn-warning-custom:hover {
    background-color: #da731c;
  }

  .text-highlight {
    color: #F28123;
  }

  a.text-highlight:hover {
    color: #da731c;
  }

  .form-control:focus {
    border-color: #F28123;
    box-shadow: 0 0 0 0.2rem rgba(242, 129, 35, 0.25);
  }
</style>

</head>
<body>

  <!-- Section -->
  <section class="background-radial-gradient overflow-hidden vh-100 d-flex align-items-center">
    <div class="container px-4 py-5 text-center text-lg-start">
      <div class="row align-items-center">
        <div class="col-lg-6 text-white">
          <h1 class="display-5 fw-bold mb-4">Đăng nhập<br>
            <span class="text-info">Hệ thống quản lý cửa hàng CDMT Coffee & Tea</span>
          </h1>
        </div>
        <div class="col-lg-6 position-relative">
          <div id="radius-shape-1" class="shadow-lg"></div>
          <div id="radius-shape-2" class="shadow-lg"></div>

          <div class="card bg-glass">
            <div class="card-body px-5 py-5" style="height: auto;">

              <div id="login-form-section">
                <h3 class="text-center mb-4 fw-bold" style="color:#012738;">Đăng nhập</h3>

                <form method="POST" action="{{ route('admin.login') }}">
                  @csrf
                  <!-- Email -->
                  <div class="mb-3">
                    <input type="email"
                          name="email"
                          class="form-control form-control-lg rounded-3 shadow-sm border-0"
                          placeholder="Email"
                          style="background:#fefefe;"
                          required>
                  </div>

                  <!-- Password -->
                  <div class="mb-3 position-relative">
                    <input type="password"
                          id="password"
                          name="password"
                          class="form-control form-control-lg rounded-3 shadow-sm border-0"
                          placeholder="Mật khẩu"
                          style="background:#fefefe;"
                          required>

                    <button type="button"
                            id="togglePassword"
                            class="btn btn-sm border-0 bg-transparent"
                            style="position:absolute;top:50%;right:14px;transform:translateY(-50%);">
                      <i class="fa-solid fa-eye-slash text-secondary"></i>
                    </button>
                  </div>

                  <!-- Forgot link -->
                  <div class="d-flex justify-content-end mb-3">
                    <a href="#" id="forgot-password-link"
                      class="text-decoration-none fw-semibold"
                      style="color:#F28123;">Quên mật khẩu?</a>
                  </div>

                  <!-- Submit -->
                  <button type="submit"
                          class="btn w-100 btn-lg fw-bold text-white border-0"
                          style="background:#012738;">
                    Đăng nhập
                  </button>
                </form>
              </div>

              <div id="forgot-password-form-section" class="d-none">
                <h3 class="text-center mb-4 fw-bold" style="color:#F28123;">Quên mật khẩu</h3>

                <form method="POST" action="{{ route('forgotPassword.send') }}">
                  @csrf
                  <div class="mb-3">
                    <input type="email"
                          name="email"
                          class="form-control form-control-lg rounded-3 shadow-sm border-0"
                          placeholder="Nhập email khôi phục"
                          style="background:#fefefe;"
                          required>
                  </div>

                  <div class="d-flex justify-content-start mb-3">
                    <a href="#" id="back-to-login-link"
                      class="text-decoration-none"
                      style="color:#012738;">← Quay lại đăng nhập</a>
                  </div>

                  <button type="submit"
                          class="btn w-100 btn-lg fw-bold text-white border-0"
                          style="background:#F28123;">
                    Gửi liên kết đặt lại
                  </button>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const togglePasswordBtn = document.getElementById("togglePassword");
      const passwordInput = document.getElementById("password");

      togglePasswordBtn.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.querySelector("i").classList.toggle("fa-eye");
        this.querySelector("i").classList.toggle("fa-eye-slash");
      });

      const loginForm = document.getElementById("login-form-section");
      const forgotForm = document.getElementById("forgot-password-form-section");

      document.getElementById("forgot-password-link").addEventListener("click", function (e) {
        e.preventDefault();
        loginForm.classList.add("d-none");
        forgotForm.classList.remove("d-none");
      });

      document.getElementById("back-to-login-link").addEventListener("click", function (e) {
        e.preventDefault();
        forgotForm.classList.add("d-none");
        loginForm.classList.remove("d-none");
      });
    });
  </script>

</body>
</html>
