<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="CDMT Coffee & Tea mang đến trải nghiệm cà phê và trà hiện đại, tươi mới. Thực đơn đa dạng, không gian đẹp, phù hợp làm việc, hẹn hò và thư giãn.">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- title -->
	<title>@yield('title', 'CDMT Coffee & Tea')</title>
	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600&family=Roboto+Slab:wght@600&display=swap" rel="stylesheet">
	<!-- boostap icon -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<!-- toastr CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	<!-- fontawesome -->
	<link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
	<!-- bootstrap -->
	<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
	<!-- owl carousel -->
	<link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
	<!-- magnific popup -->
	<link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
	<!-- animate css -->
	<link rel="stylesheet" href="{{ asset('css/animate.css') }}">
	<!-- mean menu css -->
	<link rel="stylesheet" href="{{ asset('css/meanmenu.min.css') }}">
	<!-- main style -->
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<!-- responsive -->
	<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
	<!-- store -->
	<link rel="stylesheet" href="{{ asset('css/store-popup.css') }}">
	@stack('styles')
</head>
<body>
	
	<!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="{{ route('home') }}">
								<img src="{{ asset('img/logo.png') }}" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
								<li><a href="{{ route('product') }}">Sản Phẩm</a>
									<ul class="sub-menu">
										@foreach ($danhMucCha as $dm)
											@if ($dm->totalProductsCount > 0)
												<li><a href="{{ route('product.category.list',$dm->slug) }}">{{ $dm->ten_danh_muc }}</a></li>
											@endif
										@endforeach
									</ul>
								</li>
								<li><a href="{{ route('blog') }}">Tin Tức</a>
									<ul class="sub-menu">
										<li><a href="{{ route('blog') }}">Coffee</a></li>
										<li><a href="{{ route('blog') }}">Chuyện Trà</a></li>
									</ul>
								</li>
								<li><a href="{{ route('about') }}">Giới thiệu</a></li>
								<li><a href="{{ route('contact') }}">Liên Hệ</a></li>	
								<li>
									<a href="#" id="store-btn" onclick="openStoreModal()">
										<i class="fas fa-store-alt"></i>
										<span>{{ session('selected_store_name') ?? 'Cửa hàng' }}</span>
									</a>
								</li>
								{{-- Tách user icon ra khỏi header-icons --}}
								@if(Auth::check())
									<li>
										<a href="{{ route('customer.index') }}">
											<i class="fas fa-user"></i> {{ Auth::user()->khachHang->ho_ten_khach_hang ?? 'Khách hàng' }}
										</a>
										<ul class="sub-menu">
											<li><a href="{{ route('customer.index') }}"><i class="fas fa-user-circle"></i> Hồ sơ</a></li>
											<li><a href="{{ route('traCuuDonHang.show') }}"><i class="fas fa-search"></i> Tra cứu đơn hàng</a></li>
											<li><a href="{{ route('favorite.show') }}"><i class="fas fa-heart"></i> Yêu thích</a></li>
											<li><a href="{{ route('customer.order.history') }}"><i class="fas fa-receipt"></i> Lịch sử mua hàng</a></li>
											<li><a href="#"><i class="fas fa-eye"></i> Sản phẩm đã xem</a></li>
											<li><a href="{{ route('forgotPassword.show') }}"><i class="fas fa-unlock-alt me-2"></i> Lấy lại mật khẩu</a></li>
											<li>
												<a href="#" id="logout-btn">
													<i class="fas fa-sign-out-alt"></i> Đăng xuất
												</a>
												<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
													@csrf
												</form>
											</li>
										</ul>
									</li>
								@else
									<li>
										<a class="login" href="{{ route('login') }}"><i class="fas fa-user"></i></a>
										<ul class="sub-menu">
											<li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i> Đăng nhập</a></li>
											<li><a href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i> Đăng ký</a></li>
											<li><a href="{{ route('traCuuDonHang.show') }}"><i class="fas fa-search me-2"></i> Tra cứu đơn hàng</a></li>
										</ul>
									</li>
								@endif	
								<li>
									<div class="header-icons">
										<a class="shopping-cart" href="{{ route('cart') }}">
											<i class="fas fa-shopping-cart"></i>
											<span class="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
										</a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
									</div>
								</li>
							</ul>
						</nav>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header --> 

	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<form action="{{ route('product.search') }}" method="GET" class="search-form">
								<h3>Tìm kiếm</h3>
								<input type="text" name="search" placeholder="Nhập từ khóa..." value="{{ request('search') }}" required>
								<button type="submit">
									Tìm kiếm <i class="fas fa-search"></i>
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search area -->
	 
	@yield('content')

	<!-- footer -->
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">Về chúng tôi</h2>
						<p>CDMT Coffee & Tea là điểm đến của những tâm hồn yêu chill. Từ cà phê đậm vị đến trà trái cây siêu fresh – tụi mình luôn mang đến năng lượng tích cực trong từng ly nước!</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Liên hiện</h2>
						<ul>
							<li>65, Huỳnh Thúc Kháng, Bến Nghé, Quận 1, Thành Phố Hồ Chí Minh</li>
							<li>cdmtcoffeetea.com</li>
							<li>+84 901 318 766</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box pages">
						<h2 class="widget-title">Khám phá</h2>
						<ul>
							<li><a href="{{ route('home') }}">Trang Chủ</a></li>
							<li><a href="{{ route('about')}}">Giới Thiệu</a></li>
							<li><a href="{{ route('product') }}">Sản Phẩm</a></li>
							<li><a href="{{ route('blog') }}">Tin Tức</a></li>
							<li><a href="{{ route('contact') }}">Liên Hệ</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box subscribe">
						<h2 class="widget-title">Đăng ký nhận tin</h2>
						<p>Đăng ký email để không bỏ lỡ các ưu đãi và thức uống mới từ CDMT Coffee & Tea.</p>
						<form action="#">
							<input type="email" placeholder="Nhập email của bạn">
							<button type="submit"><i class="fas fa-paper-plane"></i></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end footer -->
	
	<!-- copyright -->
	<div class="copyright">
		<div class="container">	
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<p>Copyrights &copy; 2025 - Đồ án tốt nghiệp webiste thương mại điện tử trường <a href="https://caothang.edu.vn/">Cao đăng kỹ thuật Cao Thắng</a>.<br>
						Sinh viên - Chí Đạt & Minh Tân
					</p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end copyright -->
	 
	<!-- jquery -->
	<script src="{{ asset('js/jquery-1.11.3.min.js') }}"></script>	
	<!-- toastr JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
	<!-- bootstrap -->
	<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
	<!-- count down -->
	<script src="{{ asset('js/jquery.countdown.js') }}"></script>
	<!-- isotope -->
	<script src="{{ asset('js/jquery.isotope-3.0.6.min.js') }}"></script>
	<!-- waypoints -->
	<script src="{{ asset('js/waypoints.js') }}"></script>
	<!-- owl carousel -->
	<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
	<!-- magnific popup -->
	<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
	<!-- mean menu -->
	<script src="{{ asset('js/jquery.meanmenu.min.js') }}"></script>
	<!-- sticker js -->
	<script src="{{ asset('js/sticker.js') }}"></script>
	<!-- main js -->
	<script src="{{ asset('js/main.js') }}"></script>
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- JS -->
	<script src="{{ asset('js/store-popup.js') }}"></script>
	<script src="{{ asset('js/cart.js') }}"></script>
	<!--  -->
	<script src="https://cdn.payos.vn/payos-checkout/v1/stable/payos-initialize.js"></script>
	@stack('scripts')
	<x-store-popup />
	<script>
    	$(document).on('click', '#logout-btn', function (e) {
			e.preventDefault();

			Swal.fire({
				title: 'Đăng xuất?',
				text: "Bạn chắc chắn muốn đăng xuất?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Có, đăng xuất!',
				cancelButtonText: 'Hủy'
			}).then((result) => {
				if (result.isConfirmed) {
					$('#logout-form').submit();
				}
			});
		});

    $('#store-modal').on('show.bs.modal', () => {
        document.getElementById('storeList').innerHTML = `
            <li class="list-group-item text-center text-muted">
                Nhấn "Vị trí của bạn" để hiển thị các cửa hàng gần nhất
            </li>`;
    });

    function filterStores() {
        const input = document.getElementById("searchStoreInput").value.toLowerCase();
        const items = document.querySelectorAll("#storeList li");

        items.forEach(item => {
            const name = item.getAttribute("data-store-name") || '';
            item.style.display = name.includes(input) ? "" : "none";
        });
    }

    function renderStores(stores) {
        const ul = document.getElementById('storeList');
        if (stores.length === 0) {
            ul.innerHTML = `<li class="list-group-item text-center text-muted">Không tìm thấy cửa hàng nào</li>`;
            return;
        }

        ul.innerHTML = stores.map(store => `
		<li class="list-group-item d-flex justify-content-between align-items-center" data-store-name="${store.ten_cua_hang.toLowerCase()}">
			<div>
				<strong>${store.ten_cua_hang}</strong><br>
				<small>${store.dia_chi}</small><br>
				<small class="text-muted">Cách bạn ~ ${store.khoang_cach.toFixed(1)} km</small>
			</div>
			<button class="btn btn-sm btn-outline-primary" onclick="selectStore('${store.ma_cua_hang}')">Chọn</button>
		</li>`).join('');
	}
    // Lấy vị trí và gọi API
	function getCurrentLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(
				async pos => {
					const lat = pos.coords.latitude;
					const lng = pos.coords.longitude;

					fetch('/stores/nearest', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
						},
						body: JSON.stringify({ latitude: lat, longitude: lng })
					})
					.then(res => res.json())
					.then(renderStores)
					.catch(() => alert("Lỗi khi tìm cửa hàng!"));

					fetch('/get-address', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
						},
						body: JSON.stringify({ latitude: lat, longitude: lng })
					})
					.then(async res => {
						const text = await res.text();
						try {
							return JSON.parse(text);
						} catch (e) {
							throw e;
						}
					})
					.then(data => {
						const diaChi = data.display_name || "Không xác định được địa chỉ";

						const addressBox = document.getElementById('addressBox');
						if (addressBox) addressBox.textContent = diaChi;
					})
					.catch(err => console.error("Lỗi khi lấy địa chỉ:", err));
				},
				() => alert("Không lấy được vị trí của bạn.")
			);
		} else {
			alert("Trình duyệt không hỗ trợ geolocation.");
		}
	}
    function closeStoreModal() {
        $('#store-modal').modal('hide');
    }
	</script>
</body>
</html>