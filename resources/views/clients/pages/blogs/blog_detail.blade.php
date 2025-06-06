@extends('layouts.app')
@section('title', $title)

@push('styles')
<style>
    .single-artcile-bg {
        background-image: url('{{ asset('storage/' . $blog->hinh_anh) }}');
        height: 450px;
    }
</style>
@endpush
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Coffee & Tea</p>
                    <h1>{{ $blog->tieu_de }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
<!-- featured section -->
<div class="mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="single-article-section">
						<div class="single-article-text">
							@if ($blog->hinh_anh)
                            <div class="single-artcile-bg"></div>
                            @endif
                            <p class="blog-meta">
								<span class="author"><i class="fas fa-user"></i> {{ $blog->tac_gia }}</span>
								<span class="date"><i class="fas fa-calendar"></i> {{ $blog->ngay_dang }}</span>
							</p>
							<h2>{{ $blog->sub_tieu_de }}</h2>
                            <p>{!! $blog->noi_dung !!}</p>
						</div>
                    </div>
                </div>
                @include('clients.pages.blogs._sidebar_blog')
			</div>
		</div>
	</div>
<!-- end featured section -->

<!-- logo carousel -->
<div class="logo-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo-carousel-inner">
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h1.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h2.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h3.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h4.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{ asset('storage/home/h5.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end logo carousel -->
@endsection