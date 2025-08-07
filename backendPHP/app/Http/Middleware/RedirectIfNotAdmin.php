<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập và có phải là admin không
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            // Nếu không phải admin, chuyển hướng đến trang settings/appearance
            return redirect()->route('settings.appearance');
        }

        return $next($request);
    }
}