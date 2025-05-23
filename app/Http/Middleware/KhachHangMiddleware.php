<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class KhachHangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
//guest->mail->thông báo kèm mã đơn hàng->nhập mã đơn hàng tra cứu,...
//hóa đơn: -> trạng thái & trạng thái thanh toán
//mã hóa đơn string 50 -> 'HD000000' + hh:mm:ss:DD:MM:yyyy:
//thống kê
//transaction [momo, vnpay, paypal,..]
//