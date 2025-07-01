<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders.{maCuaHang}', function ($user, $maCuaHang) {
    return true; 
});
