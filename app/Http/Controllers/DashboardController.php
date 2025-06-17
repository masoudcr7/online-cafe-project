<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function __invoke(Request $request)
    {
        // دریافت پروژه‌های کاربر وارد شده
        $projects = $request->user()->projects()
            ->with('service:id,name,slug') // بارگذاری اطلاعات خدمت مربوطه
            ->latest() // مرتب‌سازی بر اساس جدیدترین
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($project) => [
                'id' => $project->id,
                'title' => $project->title,
                'status' => $project->current_status,
                'service_name' => $project->service->name,
                'created_at' => $project->created_at->format('Y/m/d H:i'),
            ]);

        return Inertia::render('Dashboard', [
            'projects' => $projects,
        ]);
    }
}
