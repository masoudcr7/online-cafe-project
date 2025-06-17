<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // اگر درخواست یک صفحه JSON نیست، ادامه بده
        if (! $request->expectsJson()) {
            // اگر درخواست برای پنل ادمین است، به صفحه ورود ادمین ریدایرکت کن
            if ($request->routeIs('admin.*')) {
                return route('admin.login');
            }

            // در غیر این صورت، به صفحه ورود پیش‌فرض (برای کاربران عادی) ریدایرکت کن
            return route('login');
        }

        return null;
    }
}
