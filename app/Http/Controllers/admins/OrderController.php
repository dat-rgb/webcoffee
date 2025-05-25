<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        
        $orders = HoaDon::with('chiTietHoaDon');

        $ViewData = [
            'title' => 'Đơn hàng | CDMT Coffee & Tea',
            'subtitle' => 'Quản lý đơn hàng',
            'orders' => $orders
        ];

        return view('admins.order.index', $ViewData);
    }
}
