<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider; // این namespace ممکن است نیاز به اصلاح داشته باشد
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // اگر گارد 'admin' است، به داشبورد ادمین ریدایرکت کن
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // در غیر این صورت، به داشبورد پیش‌فرض (برای کاربران عادی) ریدایرکت کن
                // RouteServiceProvider::HOME ممکن است دیگر به این شکل در دسترس نباشد. از route() استفاده می کنیم.
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
