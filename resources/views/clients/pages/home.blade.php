@extends('layouts.app')
@section('title', $title)

@push('styles')
<style>
    .hero-bg {
        background-image: url('{{ asset('storage/home/h6.jpg') }}');          
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .abt-bg {
        background-image: url('{{ asset('storage/home/h7.jpg') }}');
    }
    .homepage-bg-1 {
        background-image: url('{{ asset('storage/home/h8.jpg') }}');
    }

    .homepage-bg-2 {
        background-image: url('{{ asset('storage/home/h9.jpg') }}');
    }

    .homepage-bg-3 {
        background-image: url('{{ asset('storage/home/h6.jpg') }}');
    }
</style>
@endpush
@section('content')
<!-- hero area -->
<div class="hero-area hero-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 offset-lg-2 text-center">
                <div class="hero-text">
                    <div class="hero-text-tablecell">
                    <p class="subtitle">CDMT Coffee & Tea</p>
                        <h1>Thức uống thơm ngon  Đậm vị riêng</h1>
                        <div class="hero-btns">
                            <a href="{{ route('product') }}" class="boxed-btn">Khám phá menu</a>
                            <a href="{{ route('contact') }}" class="bordered-btn">Liên hệ với chúng tôi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
        <div class="row"> 
            @foreach ($products as $pro)
                <div class="col-lg-3 col-md-4 text-center">
                    <div class="single-product-item">
                    <div class="product-image">
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
                            <a href="{{ route('product.detail',$pro->slug) }}">
                                <img src="{{ $pro->hinh_anh ? asset('storage/' . $pro->hinh_anh) : asset('images/no_product_image.png') }}" alt="">
                            </a>
                        </div>
                        <h3>{{ $pro->ten_san_pham }}</h3>
                        <a href="{{ route('product.detail',$pro->slug) }}" class="cart-btn"><i class="fas fa-shopping-cart"></i> Đặt mua</a>
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
<div class="homepage-slider">
    <!-- single home slider -->
    <div class="single-homepage-slider homepage-bg-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-7 offset-lg-1 offset-xl-0">
                    <div class="hero-text">
                        <div class="hero-text-tablecell">
                            <p class="subtitle">Không gian đậm chất Coffee & Tea</p>
                            <h1>Thư giãn trong không gian ấm cúng & hiện đại</h1>
                            <div class="hero-btns">
                                <a href="{{ route('product') }}" class="boxed-btn">Khám phá menu</a>
                                <a href="{{ route('contact') }}" class="bordered-btn">Liên hệ với chúng tôi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- single home slider -->
    <div class="single-homepage-slider homepage-bg-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 text-center">
                    <div class="hero-text">
                        <div class="hero-text-tablecell">
                            <p class="subtitle">Coffee & Tea</p>
                            <h1>Cà phê nguyên chất 100% Đậm đà hương vị Việt</h1>
                            <div class="hero-btns">
                                <a href="{{ route('product.category.list', 'ca-phe') }}" class="boxed-btn">Khám phá ngay</a>
                                <a href="{{ route('contact') }}" class="bordered-btn">Liên hệ với chúng tôi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- single home slider -->
    <div class="single-homepage-slider homepage-bg-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 text-right">
                    <div class="hero-text">
                        <div class="hero-text-tablecell">
                            <p class="subtitle">Coffee & Tea</p>
                            <h1>Khám phá thế giới Trà thơm mát</h1>
                            <div class="hero-btns">
                                <a href="{{ route('product.category.list', 'tra') }}" class="boxed-btn">Xem danh mục Trà</a>
                                <a href="{{ route('contact') }}" class="bordered-btn">Liên hệ với chúng tôi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                <div class="abt-bg">
                    <a href="#" class="video-play-btn popup-youtube"><i class="fas fa-play"></i></a>
                </div>
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