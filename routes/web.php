<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

//Route Home
Route::prefix('trang-chu')->group(function(){
    Route::get('/',[HomeController::class, 'home'])->name('home');
    Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');
    Route::get('/lien-he', [HomeController::class, 'contact'])->name('contact');
   
    //Mượn tạm HomeController hiển thị giao diện login & register
    Route::get('/dang-nhap', [HomeController::class, 'login'])->name('login');
    Route::get('/dang-ky', [HomeController::class, 'register'])->name('register');
});

//Route sản phẩm
Route::prefix('san-pham')->group(function(){
    Route::get('/',[ProductController::class, 'productList'])->name('sanpham');
    Route::get('/detail',[ProductController::class, 'productDetail'])->name('sanpham.detail');
});

//Route giỏ hàng
Route::prefix('gio-hang')->group(function(){
    Route::get('/', [CartController::class, 'cart'])->name('cart');
});

//Tin tức
Route::prefix('tin-tuc')->group(function(){
    Route::get('/', [BlogController::class, 'index'])->name('blog');
    Route::get('/chi-tiet', [BlogController::class, 'blogDetail'])->name('blog.detail');
});