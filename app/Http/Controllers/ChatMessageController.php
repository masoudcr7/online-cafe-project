<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatMessageController extends Controller
{
    /**
     * Store a new chat message for a project.
     */
    public function store(Request $request, Project $project)
    {
        // سیاست دسترسی (Authorization)
        $user = Auth::user();
        $adminUser = Auth::guard('admin')->user();

        if (($user && $user->id !== $project->user_id) && !$adminUser) {
            abort(403, 'Unauthorized action.');
        }

        // اعتبارسنجی
        $request->validate([
            'message' => 'required|string|max:2000',
            // در آینده، فایل پیوست را هم اینجا اعتبارسنجی می کنیم
        ]);

        // تعیین فرستنده (کاربر عادی یا ادمین)
        $sender = $adminUser ?? $user;

        // ایجاد پیام
        $project->messages()->create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'message' => $request->input('message'),
        ]);

        return back(); // کاربر را به همان صفحه باز می گرداند
    }
}
