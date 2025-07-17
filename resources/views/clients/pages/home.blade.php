@extends('layouts.app')
@section('title', $title)
@push('styles')
<style>
    .hero-bg {
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
</style>
@endpush
@section('content')
<!-- hero area -->
@if (!empty($banners['top_banner']) && $banners['top_banner']->first())
@php
    $hero = $banners['top_banner']->first();
@endphp
<div class="hero-area" style="background-image: url('{{ asset('storage/' . $hero->hinh_anh) }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 offset-lg-2 text-center">
                <div class="hero-text">
                    <div class="hero-text-tablecell">
                        @if ($hero->tieu_de)
                            <p class="subtitle">{{ $hero->tieu_de }}</p>
                        @endif
                        @if ($hero->noi_dung)
                            <h1>{{ $hero->noi_dung }}</h1>
                        @endif
                        <div class="hero-btns">
                            @if ($hero->link_dich)
                                <a href="{{ $hero->link_dich }}" class="boxed-btn">Khám phá menu</a>
                            @endif
                            <a href="{{ route('contact') }}" class="bordered-btn">Liên hệ với chúng tôi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- end hero area -->
<!-- start product hot -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">	
                    <h3><span class="orange-text">Sản phẩm</span> nổi bật</h3>
                    <p>CDMT không chỉ bán đồ uống, mà là những trải nghiệm vị giác. Những món nổi bật dưới đây là sự kết hợp giữa đam mê, nguyên liệu chất lượng và cảm hứng sáng tạo.</p>
                </div>
            </div>
        </div>
        <div class="row"  id="product-list">
            @foreach ($products as $pro)
                <div class="col-lg-3 col-md-4 col-6 text-center">
                    <div class="single-product-item">
                        <div class="product-image position-relative">
                            <div class="icon-wrapper">
                                @if ($pro->hot && $pro->is_new)
                                    <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                    <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon second">
                                @elseif($pro->hot)
                                    <img src="{{ asset('images/product_hot.png') }}" alt="" class="hot-icon">
                                @elseif($pro->is_new)
                                    <img src="{{ asset('images/product_new.png') }}" alt="" class="hot-icon">
                                @endif
                            </div>
                            <a href="{{ route('product.detail', $pro->slug) }}">
                                <img src="{{ $pro->hinh_anh 
                                    ? asset('storage/' . $pro->hinh_anh) 
                                    : asset('images/no_product_image.png') }}" 
                                    alt="">
                            </a>
                        </div>
                        <h5 class="mt-2">{{ \Illuminate\Support\Str::limit($pro['ten_san_pham'], 20) }}</h5>
                        @if ($pro->loai_san_pham == 0)
                            @php
                                $sizes = $sizesMap[$pro->ma_san_pham] ?? collect(); 
                                $sortedSizes = $sizes->sortBy('gia_size');
                            @endphp
                            <p class="text-center" style="font-style: italic; font-weight: bold; font-size: 14px; color: #5a5a5a; margin-bottom: 6px;">
                                @php
                                    $sizes = $sizesMap[$pro->ma_san_pham] ?? collect(); 
                                @endphp
                                @if ($sizes->count() === 1)
                                    {{ number_format($pro->gia + $sizes[0]->gia_size, 0, ',', '.') }} đ
                                @elseif ($sizes->count() > 1)
                                    @php
                                        $prices = $sizes->map(function($size) use ($pro) {
                                            return $pro->gia + $size->gia_size;
                                        })->sort()->values();
                                    @endphp
                                    {{ number_format($prices->first(), 0, ',', '.') }} đ ~ {{ number_format($prices->last(), 0, ',', '.') }} đ
                                @else
                                    {{ number_format($pro->gia, 0, ',', '.') }} đ
                                @endif
                            </p>
                        @else
                            <p class="text-center" style="font-style: italic; font-weight: bold; font-size: 14px; color: #5a5a5a; margin-bottom: 6px;">
                                {{ number_format($pro->gia, 0,',','.') }} đ
                            </p>
                        @endif
                        <a href="{{ route('product.detail', $pro->slug) }}" class="cart-btn mt-1">
                            <i class="fas fa-shopping-cart"></i> Đặt mua
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center">
            <a href="{{ route('product') }}" class="boxed-btn">Xem thêm</a>
        </div>
    </div>
</div>
<!-- end product hot -->
<!-- home page slider -->
@if (!empty($banners['main_slider']))
<div class="homepage-slider">
    @foreach ($banners['main_slider'] as $slider)
        <div class="single-homepage-slider" style="background-image: url('{{ asset('storage/' . $slider->hinh_anh) }}'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 {{ $loop->index === 0 ? 'offset-lg-1 text-left' : ($loop->index === 1 ? 'offset-lg-1 text-center' : 'offset-lg-1 text-right') }}">
                        <div class="hero-text">
                            <div class="hero-text-tablecell">
                                @if ($slider->tieu_de)
                                    <p class="subtitle">{{ $slider->tieu_de }}</p>
                                @endif
                                @if ($slider->noi_dung)
                                    <h1>{{ $slider->noi_dung }}</h1>
                                @endif
                                <div class="hero-btns">
                                    @if ($slider->link_dich)
                                        <a href="{{ $slider->link_dich }}" class="boxed-btn">Xem thêm</a>
                                    @endif
                                    <a href="{{ route('contact') }}" class="bordered-btn">Liên hệ với chúng tôi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
<!-- end home page slider -->
<!-- Tin tức nổi bật -->
<div class="latest-news pt-150 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">	
                    <h3><span class="orange-text">Bài viết</span> Nổi bật</h3>
                    <p>Khám phá những câu chuyện thú vị, mẹo hay về cà phê, và những khoảnh khắc chill tại CDMT Coffee & Tea. Cùng đón xem để không bỏ lỡ vibes mới mỗi ngày!</p>
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
            <div class="col-lg-12 text-center">
                <a href="{{ route('blog') }}" class="boxed-btn">Xem thêm</a>
            </div>
        </div>
    </div>
</div>
<!-- end Tin tức nổi bật -->
<!-- start about  -->
<div class="abt-section mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
            @if(!empty($banners['about_section_bg']) && count($banners['about_section_bg']) > 0)
                @php
                    $aboutBg = $banners['about_section_bg'][0];
                @endphp
                <div class="abt-bg" style="background-image: url('{{ asset('storage/' . $aboutBg->hinh_anh) }}'); background-size: cover; background-position: center;">
                    <a href="#" class="video-play-btn popup-youtube"><i class="fas fa-play"></i></a>
                </div>
            @else
                <div class="abt-bg">
                    <a href="#" class="video-play-btn popup-youtube"><i class="fas fa-play"></i></a>
                </div>
            @endif

            </div>
            <div class="col-lg-6 col-md-12">
                <div class="abt-text">
                    <p class="top-sub">Since 2025</p>
                    <h2>Chúng tôi là <span class="orange-text">CDMT Coffee & Tea</span></h2>
                    <p>CDMT bắt đầu từ một ước mơ nhỏ: tạo nên không gian cà phê thân thiện, nơi mọi người có thể dừng lại giữa dòng đời vội vã, nhâm nhi ly cà phê thơm và tìm lại sự cân bằng.</p>
                    <p>Từng ly nước tại CDMT không chỉ là hương vị, mà còn là tâm huyết – từ cà phê rang xay nguyên chất đến các món trà trái cây tươi mát, tất cả đều được lựa chọn kỹ lưỡng vì sức khỏe và trải nghiệm của bạn.</p>
                    <a href="{{ route('about') }}" class="boxed-btn mt-4">Tìm hiểu thêm</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end about  -->
<!-- logo carousel -->
@if(!empty($banners['store_gallery']))
<div class="logo-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo-carousel-inner">
                    @foreach($banners['store_gallery'] as $item)
                        <div class="single-logo-item">
                            @if($item->link_dich)
                                <a href="{{ $item->link_dich }}" target="_blank">
                                    <img src="{{ asset('storage/' . $item->hinh_anh) }}" alt="{{ $item->tieu_de }}">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $item->hinh_anh) }}" alt="{{ $item->tieu_de }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- end logo carousel -->
@endsection