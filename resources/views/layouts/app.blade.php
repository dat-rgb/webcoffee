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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	
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
								<li><a href="{{ route('about') }}">Giới thiệu</a></li>
								<li><a href="{{ route('blog') }}">Tin Tức</a>
									<ul class="sub-menu">
										<li><a href="{{ route('blog') }}">Coffee</a></li>
										<li><a href="{{ route('blog') }}">Chuyện Trà</a></li>
									</ul>
								</li>
								<li><a href="{{ route('contact') }}">Liên Hệ</a></li>
								<li><a href="{{ route('product') }}">Sản Phẩm</a>
									<ul class="sub-menu">
										@foreach ($danhMucCha as $dm)
											@if ($dm->totalProductsCount > 0)
												<li><a href="{{ route('product.category.list',$dm->slug) }}">{{ $dm->ten_danh_muc }}</a></li>
											@endif
										@endforeach
									</ul>
								</li>
								<li>
									<a href="#" id="store-btn" onclick="openStoreModal()" style="border-radius: 20px; background-color:#F28123; color: #fff; padding: 6px 16px;">
										<i class="fas fa-store-alt"></i>
										{{ session('selected_store_name') ?? 'Cửa hàng' }}
									</a>
								</li>

								{{-- Tách user icon ra khỏi header-icons --}}
								@auth
									<li>
										<div class="current-list-item">
											<a href="#"><i class="fas fa-user"></i> {{ Auth::user()->khachHang->ho_ten_khach_hang ?? 'Khách hàng' }}</a>
											<ul class="sub-menu">
												<li><a href="{{ route('customer.index') }}"><i class="fas fa-user-circle" style="margin-right:6px;"></i>Hồ sơ</a></li>
												<li><a href="#"><i class="fas fa-map-marker-alt" style="margin-right:6px;"></i>Sổ địa chỉ</a></li>
												<li><a href="#"><i class="fas fa-heart" style="margin-right:6px;"></i>Yêu thích</a></li>
												<li><a href="#"><i class="fas fa-receipt" style="margin-right:6px;"></i>Lịch sử mua hàng</a></li>
												<li><a href="#"><i class="fas fa-eye" style="margin-right:6px;"></i>Sản phẩm đã xem</a></li>
												<li>
													<button type="button" id="logout-btn" style="color: #fff; background: #e74c3c; border-radius: 8px; padding: 8px 16px; border: none; font-weight: 500;">
														<i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Đăng xuất
													</button>

													<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
														@csrf
													</form>
												</li>
											</ul>
										</div>
									</li>
								@else
									<li>
										<a class="login" href="{{ route('login') }}"><i class="fas fa-user"></i></a>
									</li>
								@endauth
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
								<input type="text" name="search" placeholder="Nhập từ khóa..." value="{{ request('search') }}">
								<button type="submit">
									Search <i class="fas fa-search"></i>
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
						<h2 class="widget-title">About us</h2>
						<p>Ut enim ad minim veniam perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Get in Touch</h2>
						<ul>
							<li>65, Huỳnh Thúc Kháng, Bến Nghé, Quận 1, Thành Phố Hồ Chí Minh</li>
							<li>trandatc3vvk@gmail.com</li>
							<li>+84 901 318 766</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box pages">
						<h2 class="widget-title">Pages</h2>
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
						<h2 class="widget-title">Subscribe</h2>
						<p>Subscribe to our mailing list to get the latest updates.</p>
						<form action="#">
							<input type="email" placeholder="Email">
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
	<script src="{{ asset('js/sweet-alert.js') }}"></script> 
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- JS -->
	<script src="{{ asset('js/store-popup.js') }}"></script>
	<script src="{{ asset('js/cart.js') }}"></script>
	<!--  -->
	<script src="https://cdn.payos.vn/payos-checkout/v1/stable/payos-initialize.js"></script>
	@stack('scripts')
	<x-store-popup />
</body>
</html>