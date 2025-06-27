<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{ $thongTinWebsite['mo_ta'] }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- title -->
	<title>@yield('title', $thongTinWebsite['ten_website'])</title>
	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('images/'.$thongTinWebsite['favicon']) }}">
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
								<img src="{{ asset('images/' . $thongTinWebsite['logo']) }}" alt="">
							</a>
						</div>
						<!-- logo -->
						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="{{ request()->routeIs('home') ? 'current-list-item ' : '' }}"><a href="{{ route('home') }}">Trang Chủ</a></li>
								<li class="{{ request()->routeIs('product') ? 'current-list-item ' : '' }}"><a href="{{ route('product') }}">Sản Phẩm</a>
									<ul class="sub-menu">
										<li><a href="{{ route('product') }}">Tất cả</a></li>
										@foreach ($danhMucCha as $dm)
											@if ($dm->totalProductsCount > 0)
												<li><a href="{{ route('product.category.list',$dm->slug) }}">{{ $dm->ten_danh_muc }}</a></li>
											@endif
										@endforeach
									</ul>
								</li>
								<li class="{{ request()->routeIs('blog') ? 'current-list-item ' : '' }}"><a href="{{ route('blog') }}">Tin Tức</a>
									<ul class="sub-menu">
										<li><a href="{{ route('blog') }}">Tất cả</a></li>
										@foreach ($danhMucBlog as $dmBlog)
											<li><a href="{{ route('blog.byCate',$dmBlog->slug) }}">{{ $dmBlog->ten_danh_muc_blog }}</a></li>
										@endforeach
									</ul>
								</li>
								<li class="{{ request()->routeIs('about') ? 'current-list-item ' : '' }}"><a href="{{ route('about') }}">Giới thiệu</a></li>
								<li class="{{ request()->routeIs('contact') ? 'current-list-item ' : '' }}"><a href="{{ route('contact') }}">Liên Hệ</a></li>	
								<li>
									<a href="#" id="store-btn" onclick="openStoreModal()">
										<i class="fas fa-store-alt"></i>
										<span>{{ \Illuminate\Support\Str::limit(session('selected_store_name'), 15) ?? 'Cửa hàng' }}</span>
									</a>
								</li>
								@if(Auth::check())
									<li>
										<a href="{{ route('customer.index') }}">
											<i class="fas fa-user"></i>
											{{ \Illuminate\Support\Str::limit(Auth::user()->khachHang->ho_ten_khach_hang ?? 'Khách hàng', 10) ?? 'Cửa hàng' }}
										</a>
										<ul class="sub-menu">
											<li><a href="{{ route('customer.index') }}"><i class="fas fa-user-circle"></i> Hồ sơ</a></li>
											<li><a href="{{ route('favorite.show') }}"><i class="fas fa-heart"></i> Yêu thích</a></li>
											<li><a href="{{ route('customer.order.history') }}"><i class="fas fa-receipt"></i> Lịch sử mua hàng</a></li>
											<li><a href="{{ route('customer.sanPhamDaXem') }}"><i class="fas fa-eye"></i> Sản phẩm đã xem</a></li>
											<li>
												<a href="{{ route('customer.sanPhamDaMua') }}">
													<i class="fas fa-box-open"></i> Sản phẩm đã mua
												</a>
											</li>
											<li>
												<a href="{{ route('customer.uuDaiThanhVien') }}">
													<i class="fas fa-gift"></i> Ưu đãi thành viên
												</a>
											</li>
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
						<a href="{{ route('cart') }}" class="mobile-show cart-bar-icon">
							<i class="fas fa-shopping-cart"></i>
							<span class="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
						</a>
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
	<a href="#" id="back-to-top" class="cart-btn" style="position: fixed; bottom: 30px; right: 30px; display: none; z-index: 999; ">
		<i class="fas fa-arrow-up"></i> 
	</a>

	<!-- footer -->
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">Về chúng tôi</h2>
						<p>{{ $thongTinWebsite['mo_ta'] }}</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Liên hệ</h2>
						<ul>
							<li>{{ $thongTinWebsite['dia_chi'] }}</li>
							<li>{{ $thongTinWebsite['email'] }}</li>
							<li>{{ $thongTinWebsite['so_dien_thoai'] }}</li>
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
					<p>{{ $thongTinWebsite['footer_text'] }}</p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li>
								<a href="{{ $thongTinWebsite['facebook_url'] }}" target="_blank">
									<img src="{{ asset('images/website/icon_fb.png') }}" alt="Facebook" width="20">
								</a>
							</li>
							<li>
								<a href="{{ $thongTinWebsite['instagram_url'] }}" target="_blank">
									<img src="{{ asset('images/website/icon_instagram.png') }}" alt="Instagram" width="20">
								</a>
							</li>
							<li>
								<a href="{{ $thongTinWebsite['youtube_url'] }}" target="_blank">
									<img src="{{ asset('images/website/icon_youtube.png') }}" alt="Youtube" width="20">
								</a>
							</li>
							<li>
								<a href="{{ $thongTinWebsite['tiktok_url'] }}" target="_blank">
									<img src="{{ asset('images/website/icon_tiktok.png') }}" alt="Tiktok" width="20">
								</a>
							</li>
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
	<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
	<script src="https://cdn.payos.vn/payos-checkout/v1/stable/payos-initialize.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

		$('#store-modal').on('show.bs.modal', async () => {
			const address = sessionStorage.getItem('user_address');
			const stores = sessionStorage.getItem('nearest_stores');

			if (address) {
				document.getElementById('addressBox').textContent = address;
			}

			if (stores) {
				renderStores(JSON.parse(stores));
			} else {
				document.getElementById('storeList').innerHTML = `
					<li class="list-group-item text-center text-muted">
						Nhấn "Vị trí" để hiển thị các cửa hàng gần nhất
					</li>`;
			}
		});

		function filterStores() {
			const input = document.getElementById("searchStoreInput").value.toLowerCase();
			const items = document.querySelectorAll("#storeList li");

			items.forEach(item => {
				const name = item.getAttribute("data-store-name") || '';
				item.style.display = name.includes(input) ? "" : "none";
			});
		}

		function parseTimeToDate(timeStr) {
			const [hours, minutes] = timeStr.split(':').map(Number);
			const now = new Date();
			return new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
		}

		function renderStores(stores) {
			const ul = document.getElementById('storeList');
			const selectedStoreId = ul.getAttribute('data-selected-store');
			const hasLocation = !!sessionStorage.getItem('nearest_stores');
			const now = new Date();

			if (stores.length === 0) {
				ul.innerHTML = `<li class="list-group-item text-center text-muted">Không tìm thấy cửa hàng nào</li>`;
				return;
			}

			ul.innerHTML = stores.map(store => {
				const isSelected = store.ma_cua_hang === selectedStoreId;

				const openTime = parseTimeToDate(store.gio_mo_cua);
				const closeTime = parseTimeToDate(store.gio_dong_cua);
				const isOpen = now >= openTime && now <= closeTime;
				const remainingMinutes = Math.floor((closeTime - now) / 60000);

				let statusText = '';
				if (!isOpen) {
					statusText = `<small class="text-danger fw-bold">Đã đóng cửa</small>`;
				} else if (remainingMinutes <= 60) {
					statusText = `<small class="text-warning">⏰ Còn ${remainingMinutes} phút nữa đóng cửa</small>`;
				}

				const btnDisabled = !hasLocation || isSelected || !isOpen ? 'disabled' : '';
				const btnText = !isOpen ? 'Đã đóng cửa' : (isSelected ? 'Đã chọn' : 'Chọn');
				const btnClass = isSelected ? 'btn' : 'btn btn-outline-primary';
				const btnStyle = isSelected ? 'background-color: #F28123; border-color: #F28123; color: white;' : '';

				return `
					<li class="list-group-item d-flex justify-content-between align-items-center" data-store-name="${store.ten_cua_hang.toLowerCase()}">
						<div>
							<strong>${store.ten_cua_hang}</strong><br>
							<small><strong>Địa chỉ:</strong> ${store.dia_chi}</small><br>
							<small><strong>Số điện thoại:</strong> ${store.so_dien_thoai}</small><br>
							<small><strong>Giờ hoạt động:</strong> ${store.gio_mo_cua} - ${store.gio_dong_cua}</small><br>
							${statusText}
							<br><small class="text-muted">Cách bạn ~ ${store.khoang_cach.toFixed(1)} km</small>
						</div>
						<button class="btn btn-sm ${btnClass}" style=" min-width: 120px; ${btnStyle}" ${btnDisabled}
							onclick="selectStore('${store.ma_cua_hang}')">${btnText}</button>
					</li>`;
			}).join('');
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
						.then(data => {
							sessionStorage.setItem('nearest_stores', JSON.stringify(data));
							renderStores(data);
						})
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
							document.getElementById('addressBox').textContent = diaChi;
							sessionStorage.setItem('user_address', diaChi);
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
	<!-- <script lang="javascript">var __vnp = {code : 25338,key:'', secret : 'd3920272b894f48f2d92802d63fd3db2'};(function() {var ga = document.createElement('script');ga.type = 'text/javascript';ga.async=true; ga.defer=true;ga.src = '//core.vchat.vn/code/tracking.js?v=35925'; var s = document.getElementsByTagName('script');s[0].parentNode.insertBefore(ga, s[0]);})();</script> -->
</body>
</html>