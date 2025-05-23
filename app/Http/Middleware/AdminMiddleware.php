<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login.show');
        } else if ($user->trang_thai == 3) {
            toastr()->error('Bạn không có quyền truy cập trang này.');
            return redirect()->route('admin.login.show');
        }
        return redirect()->route('admin.login.show');
    }
}
