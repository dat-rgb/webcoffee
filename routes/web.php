<?php
use App\Http\Controllers\admins\AdminBlogController;
use App\Http\Controllers\admins\AdminHomeController;
use App\Http\Controllers\admins\AdminOrderController;
use App\Http\Controllers\admins\AdminProductController;
use App\Http\Controllers\admins\AdminCategoryController;
use App\Http\Controllers\admins\AdminMaterialController;
use App\Http\Controllers\admins\AdminProductShopController;
use App\Http\Controllers\admins\AdminStoreController;
use App\Http\Controllers\admins\AdminVoucherController;
use App\Http\Controllers\admins\AdminSupplierController;
use App\Http\Controllers\admins\AdminNhanvienController;
use App\Http\Controllers\admins\AdminLichlamviecController;
use App\Http\Controllers\admins\AdminShopmaterialController;
use App\Http\Controllers\admins\auth\AdminLoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\clients\AuthController;
use App\Http\Controllers\clients\ForgotPasswordController;
use App\Http\Controllers\clients\ResetPasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\customers\CustomerController;
use App\Http\Controllers\customers\CustomerFavoriteController;
use App\Http\Controllers\customers\CustomerOrderController;
use App\Http\Controllers\customers\CustomerReviewController;
use App\Http\Controllers\dashboards\AdminDashboardController;
use App\Http\Controllers\dashboards\StaffDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\payments\Napas247Controller;
use App\Http\Controllers\payments\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\staffs\StaffHomeController;
use App\Http\Controllers\staffs\StaffOrderController;
use App\Http\Controllers\staffs\StaffProductController;
use App\Http\Controllers\staffs\StaffShopmaterialController;
use App\Http\Controllers\staffs\StaffController;
use App\Http\Controllers\staffs\StaffLichlamviecController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\KhachHangMiddleware;
use App\Http\Middleware\NhanVienMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Start - user
//Route Home
Route::prefix('/')->group(function(){
    Route::get('',[HomeController::class, 'home'])->name('home');
    Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');
    Route::get('/lien-he', [HomeController::class, 'contact'])->name('contact');
    Route::post('/lien-he/submit',[ContactController::class,'submitContactForm'])->name('contact.submit');
    Route::post('/select-store', [StoreController::class, 'selectStore'])->name('select.store');
    Route::get('/tra-cuu-don-hang', [CustomerOrderController::class, 'showFormTraCuuDonHang'])->name('traCuuDonHang.show');
    Route::post('/tra-cuu-don-hang', [CustomerOrderController::class, 'traCuuDonHang'])->name('traCuuDonHang.search');
    Route::post('/orders/{orderId}/cancel', [CustomerOrderController::class, 'cancelOrderByCustomer'])->name('customer.orders.cancel');
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

});
Route::get('/stores', [StoreController::class, 'index']);        
Route::post('/stores/nearest', [StoreController::class, 'ganNhat']);      
Route::post('/get-address', [StoreController::class, 'getAddress']);
// routes/web.php  (hoặc api.php)
Route::get('/session/location', function () {
    return response()->json([
        'lat'     => session('user_lat'),
        'lng'     => session('user_lng'),
        'address' => session('user_address')
    ]);
});

//Route sản phẩm
Route::prefix('products')->group(function(){
    Route::get('/',[ProductController::class, 'productList'])->name('product');
    Route::get('/categories-products/{id}',[ProductController::class,'listProductsByCategoryParent'])->name('product.category.list');
    Route::get('/product-detail/{slug}',[ProductController::class, 'productDetail'])->name('product.detail');
    Route::get('/search', [ProductController::class, 'searchProduct'])->name('product.search');
    Route::delete('/history/remove-product/{product_id}', [ProductController::class, 'removeProductFromViewHistory'])->name('history.removeProduct');
    Route::post('/history/clear-all', [ProductController::class, 'clearAllViewHistory'])->name('history.clearAll');
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
    Route::get('/voucher/check', [CartController::class, 'check']);
});

//Route Payment
Route::prefix('payment')->group(function(){
    Route::post('/',[PaymentController::class,'payment'])->name('payment');
    Route::get('/payos-return', [Napas247Controller::class, 'handleReturn'])->name('payos.return');
    Route::get('/payos-cancel', [Napas247Controller::class, 'handleCancel'])->name('payos.cancel');
    Route::get('/status/{orderCode}', [Napas247Controller::class, 'checkPaymentStatus']);
    Route::get('/checkout-status',[PaymentController::class,'checkoutStatus'])->name('checkout_status');
});

//Tin tức
Route::prefix('blog')->group(function(){
    Route::get('/', [BlogController::class, 'index'])->name('blog');
    Route::get('/{slug}', [BlogController::class, 'detail'])->name('blog.detail');
});

//Khách hàng
Route::prefix('customer')->middleware(KhachHangMiddleware::class)->group(function(){
    Route::get('/profile', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/order-history',[CustomerOrderController::class,'index'])->name('customer.order.history');
    Route::get('/favorites',[CustomerFavoriteController::class,'showFavorite'])->name('favorite.show');
    Route::put('/profile/update',[CustomerController::class,'updateInfo'])->name('customer.update');
    Route::post('/store/address',[CustomerController::class,'storeAddress'])->name('customer.address.store');
    Route::get('/yeu-thich/danh-sach', [CustomerController::class, 'showFavorite'])->name('favorite.list');
    Route::post('/review', [CustomerReviewController::class, 'store']);
    Route::get('/api/review-by-order/{ma_hoa_don}', [CustomerReviewController::class, 'getByOrder']);

});

Route::post('/favorite/toggle/{id}', [CustomerFavoriteController::class, 'favoriteProduct'])->name('favorite.toggle');
Route::get('api/dia-chi', [CustomerController::class, 'getDiaChi'])->name('api.diachi');

//End - User
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//auth adim & staff

Route::prefix('auth')->group(function(){
    Route::get('/login',[AdminLoginController::class,'showLoginForm'])->name('admin.login.show');
    Route::post('/login',[AdminLoginController::class,'login'])->name('admin.login');
    Route::post('/logout',[AdminLoginController::class,'logout'])->name('admin.logout');
});
///////////////////////////////////////////////////////////////////////////////////////////
//Start - Admin
//Route Admin home
Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function(){
    Route::get('/', [AdminHomeController::class, 'index'])->name('admin');
    Route::get('/thong-tin-website',[AdminHomeController::class,'thongTinWebsite'])->name('admin.thongTinWebSite');
    Route::put('/thong-tin-website/update',[AdminHomeController::class, 'updateThongTinWebsite'])->name('admin.thong_tin_website.update');
});
//Route Products Admin
Route::prefix('admin/products')->middleware(AdminMiddleware::class)->group(function(){
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
Route::prefix('admin/categories')->middleware(AdminMiddleware::class)->name('admins.category.')->group(function () {
    Route::get('/', action: [AdminCategoryController::class, 'index'])->name('index');
    Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
    Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminCategoryController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/archive', [AdminCategoryController::class, 'archive'])->name('archive');
    Route::get('/archive', [AdminCategoryController::class, 'archiveIndex'])->name('archive.index'); // Hiển thị danh mục lưu trữ
    Route::post('/{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
    //route for checkbox
    Route::post('/bulk-archive', [AdminCategoryController::class, 'bulkArchive'])->name('bulk-archive');
    Route::post('/bulk-restore', [AdminCategoryController::class, 'bulkRestore'])->name('bulk-restore');
    Route::post('/bulk-delete', [AdminCategoryController::class, 'bulkDelete'])->name('bulk-delete');
});
//Route Material Admin
Route::prefix('admin/materials')->middleware(AdminMiddleware::class)->name('admins.material.')->group(function () {
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
    Route::post('/bulk-action', [AdminMaterialController::class, 'bulkAction'])->name('bulk');

});

//Route Vouchers Admin
Route::prefix('admin/vouchers')->middleware(AdminMiddleware::class)->name('admin.vouchers.')->group(function(){
    Route::get('',[AdminVoucherController::class,'listVouchers'])->name('list');
    Route::get('/list-vouchers-off',[AdminVoucherController::class,'listVouchersOff'])->name('list-vouchers-off');
    Route::get('/add-voucher',[AdminVoucherController::class,'showVoucherForm'])->name('form');
    Route::post('/add-voucher',[AdminVoucherController::class,'addVoucher'])->name('add');
    Route::post('/on-or-off-voucher/{id}',[AdminVoucherController::class,'onOrOffVoucher'])->name('on-or-off-voucher');
    Route::post('/archive-voucher{id}',[AdminVoucherController::class,'voucherArchive'])->name('archive-voucher');
    Route::post('/delete-voucher/{id}', [AdminVoucherController::class, 'deleteVoucher'])->name('delete');
    Route::get('/edit-voucher/{id}', [AdminVoucherController::class, 'editVoucherForm'])->name('edit');
    Route::post('/{id}/edit', [AdminVoucherController::class, 'editVoucher'])->name('update');
    Route::post('/bulk-action', [AdminVoucherController::class, 'bulkAction'])->name('bulk-action');
    Route::get('/deleted', [AdminVoucherController::class, 'showDeletedVouchers'])->name('deleted-list');
});

//Route Supplier Admin
Route::prefix('admin/suppliers')->middleware(AdminMiddleware::class)->name('admins.supplier.')->group(function () {
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

//Route Admin NhanVien
Route::prefix('admin/nhanviens')->middleware(AdminMiddleware::class)->name('admins.nhanvien.')->group(function () {
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
    Route::post('/archive/{id}', action: [AdminNhanvienController::class, 'archive'])->name('archive');  // Lưu trữ
    Route::get('/archived', [AdminNhanvienController::class, 'archived'])->name('archived');    // Danh sách lưu trữ
    Route::patch('/restore/{id}', action: [AdminNhanvienController::class, 'restore'])->name('restore'); // Khôi phục
    Route::patch('/restore-bulk', [AdminNhanvienController::class, 'bulkRestore'])->name('restore.bulk');
    Route::patch('/archive/bulk', [AdminNhanvienController::class, 'archiveBulk'])->name('archive.bulk');
});

//Route Admin Order
Route::prefix('admin/orders')->middleware(AdminMiddleware::class)->group(function(){
    Route::get('/',[AdminOrderController::class,'index'])->name('admin.orders.list');
    Route::get('/{id}/detail', [AdminOrderController::class, 'detail'])->name('admin.orders.detail');
    Route::post('/filter', [AdminOrderController::class, 'filter'])->name('admin.orders.filter');
});

//Route Admin Dashboard
Route::prefix('admin/dashboard')->group(function() {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

//Route Admin Shop Material
Route::prefix('admin/shop-materials')->middleware(AdminMiddleware::class)->name('admins.shopmaterial.')->group(function(){
    Route::get('/', [AdminShopmaterialController::class, 'index'])->name('index');
    Route::get('/create', [AdminShopmaterialController::class, 'create'])->name('create');
    Route::post('/store', [AdminShopmaterialController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminShopmaterialController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [AdminShopmaterialController::class, 'update'])->name('update');
    Route::get('/import-page', [AdminShopmaterialController::class, 'showImportPage'])->name('showImportPage');
    Route::post('/import', [AdminShopmaterialController::class, 'import'])->name('import');
    Route::get('/export-page',[AdminShopmaterialController::class,'showExportPage'])->name('showExportPage');
    Route::post('/export',[AdminShopmaterialController::class,'export'])->name('export');
    Route::post('/destroy', [AdminShopmaterialController::class, 'destroy'])->name('destroy');
    Route::get('/destroy-page', [AdminShopmaterialController::class, 'showDestroyPage'])->name('showDestroyPage');
});

//Route Admin Blog
Route::prefix('admin/blog')->middleware(AdminMiddleware::class)->group(function(){
    Route::get('/',[AdminBlogController::class,'index'])->name('admin.blog.index');
    Route::get('/add-blog',[AdminBlogController::class,'showFormBlog'])->name('admin.blog.form');
    Route::post('/add-blog',[AdminBlogController::class,'add'])->name('admin.blog.add');
});


//Route Admin Sản phẩm cho các cừa hàng.
Route::prefix('admin/product-shop')->middleware(AdminMiddleware::class)->group(function(){
    Route::get('/',[AdminProductShopController::class,'index'])->name('admin.product-shop.index');
    Route::post('/add-to-shop', [AdminProductShopController::class, 'addToShop'])->name('admin.product-shop.addtoshop');
    Route::post('/delete', [AdminProductShopController::class, 'deleteProductShop'])->name('admin.product-shop.delete');

});

Route::prefix('/admin/store')->middleware(AdminMiddleware::class)->group(function(){
    Route::get('/',[AdminStoreController::class,'index'])->name('admin.store.index');
    Route::post('/add',[AdminStoreController::class,'addStore'])->name('admin.store.add');
    Route::post('/toggle', [AdminStoreController::class, 'toggle'])->name('admin.store.toggle');
});

//End - Admin
///////////////////////////////////////////////////////////////////////////
//Start - Staff
//Route Staff home

Route::prefix('staff')->middleware(NhanVienMiddleware::class)->group(function(){
    Route::get('', [StaffHomeController::class, 'index'])->name('staff');
});
//Route Staff products store
Route::prefix('staff/product-store')->middleware(NhanVienMiddleware::class)->group(function(){
    Route::get('', [StaffProductController::class, 'index'])->name('staff.productStore');
    Route::post('/update-status', [StaffProductController::class, 'updateStatus'])->name('staff.productStore.update-status');
});
//Route Staff Order
Route::prefix('staff/orders')->middleware(NhanVienMiddleware::class)->group(function(){
    Route::get('/', [StaffOrderController::class, 'orderStore'])->name('staff.orders.list');
    Route::get('/{id}/detail', [StaffOrderController::class, 'detail'])->name('staff.orders.detail');
    Route::post('/filter', [StaffOrderController::class, 'filter'])->name('staff.orders.filter');
    Route::post('/update-status', [StaffOrderController::class, 'updateStatusOrder'])->name('staff.orders.updateStatus');
});

//Route Staff Dashboard
Route::prefix('staff/dashboard')->middleware(NhanVienMiddleware::class)->group(function() {
    Route::get('/', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
});

//Route Staff Shop material
Route::prefix('staff/shop-material')->middleware(NhanVienMiddleware::class)->name('staffs.shop_materials.')->group(function() {
    Route::get('/', action: [StaffShopmaterialController::class, 'index'])->name('index');
    Route::get('/create', [StaffShopmaterialController::class, 'create'])->name('create');
    Route::post('/store', [StaffShopmaterialController::class, 'store'])->name('store');
    //Route::get('/{id}/edit', [AdminShopmaterialController::class, 'edit'])->name('edit');
    //Route::put('/{id}/update', [AdminShopmaterialController::class, 'update'])->name('update');
    Route::get('/import-page', [StaffShopmaterialController::class, 'showImportPage'])->name('showImportPage');
    Route::post('/import', [StaffShopmaterialController::class, 'import'])->name('import');
    Route::get('/export-page',[StaffShopmaterialController::class,'showExportPage'])->name('showExportPage');
    Route::post('/export',[StaffShopmaterialController::class,'export'])->name('export');
    Route::post('/destroy', [StaffShopmaterialController::class, 'destroy'])->name('destroy');
    Route::get('/destroy-page', [StaffShopmaterialController::class, 'showDestroyPage'])->name('showDestroyPage');
});

//Route Staff NhanVien
Route::prefix('staff/nhanviens')->middleware(NhanVienMiddleware::class)->name('staffs.nhanviens.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/create', [StaffController::class, 'create'])->name('create');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    Route::get('/{ma_nhan_vien}/edit', [StaffController::class, 'edit'])->name('edit');
    Route::put('/{ma_nhan_vien}', [StaffController::class, 'update'])->name('update');
    Route::delete('/{ma_nhan_vien}', [StaffController::class, 'destroy'])->name('destroy');
    //Thử thách Admin phân công lịch làm việc
    Route::get('/phan-cong-lich', [StaffLichlamviecController::class, 'showForm'])->name('lich.showForm');
    Route::post('/phan-cong-lich', [StaffLichlamviecController::class, 'assignWork'])->name('lich.assignWork');
    Route::get('/lich-lam-viec', [StaffLichlamviecController::class, 'showLichTheoTuan'])->name('lich.tuan');
    //Tạm nghỉ cho nhân viên
    Route::post('/archive/{id}',  [StaffController::class, 'archive'])->name('archive');  // Lưu trữ
    Route::get('/archived', [StaffController::class, 'archived'])->name('archived');    // Danh sách lưu trữ
    Route::patch('/restore/{id}',  [StaffController::class, 'restore'])->name('restore'); // Khôi phục
    Route::patch('/restore-bulk', [StaffController::class, 'bulkRestore'])->name('restore.bulk');
    Route::patch('/archive/bulk', [StaffController::class, 'archiveBulk'])->name('archive.bulk');
});
//End - Staff


