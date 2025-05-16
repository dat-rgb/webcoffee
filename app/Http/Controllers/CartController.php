<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart(){
        $cart = session()->get('cart', []);  
        $countItem = count($cart);
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

        $viewData = [
            'title' => 'Giỏ Hàng | CMDT Coffee & Tea',
            'cart' => $cart,   
            'productSizes' => $productSizes,
            'countItem' => $countItem,
            'total' => $total
        ];

        return view('clients.pages.carts.index', $viewData);
    }

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

            return response()->json(['success' => 'Đã thêm sản phẩm vào giỏ hàng.', 'cartCount' => count($cart)]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
