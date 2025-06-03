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
							<div class="single-artcile-bg"></div>
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
@endsection