<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InitialRequest;

class DashboardController extends Controller
{
    public function index()
    {
        // برای نمونه، تعداد درخواست‌های در انتظار را به داشبورد می‌فرستیم
        $pendingRequestsCount = InitialRequest::where('status', 'pending')->count();

        return Inertia::render('Admin/Dashboard', [
            'pendingRequestsCount' => $pendingRequestsCount,
        ]);
    }
}
