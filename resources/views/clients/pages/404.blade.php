@extends('layouts.app')
@section('title','404!')

@section('content')
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>CDMT Coffee & Tea</p>
                    <h1>404 - Not Found</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="full-height-section error-section">
    <div class="full-height-tablecell">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="error-text">
                        <i class="far fa-sad-cry"></i>
                        <h1>Not Found</h1>
                        <p>Không tìm thấy trang!</p>
                        <a href="{{ url('/') }}" class="boxed-btn">Về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
