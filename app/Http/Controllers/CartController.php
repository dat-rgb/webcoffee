<?php

namespace App\Http\Controllers;

use App\Models\CuaHang;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //Hiển thị cart
    public function cart(){
        $cart = session()->get('cart', []);  
        $productSizes = [];

        foreach ($cart as $item) {
            $productId = $item['product_id'];

            if (!isset($productSizes[$productId])) {
                $sizes = DB::table('thanh_phan_san_phams')
                    ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
                    ->where('thanh_phan_san_phams.ma_san_pham', $productId)
                    ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
                    ->distinct()
                    ->get();

                $productSizes[$productId] = $sizes;
            }
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['money'];
        }

        $cartCount = count($cart);

        $viewData = [
            'title' => 'Giỏ Hàng | CMDT Coffee & Tea',
            'cart' => $cart,   
            'productSizes' => $productSizes,
            'total' => $total,
            'cartCount' => $cartCount
        ];

        return view('clients.pages.carts.index', $viewData);
    }
    public function loadCart()
    {
        $cart = session()->get('cart', []);
        $productSizes = [];
        $total = 0;
        //$cartCount = 0;
        $cartCount = count($cart);

        foreach ($cart as $item) {
            $total += $item['money'];
            //$cartCount += $item['product_quantity'];

            $productId = $item['product_id'];
            if (!isset($productSizes[$productId])) {
                $productSizes[$productId] = DB::table('thanh_phan_san_phams')
                    ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
                    ->where('thanh_phan_san_phams.ma_san_pham', $productId)
                    ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
                    ->distinct()
                    ->get();
            }
        }

        if ($cartCount > 0) {
            $cartTableHtml = view('clients.pages.carts.cart_table', compact('cart', 'productSizes'))->render();
            $cartTotalHtml = view('clients.pages.carts.cart_total', compact('cart', 'total'))->render();

            $html = '
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12 cart-table">' . $cartTableHtml . '</div>
                    <div class="col-lg-4 cart-total">' . $cartTotalHtml . '</div>
                </div>
            </div>';

            return response()->json([
                'html' => $html,
                'cartCount' => $cartCount
            ]);
        } else {
            $emptyHtml = view('clients.pages.carts.cart_empty')->render();
            return response()->json([
                'html' => $emptyHtml,
                'cartCount' => $cartCount
            ]);
        }
    }
    //chech count
    public function getCartCount()
    {
        try {
            $cart = session('cart', []);
           
            $cartCount = count($cart);

            return response()->json(['cartCount' => $cartCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi lấy số lượng giỏ hàng'], 500);
        }
    }
    //Check quantity
    public function checkCartQuantity(Request $request) {
        $productId = $request->product_id;
        $sizeId = $request->size_id;

        $cart = session()->get('cart', []);
        $key = $productId . '_' . $sizeId;

        $quantity = isset($cart[$key]) ? $cart[$key]['product_quantity'] : 0;

        return response()->json(['quantity' => $quantity]);
    }
    public function checkStore($productId, $storeId, $sizeId, $newQuantity, $mode = 'add', $oldSizeId = null, $oldQuantity = 0)
    {
        $store = CuaHang::where('ma_cua_hang', $storeId)->first();
        if (!$store) {
            return response()->json(['error' => 'Không tìm thấy cửa hàng.'], 404);
        }

        $cart = session()->get('cart', []);

        $totalUsedIngredients = [];

        foreach ($cart as $key => $item) {
            $itemProductId = $item['product_id'];
            $itemSizeId = $item['size_id'];
            $quantityInCart = (int)$item['product_quantity'];

            // Nếu update, bỏ nguyên liệu của sản phẩm cũ (oldSizeId, oldQuantity)
            if ($mode === 'update' && $itemProductId == $productId && $itemSizeId == $oldSizeId) {
                // Bỏ đi số lượng cũ
                $quantityInCart -= $oldQuantity;
                if ($quantityInCart <= 0) {
                    continue; // không còn trong giỏ nữa
                }
            }

            $ingredients = DB::table('thanh_phan_san_phams')
                ->where('ma_san_pham', $itemProductId)
                ->where('ma_size', $itemSizeId)
                ->get();

            foreach ($ingredients as $ingredient) {
                $idNL = $ingredient->ma_nguyen_lieu;
                if (!isset($totalUsedIngredients[$idNL])) {
                    $totalUsedIngredients[$idNL] = 0;
                }
                $totalUsedIngredients[$idNL] += $ingredient->dinh_luong * $quantityInCart;
            }
        }

        // Lấy nguyên liệu của sản phẩm mới (size mới)
        $ingredientsForNewSize = DB::table('thanh_phan_san_phams as tpsp')
            ->join('nguyen_lieus as nl', 'tpsp.ma_nguyen_lieu', '=', 'nl.ma_nguyen_lieu')
            ->leftJoin('cua_hang_nguyen_lieus as chnl', function ($join) use ($storeId) {
                $join->on('tpsp.ma_nguyen_lieu', '=', 'chnl.ma_nguyen_lieu')
                    ->on('chnl.ma_cua_hang', '=', DB::raw("'" . $storeId . "'"));
            })
            ->select(
                'tpsp.ma_nguyen_lieu',
                'nl.ten_nguyen_lieu',
                'tpsp.dinh_luong',
                'tpsp.don_vi',
                'chnl.so_luong_ton'
            )
            ->where('tpsp.ma_san_pham', $productId)
            ->where('tpsp.ma_size', $sizeId)
            ->get();

        if ($ingredientsForNewSize->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy nguyên liệu cho sản phẩm này.'
            ];
        }

        // Cộng nguyên liệu của sản phẩm mới theo số lượng mới
        foreach ($ingredientsForNewSize as $ingredient) {
            $idNL = $ingredient->ma_nguyen_lieu;
            $required = $ingredient->dinh_luong * $newQuantity;

            if (!isset($totalUsedIngredients[$idNL])) {
                $totalUsedIngredients[$idNL] = 0;
            }

            $totalUsedIngredients[$idNL] += $required;
        }

        // Check tồn kho nguyên liệu
        foreach ($ingredientsForNewSize as $ingredient) {
            $idNL = $ingredient->ma_nguyen_lieu;
            $stock = $ingredient->so_luong_ton;

            if (is_null($stock)) {
                return [
                    'success' => false,
                    'message' => "Sản phẩm tại cửa hàng không đủ số lượng bán. Hãy chọn sang cửa hàng khác."
                ];
            }

            if ($totalUsedIngredients[$idNL] > $stock) {
                return [
                    'success' => false,
                    'message' => "Nguyên liệu không đủ cung cấp cho sản phẩm trong giỏ hàng, vui lòng giảm số lượng hoặc chọn sản phẩm khác."
                ];
            }
        }

        // Giới hạn số lượng max 99
        $maxAllowedQuantity = 99;
        if ($newQuantity > $maxAllowedQuantity && $mode === 'add') {
            return [
                'success' => false,
                'message' => "Bạn chỉ có thể mua tối đa {$maxAllowedQuantity} sản phẩm cho mỗi loại."
            ];
        }

        return [
            'success' => true,
            'message' => 'Đủ nguyên liệu trong kho.'
        ];
    }
    //add to cart
    public function addToCart(Request $request, $id)
    {
        
        try {
            $product = SanPham::where('ma_san_pham', $id)->first();
            if (!$product) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm.'], 404);
            }

            $storeID =  $request->input('store');
          
            $store = CuaHang::where('ma_cua_hang',$storeID);
            if (!$store) {
                return response()->json(['error' => 'Không tìm thấy cửa hàng.'], 404);
            }

            $size = $request->input('size');
            $quantity = $request->input('quantity') ?: 1;
            $sizeInfo = Sizes::where('ma_size', $size)->first();
            $cartKey = $id . '_' . $size;

            $check = $this->checkStore($id, $storeID, $size, $quantity,'add');

            if (!$check['success']) {
                return response()->json([
                    'error' => $check['message']
                ], 400);
            }

            $cart = session()->get('cart', []); 

            $size_price =  $sizeInfo->gia_size;
            $pro_price = $product->gia + $size_price;
            $money = $pro_price * $quantity;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['product_quantity'] += $quantity;
                $cart[$cartKey]['money'] = $cart[$cartKey]['product_quantity'] * ($cart[$cartKey]['product_price'] + $cart[$cartKey]['size_price']);

            } else {
                $cart[$cartKey] = [
                    'product_id' => $id,
                    'product_name' => $product->ten_san_pham,
                    'product_price' => $product->gia,
                    'product_quantity' => $quantity,
                    'product_image' =>$product->hinh_anh,
                    'product_slug' =>$product->slug,
                    'size_id' => $size,
                    'size_price' => $sizeInfo->gia_size,
                    'size_name' => $sizeInfo->ten_size,
                    'money' => $money
                ];
            }
          
            session()->put('cart', $cart);

            $cartCount = count($cart);

            return response()->json(['success' => 'Đã thêm sản phẩm vào giỏ hàng.', 'cartCount' => $cartCount]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //change quantity
    public function updateQuantity(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $sizeId = $request->input('size_id');
            $quantity = (int) $request->input('quantity');

            $cartKey = $productId . '_' . $sizeId;
            $cart = session()->get('cart', []);

            if (!isset($cart[$cartKey])) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng.'], 400);
            }

            $storeID = session('selected_store_id'); 

            $oldQuantity = $cart[$cartKey]['product_quantity'];
            $oldSizeId = $cart[$cartKey]['size_id'];
            
            $check = $this->checkStore($productId, $storeID, $sizeId, $quantity, 'update', $oldSizeId, $oldQuantity);
            if (!$check['success']) {
                return response()->json(['error' => $check['message']], 400);
            }


            // Cập nhật lại số lượng và tiền
            $cart[$cartKey]['product_quantity'] = $quantity;
            $cart[$cartKey]['money'] = $quantity * (
                $cart[$cartKey]['product_price'] + $cart[$cartKey]['size_price']
            );

            session()->put('cart', $cart);

            // Tính lại tổng tiền
            $subtotal = collect($cart)->sum('money');
            $shippingFee = 0; // Tuỳ logic bạn
            $total = $subtotal + $shippingFee;

            return response()->json([
                'success' => 'Cập nhật thành công',
                'money' => $cart[$cartKey]['money'],
                'money_format' => number_format($cart[$cartKey]['money'], 0, ',', '.') . ' đ',
                'subtotal_format' => number_format($subtotal, 0, ',', '.') . ' đ',
                'shipping_fee_format' => number_format($shippingFee, 0, ',', '.') . ' đ',
                'total_format' => number_format($total, 0, ',', '.') . ' đ',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
    //change size 
    public function updateSize(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $oldSizeId = $request->input('old_size_id');
            $newSizeId = $request->input('new_size_id');
            $storeID = session('selected_store_id');

            $cart = session()->get('cart', []);

            $oldKey = $productId . '_' . $oldSizeId;
            $newKey = $productId . '_' . $newSizeId;

            if (!isset($cart[$oldKey])) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng.'], 400);
            }

            $product = $cart[$oldKey];
            $quantity = $product['product_quantity'];
            $productPrice = $product['product_price'];

            $size = DB::table('sizes')->where('ma_size', $newSizeId)->first();
            if (!$size) {
                return response()->json(['error' => 'Size không hợp lệ.'], 400);
            }

            $newMoney = $quantity * ($productPrice + $size->gia_size);
            $newQuantity = 0;

            $newCart = $cart;

            if ($oldKey !== $newKey) {
                if (isset($newCart[$newKey])) {
                    $newCart[$newKey]['product_quantity'] += $quantity;
                    $newCart[$newKey]['money'] += $newMoney;
                    $newQuantity = $newCart[$newKey]['product_quantity'];
                } else {
                    $newCart[$newKey] = [
                        'product_id' => $productId,
                        'product_name' => $product['product_name'],
                        'product_price' => $productPrice,
                        'product_quantity' => $quantity,
                        'product_image' => $product['product_image'],
                        'product_slug' => $product['product_slug'],
                        'size_id' => $newSizeId,
                        'size_price' => $size->gia_size,
                        'size_name' => $size->ten_size,
                        'money' => $newMoney,
                    ];
                    $newQuantity = $quantity;
                }
                unset($newCart[$oldKey]);
            } else {
                $newCart[$oldKey]['size_id'] = $newSizeId;
                $newCart[$oldKey]['size_price'] = $size->gia_size;
                $newCart[$oldKey]['size_name'] = $size->ten_size;
                $newCart[$oldKey]['money'] = $newMoney;
                $newQuantity = $newCart[$oldKey]['product_quantity'];
            }

            // Gọi checkStore với cart mới để validate nguyên liệu
            $check = $this->checkStore($productId, $storeID, $newSizeId, $newQuantity, 'update', $oldSizeId, $quantity);

            if (!$check['success']) {
                // Ko cập nhật cart, trả về lỗi luôn
                return response()->json(['error' => $check['message']], 400);
            }

            // Nếu checkStore OK, mới cập nhật session cart chính thức
            session()->put('cart', $newCart);

            $subtotal = collect($newCart)->sum('money');
            $shippingFee = 0; // tính phí ship riêng
            $total = $subtotal + $shippingFee;

            return response()->json([
                'success' => 'Cập nhật size thành công',
                'money' => $newMoney,
                'money_format' => number_format($newMoney, 0, ',', '.') . ' đ',
                'subtotal_format' => number_format($subtotal, 0, ',', '.') . ' đ',
                'shipping_fee_format' => number_format($shippingFee, 0, ',', '.') . ' đ',
                'total_format' => number_format($total, 0, ',', '.') . ' đ',
                'cartCount' => count($newCart),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //delete 
    public function deleteProduct(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');

        // Lấy giỏ hàng từ session (mảng)
        $cart = session()->get('cart', []);

        $key = $productId . '_' . $sizeId;

        if (isset($cart[$key])) {
            // Xóa sản phẩm khỏi giỏ hàng
            unset($cart[$key]);

            // Cập nhật lại giỏ hàng trong session
            session()->put('cart', $cart);

            // Tính lại tổng tiền
            $subtotal = collect($cart)->sum('money');
            $shippingFee = $subtotal >= 0;
            $total = $subtotal + $shippingFee;

            $cartCount = count($cart);
            
            return response()->json([
                'message' => 'Xoá thành công',
                'subtotal_format' => number_format($subtotal, 0, ',', '.') . ' đ',
                'shipping_fee_format' => number_format($shippingFee, 0, ',', '.') . ' đ',
                'total_format' => number_format($total, 0, ',', '.') . ' đ',
                'cartCount' => $cartCount, 
            ]);
        }

        // Nếu sản phẩm không tồn tại trong giỏ hàng thì báo lỗi
        return response()->json([
            'error' => 'Sản phẩm không tồn tại trong giỏ hàng.'
        ], 400);
    }
    public function getVoucher(){
        $vouchers = KhuyenMai::where('trang_thai',1)->get();
        return $vouchers;
    }
    public function check(Request $request)
    {
        $code = $request->input('code');
        $voucher = KhuyenMai::where('ma_voucher', $code)
            ->whereNull('deleted_at')
            ->whereDate('ngay_bat_dau', '<=', now())
            ->whereDate('ngay_ket_thuc', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher không tồn tại hoặc đã hết hạn.'
            ]);
        }

        return response()->json([
            'success' => true,
            'voucher' => [
                'ma_voucher' => $voucher->ma_voucher,
                'ten_voucher' => $voucher->ten_voucher,
                'gia_tri_giam' => $voucher->gia_tri_giam,
                'giam_gia_max' => $voucher->giam_gia_max,
                'dieu_kien_ap_dung' => $voucher->dieu_kien_ap_dung,
                'hinh_anh' => $voucher->hinh_anh,
                'ngay_ket_thuc' => \Carbon\Carbon::parse($voucher->ngay_ket_thuc)->format('d/m/Y')
            ]
        ]);
    }
    //check out page
    public function checkout() {
        $cart = session()->get('cart', []);
        $store = session('selected_store_id');
        $user = auth()->user();

        if(!$cart){
            return redirect()->route('cart');
        }
        if (!$store) {
            toastr()->error('Vui lòng chọn cửa hàng trước khi thanh toán');
            return redirect()->back();
        }

        foreach ($cart as $item) {
            $product = SanPham::where('ma_san_pham', $item['product_id'])->first();
            $size = Sizes::where('ma_size', $item['size_id'])->first();

            $expectedPrice = $product->gia;
            $expectedSizePrice = $size->gia_size;

            // So sánh riêng từng phần thay vì tổng
            if (
                round($item['product_price'], 0) != round($expectedPrice, 0) ||
                round($item['size_price'], 0) != round($expectedSizePrice, 0)
            ) {
                $cartKey = $item['product_id'] . '_' . $item['size_id'];
                $item['product_price'] = $expectedPrice;
                $item['size_price'] = $expectedSizePrice;
                $item['money'] = ($expectedPrice + $expectedSizePrice) * $item['product_quantity'];

                $cart[$cartKey] = $item;
                session()->put('cart', $cart);

                toastr()->warning("Giá của sản phẩm trong giỏ hàng đã thay đổi. Đã cập nhật lại trong giỏ hàng.");
                return redirect()->back();
            }
        }

        // Lấy info user
        $ma_tai_khoan = null;
        $khach_hang = null;
        $email = null;

        if ($user) {
            $ma_tai_khoan = $user->ma_tai_khoan;
            $khach_hang = KhachHang::with('taiKhoan')
                ->where('ma_tai_khoan', $ma_tai_khoan)
                ->first();
            $email = $khach_hang?->taiKhoan?->email;
        }

        // Lấy danh sách size theo từng sản phẩm (nếu cần)
        $productSizes = [];
        foreach ($cart as $item) {
            $productId = $item['product_id'];
            if (!isset($productSizes[$productId])) {
                $sizes = DB::table('thanh_phan_san_phams')
                    ->join('sizes', 'thanh_phan_san_phams.ma_size', '=', 'sizes.ma_size')
                    ->where('thanh_phan_san_phams.ma_san_pham', $productId)
                    ->select('sizes.ma_size', 'sizes.ten_size', 'sizes.gia_size')
                    ->distinct()
                    ->get();

                $productSizes[$productId] = $sizes;
            }
        }

        $subTotal = array_sum(array_column($cart, 'money'));
        
        $shippingFee = 0;
        if($subTotal < 200000){
            $shippingFee = 30000;
        }
        $total = $subTotal + $shippingFee;

        $cartCount = count($cart);
        $vouchers = $this->getVoucher();

        return view('clients.pages.carts.checkout', [
            'title' => 'Check out | CMDT Coffee & Tea',
            'cart' => $cart,
            'productSizes' => $productSizes,
            'subTotal' => $subTotal,
            'shippingFee' => $shippingFee,
            'total' => $total,
            'cartCount' => $cartCount,
            'ma_tai_khoan' => $ma_tai_khoan,
            'khach_khach' => $khach_hang,
            'email' => $email,
            'vouchers' => $vouchers
        ]);
    }
}
