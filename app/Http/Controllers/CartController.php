<?php

namespace App\Http\Controllers;

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

        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += $item['product_quantity'];
        }

        $viewData = [
            'title' => 'Giỏ Hàng | CMDT Coffee & Tea',
            'cart' => $cart,   
            'productSizes' => $productSizes,
            'total' => $total,
            'cartCount' => $cartCount
        ];

        return view('clients.pages.carts.index', $viewData);
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

    //add to cart
    public function addToCart(Request $request, $id)
    {
        
        try {
            $product = SanPham::where('ma_san_pham', $id)->first();
            if (!$product) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm.'], 404);
            }
            
            $size = $request->input('size');
            $quantity = $request->input('quantity') ?: 1;
            $cartKey = $id . '_' . $size;
            
            $sizeInfo = Sizes::where('ma_size', $size)->first();
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

            $cartCount = 0;
            foreach ($cart as $item) {
                $cartCount += $item['product_quantity'];
            }

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
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ.'], 404);
            }

            // Cập nhật lại số lượng và tiền
            $cart[$cartKey]['product_quantity'] = $quantity;
            $cart[$cartKey]['money'] = $quantity * ($cart[$cartKey]['product_price'] + $cart[$cartKey]['size_price']);

            session()->put('cart', $cart);

            // Tính lại tổng
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['money'];
            }

            $shippingFee = $subtotal >= 0; 
            $total = $subtotal + $shippingFee;

            $cartCount = 0;
            foreach ($cart as $item) {
                $cartCount += $item['product_quantity'];
            }
            return response()->json([
                'success' => 'Cập nhật thành công',
                'money' => $cart[$cartKey]['money'],
                'money_format' => number_format($cart[$cartKey]['money'], 0, ',', '.') . ' đ',
                'subtotal_format' => number_format($subtotal, 0, ',', '.') . ' đ',
                'shipping_fee_format' => number_format($shippingFee, 0, ',', '.') . ' đ',
                'total_format' => number_format($total, 0, ',', '.') . ' đ',
                'cartCount' => $cartCount,
            ]);
            
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    //change size 

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

            $cartCount = 0;
            foreach ($cart as $item) {
                $cartCount += $item['product_quantity'];
            }
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
}
