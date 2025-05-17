@extends('layouts.app')
@section('title', $title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush
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
	@if ($cartCount > 0)
		<!-- cart -->
		<div class="cart-section mt-150 mb-150" style="min-height: 250px;">
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
									@foreach ($cart as $item)
										<tr class="table-body-row" id="cart-item-{{ $item['product_id'] }}-{{ $item['size_id'] }}">
											<td class="product-remove">
												<a href="#" class="delete-product" data-product-id="{{ $item['product_id'] }}" data-size-id="{{ $item['size_id'] }}">
													<i class="far fa-window-close"></i>
												</a>
											</td>
											<td class="product-image">
												<a href="{{ route('product.detail', $item['product_slug']) }}">
													<img src="{{ $item['product_image'] ? asset('storage/' . $item['product_image']) : asset('images/no_product_image.png') }}" alt="">
												</a>
											</td>
											<td class="product-name">
												<strong>{{ $item['product_name'] }}</strong><br>
												@if (isset($productSizes[$item['product_id']]) && count($productSizes[$item['product_id']]) > 1)
													{{-- Có nhiều size -> cho chọn --}}
													<select 
														name="size_update_{{ $item['product_id'] }}" 
														class="form-select form-select-sm mt-1 change-size" 
														data-old-size="{{ $item['size_id'] }}"
														style="max-width: 200px; border-radius: 8px; border: 1px solid #ccc; padding: 4px 8px;">
														@foreach ($productSizes[$item['product_id']] as $size)
															<option value="{{ $size->ma_size }}" {{ $size->ma_size == $item['size_id'] ? 'selected' : '' }}>
																{{ $size->ten_size }} + {{ number_format($size->gia_size, 0, ',', '.') }} đ
															</option>
														@endforeach
													</select>
												@elseif (isset($productSizes[$item['product_id']]) && count($productSizes[$item['product_id']]) === 1)
													{{-- Chỉ có 1 size -> hiển thị bằng span --}}
													@php
														$onlySize = $productSizes[$item['product_id']][0];
													@endphp
													<span style="display: inline-block; background: #e0f7fa; color: #00796b; 
																padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 500;">
														{{ $onlySize->ten_size }} + {{ number_format($onlySize->gia_size, 0, ',', '.') }} đ
													</span>
												@endif
											</td>
											<td class="product-price">
												{{ number_format($item['product_price'], 0, ',', '.') }} đ
											</td>
											<td class="product-quantity">
												<div class="quantity-wrapper">
													<button class="qty-btn decrease" type="button" data-id="{{ $item['product_id'] }}" data-size="{{ $item['size_id'] }}">
														<i class="fas fa-minus"></i>
													</button>
													<input type="number" value="{{ $item['product_quantity'] }}" min="1" max="99" readonly
														class="update-cart-quantity"
														data-id="{{ $item['product_id'] }}" 
														data-size="{{ $item['size_id'] }}">
													<button class="qty-btn increase" type="button" data-id="{{ $item['product_id'] }}" data-size="{{ $item['size_id'] }}">
														<i class="fas fa-plus"></i>
													</button>
												</div>
											</td>
											<td class="product-total">
												<span class="total-money" id="money-{{ $item['product_id'] }}-{{ $item['size_id'] }}">
													{{ number_format($item['money'], 0, ',', '.') }} đ
												</span>
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
										<td><strong class="label">Tạm tính:</strong></td>
										<td>
											<span class="value subtotal" id="subtotal">
												{{ number_format($total, 0, ',', '.') }} đ
											</span>
										</td>
									</tr>
									<tr class="total-data">
										<td><strong class="label">Phí Ship:</strong></td>
										<td>
											<span class="value shipping-fee" id="shipping-fee">0 đ</span>
										</td>
									</tr>
									<tr class="total-data total-final">
										<td><strong class="label">Thành tiền:</strong></td>
										<td>
											<span class="value total" id="total">
												{{ number_format($total, 0, ',', '.') }} đ
											</span>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="cart-buttons">
								<a href="{{ route('product') }}" class="boxed-btn"><i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm</a>
								<a href="#" class="boxed-btn black"><i class="fas fa-credit-card"></i>  Thanh toán</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end cart -->
	@else
		<!-- empty-cart -->
		<div class="empty-cart d-flex align-items-center justify-content-center gap-4 py-5" style="{{ $cartCount === 0 ? '' : 'display:none;' }}">
			<img src="{{ asset('images/empty-cart.png') }}" alt="Giỏ hàng trống" style="width: 150px; padding:20px">
			<div class="text-start">
				<h5 class="mb-2 text-muted">Giỏ hàng của bạn đang trống!</h5>
				<a href="{{ route('product') }}" class="btn btn-outline-primary">
					<i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm
				</a>
			</div>
		</div>
	@endif
@endsection
@push('scripts')

@endpush