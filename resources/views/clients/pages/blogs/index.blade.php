@extends('layouts.app')
@section('title', $title)
@section('subtitle', $subtitle)
@push('styles')
<style>
    ul li.active a {
    color: #fff !important;
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
                    <h1>{{ $subtitle }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- latest news -->
<div class="latest-news mt-150 mb-150">
    @if(!$blogs)
    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-md-12 text-center">
           <h5>Nội dung đang được cập nhật...</h5>
        </div>
    </div>
    @else
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li class="{{ request()->routeIs('blog') ? 'active' : '' }}">
                            <a href="{{ route('blog') }}" style="color:black">Tất cả</a>
                        </li>
                        @foreach ($danhMucBlog as $dm)
                            <li class="{{ (isset($slugActive) && $slugActive === $dm->slug) ? 'active' : '' }}">
                                <a href="{{ route('blog.byCate', $dm->slug) }}" style="color:black">{{ $dm->ten_danh_muc_blog }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>  
        <div class="row">
            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="single-latest-news">
                        <a href="{{ route('blog.detail', $blog->slug) }}">
                            <div class="latest-news-bg" style="background-image: url('{{ asset('storage/' . $blog->hinh_anh) }}'); height: 250px; background-size: cover; background-position: center;"></div>
                        </a>
                        <div class="news-text-box">
                            <h3><a href="{{ route('blog.detail', $blog->slug) }}">{{ $blog->tieu_de }}</a></h3>
                            <p class="blog-meta">
                                <span class="author"><i class="fas fa-user"></i> {{ $blog->tac_gia }}</span>
                                <span class="date"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($blog->ngay_dang)->format('d/m/Y') }}</span>
                            </p>
                            <p class="excerpt">{!! \Str::limit(strip_tags($blog->noi_dung), 100) !!}</p>
                            <a href="{{ route('blog.detail', $blog->slug) }}" class="read-more-btn">Xem thêm <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="pagination-wrap">
                            <ul>
                                <li><a href="{{ $blogs->previousPageUrl() ?? '#' }}">Prev</a></li>
                                @for ($i = 1; $i <= $blogs->lastPage(); $i++)
                                    <li>
                                        <a href="{{ $blogs->url($i) }}"
                                            class="{{ $blogs->currentPage() == $i ? 'active' : '' }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor
                                <li><a href="{{ $blogs->nextPageUrl() ?? '#' }}">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- end latest news -->

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