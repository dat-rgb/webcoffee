<?php

use App\Http\Controllers\admins\AdminHomeController;
use App\Http\Controllers\admins\AdminProductController;
use App\Http\Controllers\admins\AdminCategoryController;
use App\Http\Controllers\admins\AdminMaterialController;
use App\Http\Controllers\admins\AdminVoucherController;
use App\Http\Controllers\admins\AdminSupplierController;
use App\Http\Controllers\admins\AdminNhanvienController;
use App\Http\Controllers\admins\AdminLichlamviecController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\clients\AuthController;
use App\Http\Controllers\clients\ForgotPasswordController;
use App\Http\Controllers\clients\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\payments\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
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

    Route::post('/logout', [AuthController::class,'logout'])->name('logout');

    Route::get('/register',[AuthController::class,'showRegisterForm'])->name('register');

    Route::post('/resgister',[AuthController::class,'register'])->name('register.post');

    Route::get('/activate/{token}',[AuthController::class,'activate'])->name('register.activate');


    //Forgot password
    Route::get('/forgot-password',[ForgotPasswordController::class,'showForgotPassword'])->name('forgotPassword.show');
    Route::post( '/forgot-password',[ForgotPasswordController::class,'sendResetPasswordLink'])->name('forgotPassword.send');
    Route::get('/reset-password/{token}',[ResetPasswordController::class,'showRetsetForm'])->name('password.reset');
    Route::post('/reset-password',[ResetPasswordController::class,'resetPassword'])->name('resetPassword.update');

    Route::post('/select-store', [StoreController::class, 'selectStore'])->name('select.store');

});

//Route sản phẩm
Route::prefix('products')->group(function(){
    Route::get('/',[ProductController::class, 'productList'])->name('product');
    Route::get('/categories-products/{id}',[ProductController::class,'listProductsByCategoryParent'])->name('product.category.list');
    Route::get('/product-detail/{slug}',[ProductController::class, 'productDetail'])->name('product.detail');
});

//Route giỏ hàng
Route::prefix('cart')->group(function(){
    Route::get('/', [CartController::class, 'cart'])->name('cart');
    Route::get('/load', [CartController::class, 'loadCart'])->name('cart.load');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('cart.count');

    //add to cart
    Route::get('/add-to-cart/{id}',[CartController::class,'addToCart'])->name('cart.addToCart');
    Route::get('/debug', function () {
        return dd([
            'cart' => session('cart'),
            'selected_store_id' => session('selected_store_id'),
            'selected_store_name' => session('selected_store_name'),
            'selected_store_dia_chi' => session('selected_store_dia_chi'),
        ]);
    });

    Route::get('/delete', function () {
        session()->forget('cart');
        session()->forget('selected_store_id');
        session()->forget('selected_store_name');
        session()->forget('selected_store_dia_chi');
        session()->save(); // bắt buộc gọi để lưu thay đổi session ngay
        return 'Cart đã bị xóa!';
    });
    Route::get('/check-cart-quantity', [CartController::class, 'checkCartQuantity'])->name('cart.checkQuantity');
    Route::post('/update-quantity', [CartController::class, 'updateQuantity']);
    Route::post('/delete-product',[CartController::class,'deleteProduct']);
    Route::post('update-size', [CartController::class, 'updateSize'])->name('cart.updateSize');
    Route::get('/empty-component', function () {
        $cart = session('cart', []);
        return view('carts.cart_empty', compact('cart'));
    });

    Route::get('/check-out',[CartController::class,'checkout'])->name('cart.check-out');
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
    Route::get('/',[AdminProductController::class,'listProducts'])->name('admin.products.list');
    Route::get('/hidden',[AdminProductController::class,'listProductsHidden'])->name('admin.products.hidden.list');
    Route::get('/add-product',[AdminProductController::class,'showProductForm'])->name('admin.products.form');
    Route::post('/add-product',[AdminProductController::class,'productAdd'])->name('admin.products.add');
    Route::get('/edit-product/{id}', [AdminProductController::class, 'showProductEdit'])->name('admin.product.edit.form');
    Route::post('/edit-product/{id}', [AdminProductController::class, 'updateProduct'])->name('admin.product.update');
    Route::get('/ingredients/{id}', [AdminProductController::class, 'showProductIngredients'])->name('admin.products.ingredients.show');
    Route::post('/hidden-or-acctive/{id}',[AdminProductController::class,'productHiddenOrAcctive'])->name('admin.product.hidde-or-acctive');
    Route::post('/bulk-action', [AdminProductController::class, 'bulkAction'])->name('admin.product.bulk-action');
    Route::get('/add-ingredients/{slug}',[AdminProductController::class,'showProductAddIngredients'])->name('admin.products.ingredients.form');
    Route::post('/ingredients/add',[AdminProductController::class,'productAddIngredients'])->name('admin.products.ingredients.add');
    Route::post('/ingredient/update',[AdminProductController::class,'productUpdateIngredients'])->name('admin.products.ingredients.update');
    Route::delete('/{slug}/sort-delete',[AdminProductController::class,'sortDelete'])->name('admin.product.sort-delete');
    Route::get('/delete',[AdminProductController::class,'listProductSortDelete'])->name('admin.products.list.delete');
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
//Route Material Admin
Route::prefix('admin/materials')->name('admins.material.')->group(function () {
    Route::get('/', [AdminMaterialController::class, 'index'])->name('index');
    Route::get('/create', [AdminMaterialController::class, 'create'])->name('create');
    Route::post('/', [AdminMaterialController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminMaterialController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminMaterialController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminMaterialController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/archive', [AdminMaterialController::class, 'archive'])->name('archive');
    Route::get('/archive', [AdminMaterialController::class, 'archiveIndex'])->name('archive.index'); // Hiển thị danh mục lưu trữ
    Route::post('/{id}/restore', [AdminMaterialController::class, 'restore'])->name('restore');
    Route::post('/{id}/toggle-status', [AdminMaterialController::class, 'toggleStatus'])->name('toggleStatus');
    Route::post('/{id}/archive', action: [AdminMaterialController::class, 'archive'])->name('archive');
    Route::get('/archive', [AdminMaterialController::class, 'archiveIndex'])->name('archive.index');

});

//Route Vouchers Admin
Route::prefix('admin/vouchers')->name('admin.vouchers.')->group(function(){
    Route::get('',[AdminVoucherController::class,'listVouchers'])->name('list');
    Route::get('/list-vouchers-off',[AdminVoucherController::class,'listVouchersOff'])->name('list-vouchers-off');
    Route::get('/list-vouchers-archive',[AdminVoucherController::class,'listVouchersArchive'])->name('list-vouchers-archive');
    Route::get('/add-voucher',[AdminVoucherController::class,'showVoucherForm'])->name('form');
    Route::post('/add-voucher',[AdminVoucherController::class,'addVoucher'])->name('add');
    Route::post('/on-or-off-voucher/{id}',[AdminVoucherController::class,'onOrOffVoucher'])->name('on-or-off-voucher');
    Route::post('/archive-voucher{id}',[AdminVoucherController::class,'voucherArchive'])->name('archive-voucher');
    Route::post('/delete-voucher/{id}', [AdminVoucherController::class, 'deleteVoucher'])->name('delete');
    Route::get('/edit-voucher/{id}', [AdminVoucherController::class, 'editVoucherForm'])->name('edit');
    Route::post('/admin/vouchers/{id}/edit', [AdminVoucherController::class, 'editVoucher'])->name('update');

});
//Route Supplier Admin
Route::prefix('admin/suppliers')->name('admins.supplier.')->group(function () {
    Route::get('/', [AdminSupplierController::class, 'index'])->name('index');
    Route::get('/create', [AdminSupplierController::class, 'create'])->name('create');
    Route::post('/store', [AdminSupplierController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [AdminSupplierController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [AdminSupplierController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [AdminSupplierController::class, 'destroy'])->name('destroy');
    Route::post('/archive/{id}', [AdminSupplierController::class, 'archive'])->name('archive');  // Lưu trữ
    Route::get('/archived', [AdminSupplierController::class, 'archived'])->name('archived');    // Danh sách lưu trữ
    Route::patch('/restore/{id}', [AdminSupplierController::class, 'restore'])->name('restore'); // Khôi phục
    Route::post('/toggle-status/{id}', [AdminSupplierController::class, 'toggleStatus'])->name('toggleStatus');
});
//Route NhanVien
Route::prefix('admin/nhanviens')->name('admins.nhanvien.')->group(function () {
    Route::get('/', [AdminNhanVienController::class, 'index'])->name('index');
    Route::get('/create', [AdminNhanVienController::class, 'create'])->name('create');
    Route::post('/store', [AdminNhanVienController::class, 'store'])->name('store');
    Route::get('/{ma_nhan_vien}/edit', [AdminNhanVienController::class, 'edit'])->name('edit');
    Route::put('/{ma_nhan_vien}', [AdminNhanVienController::class, 'update'])->name('update');
    Route::delete('/{ma_nhan_vien}', [AdminNhanVienController::class, 'destroy'])->name('destroy');
    //Thử thách Admin phân công lịch làm việc
    Route::get('/phan-cong-lich', [AdminLichlamviecController::class, 'showForm'])->name('lich.showForm');
    Route::post('/phan-cong-lich', [AdminLichlamviecController::class, 'assignWork'])->name('lich.assignWork');
    Route::get('/lich-lam-viec', [AdminLichlamviecController::class, 'showLichTheoTuan'])->name('lich.tuan');
    //Tạm nghỉ cho nhân viên
    Route::post('/archive/{id}', action: [AdminNhanVienController::class, 'archive'])->name('archive');  // Lưu trữ
    Route::get('/archived', [AdminNhanVienController::class, 'archived'])->name('archived');    // Danh sách lưu trữ
    Route::patch('/restore/{id}', action: [AdminNhanVienController::class, 'restore'])->name('restore'); // Khôi phục
});


