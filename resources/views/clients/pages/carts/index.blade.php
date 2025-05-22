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
	<!-- cart -->
	<div  id="cart-section" class="cart-section mt-150 mb-150" style="min-height: 250px;">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-12">
					@include('clients.pages.carts.cart_table')
				</div>
				<div class="col-lg-4">
					@include('clients.pages.carts.cart_total')
				</div>
			</div>
		</div>
	</div>
	<!-- end cart -->
	<!-- empty-cart -->
	<div id="empty-cart" class="empty-cart">
		@include('clients.pages.carts.cart_empty')
	</div>
	<!-- end empty-cart -->
@endsection
