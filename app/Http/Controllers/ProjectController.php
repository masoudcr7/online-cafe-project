<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ProjectController extends Controller
{
    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        // -- سیاست دسترسی (Authorization) --
        // اطمینان از اینکه فقط کاربر صاحب پروژه یا ادمین می تواند آن را ببیند.
        // در آینده این بخش را با Laravel Policies بهینه می کنیم.
        $user = auth()->user();
        $adminUser = Auth::guard('admin')->user();

        if (($user && $user->id !== $project->user_id) && !$adminUser) {
            abort(403);
        }

        // بارگذاری اطلاعات مورد نیاز
        $project->load(['user:id,name', 'service:id,name', 'messages' => function ($query) {
            $query->with('sender:id,name')->latest();
        }]);



        // تعیین Layout بر اساس کاربر وارد شده
        $layout = $adminUser ? 'AdminLayout' : 'AuthenticatedLayout';

        return Inertia::render('Projects/Show', [
            'project' => $project,
            'layout' => $layout, // ارسال نام Layout به ویو
        ]);
    }
}
