<?php

use App\Http\Controllers\admins\AdminHomeController;
use App\Http\Controllers\admins\AdminProductController;
use App\Http\Controllers\admins\AdminCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\clients\AuthController;
use App\Http\Controllers\clients\ForgotPasswordController;
use App\Http\Controllers\clients\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Route Home
Route::prefix('/')->group(function(){
    Route::get('',[HomeController::class, 'home'])->name('home');

    Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');

    Route::get('/lien-he', [HomeController::class, 'contact'])->name('contact');

    //Auth Clients
    Route::get('/login',[AuthController::class,'showLoginForm'])->name('login');

    Route::post('/login',[AuthController::class,'login'])->name('login.post');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('home');
    })->name('logout');

    Route::get('/register',[AuthController::class,'showRegisterForm'])->name('register');

    Route::post('/resgister',[AuthController::class,'register'])->name('register.post');

    Route::get('/activate/{token}',[AuthController::class,'activate'])->name('register.activate');

    //Forgot password
    Route::get('/forgot-password',[ForgotPasswordController::class,'showForgotPassword'])->name('forgotPassword.show');
    Route::post( '/forgot-password',[ForgotPasswordController::class,'sendResetPasswordLink'])->name('forgotPassword.send');
    Route::get('/reset-password/{token}',[ResetPasswordController::class,'showRetsetForm'])->name('password.reset');
    Route::post('/reset-password',[ResetPasswordController::class,'resetPassword'])->name('resetPassword.update');


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

// Route Home Admin
Route::prefix('admin')->group(function(){
    Route::get('',[AdminHomeController::class,'index'])->name('admin');
});

//Route Products Admin
Route::prefix('admin/products')->group(function(){
    Route::get('/list',[AdminProductController::class,'listProducts'])->name('admin.products.list');
    Route::get('/archive',[AdminProductController::class,'listProductsArchive'])->name('admin.products.archive.list');
    Route::get('/hidden',[AdminProductController::class,'listProductsHidden'])->name('admin.products.hidden.list');
    Route::get('/add-product',[AdminProductController::class,'showProductForm'])->name('admin.products.form');
    Route::post('/add-product',[AdminProductController::class,'productAdd'])->name('admin.products.add');
    Route::post('/archive-product/{id}',[AdminProductController::class, 'productArchive'])->name('admin.product.archive');
    Route::post('/hidden-or-acctive/{id}',[AdminProductController::class,'productHiddenOrAcctive'])->name('admin.product.hidde-or-acctive');
});

//Route Categories Admin
Route::prefix('admin/categories')->name('admins.category.')->group(function () {
    Route::get('/', action: [AdminCategoryController::class, 'index'])->name('index');
    Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
    Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminCategoryController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/archive', [AdminCategoryController::class, 'archive'])->name('archive');
    Route::get('/archive', [AdminCategoryController::class, 'archiveIndex'])->name('archive.index'); // Hiển thị danh mục lưu trữ
    Route::post('/{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');

});

