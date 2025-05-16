@extends('layouts.app')
@section('title', $title)
@section('content')
	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Coffee & Tea</p>
						<h1>Giỏ Hàng</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->
	@if (count($cart) > 0)
	<!-- cart -->
	<div class="cart-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-12">
					<div class="cart-table-wrap">
						<table class="cart-table">
							<thead class="cart-table-head">
								<tr class="table-head-row">
									<th class="product-remove"></th>
									<th class="product-image">Hình ảnh</th>
									<th class="product-name">Tên</th>
									<th class="product-price">Giá</th>
									<th class="product-quantity">Số lượng</th>
									<th class="product-total">Thành tiền</th>
								</tr>
							</thead>
							<tbody>
								@foreach ( $cart as  $item)
									<tr class="table-body-row">
										<td class="product-remove">
											<a href="#"><i class="far fa-window-close"></i></a>
										</td>
										<td class="product-image">
											<a href="{{ route('product.detail', $item['product_slug']) }}">
												<img src="{{ $item['product_image'] ? asset('storage/' . $item['product_image']) : asset('images/no_product_image.png') }}" alt="">
											</a>
										</td>
										<td class="product-name">
											<strong>{{ $item['product_name'] }}</strong> <br>

											@if (count($productSizes[$item['product_id']]) > 0)
												<select name="size_update_{{ $item['product_id'] }}" class="form-select form-select-sm mt-1" style="max-width: 200px; padding: 5px 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px;">
													@foreach ($productSizes[$item['product_id']] ?? [] as $size)
														<option value="{{ $size->ma_size }}" {{ $size->ma_size == $item['size_id'] ? 'selected' : '' }}>
															{{ $size->ten_size .' + '. number_format($size->gia_size, 0, ',', '.') }} đ
														</option>
													@endforeach
												</select>
											@endif
										</td>

										<td class="product-price">
											{{ number_format($item['product_price'],0,',','.')}} đ
										</td>
										<td class="product-quantity">
											<input type="number" placeholder="0" value="{{ $item['product_quantity'] }}">
										</td>
										<td class="product-total">
											{{ number_format($item['money'],0,',','.') }} đ
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="total-section">
						<table class="total-table">
							<thead class="total-table-head">
								<tr class="table-total-row">
									<th>Tổng cộng</th>
									<th>Giá</th>
								</tr>
							</thead>
							<tbody>
								<tr class="total-data">
									<td><strong>Tạm tính: </strong></td>
									<td>{{ number_format($total, 0,',','.') }} đ</td>
								</tr>
								<tr class="total-data">
									<td><strong>Phí Ship: </strong></td>
									<td>0 đ</td>
								</tr>
								<tr class="total-data">
									<td><strong>Thành tiền: </strong></td>
									<td>{{ number_format($total, 0,',','.') }} đ</td>
								</tr>
							</tbody>
						</table>
						<div class="cart-buttons">
							<a href="#" class="boxed-btn">Update Cart</a>
							<a href="#" class="boxed-btn black">Check Out</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@else
		<div class="d-flex align-items-center justify-content-center gap-4 py-5" style="min-height: 250px;">
			<img src="{{ asset('images/empty-cart.png') }}" alt="Giỏ hàng trống" style="width: 150px; padding:20px">
			<div class="text-start">
				<h5 class="mb-2 text-muted">Giỏ hàng của bạn đang trống!</h5>
				<a href="{{ route('product') }}" class="btn btn-outline-primary">
					<i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm
				</a>
			</div>
		</div>
	@endif
	<!-- end cart -->
@endsection