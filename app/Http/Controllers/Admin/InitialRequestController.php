<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InitialRequest;
use App\Models\Project;
use App\Notifications\InitialRequestAccepted;
use App\Notifications\InitialRequestRejected;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage; // برای ایجاد URL دانلود

class InitialRequestController extends Controller
{
    /**
     * نمایش لیست تمام درخواست‌های اولیه
     */
    public function index(Request $request)
    {

        // دریافت پارامترهای فیلتر از درخواست
        $filters = $request->only(['search', 'status']);

        // ایجاد کوئری پایه
        $query = InitialRequest::with(['user:id,name', 'service:id,name', 'project:id,initial_request_id'])->latest();

        // اعمال فیلتر جستجو
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // اعمال فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // صفحه‌بندی نتایج
        $requests = $query->paginate(15)
            ->withQueryString() // اضافه کردن پارامترهای فیلتر به لینک‌های صفحه‌بندی
            ->through(fn ($request) => [
                'id' => $request->id,
                'user_name' => $request->user->name,
                'service_name' => $request->service->name,
                'status' => $request->status,
                'created_at' => $request->created_at->format('Y/m/d H:i'),
                'project_id' => $req->project->id ?? null,
            ]);

        return Inertia::render('Admin/InitialRequests/Index', [
            'requests' => $requests,
            'filters' => $filters, // ارسال فیلترهای فعلی به ویو
        ]);
    }

    /**
     * نمایش جزئیات یک درخواست اولیه خاص
     */
    public function show(InitialRequest $request)
    {
        $request->load(['user:id,name,email', 'service:id,name']);

        return Inertia::render('Admin/InitialRequests/Show', [
            // ما دیگر نیازی به ارسال download_url نداریم،
            // چون لینک دانلود را مستقیماً در ویو با route() می سازیم.
            'request' => [
                'id' => $request->id,
                'status' => $request->status,
                'description' => $request->description,
                'data' => $request->data,
                'admin_message' => $request->admin_message,
                'initial_file_path' => $request->initial_file_path, // فقط وجود فایل را بررسی می کنیم
                'created_at' => $request->created_at->format('Y/m/d H:i'),
                'user' => $request->user,
                'service' => $request->service,
            ],
        ]);
    }

    /**
     * قبول کردن یک درخواست اولیه و ایجاد یک پروژه جدید
     */
    public function accept(InitialRequest $request)
    {
        if ($request->status !== 'pending') {
            return back()->with('error', 'این درخواست قبلاً پردازش شده است.');
        }

        $request->update(['status' => 'accepted']);

        Project::create([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'initial_request_id' => $request->id,
            'title' => "پروژه جدید برای خدمت " . $request->service->name,
            'total_price' => $request->service->base_price,
            'current_status' => 'waiting_user_files',
        ]);

        // --- ارسال نوتیفیکیشن به کاربر ---
        $request->user->notify(new InitialRequestAccepted($request));
        // --------------------------------

        return redirect()->route('admin.initial-requests.index')->with('success', 'درخواست با موفقیت قبول شد و ایمیل اطلاع‌رسانی ارسال گردید.');
    }

        /**
     * رد کردن یک درخواست اولیه
     */
        public function reject(Request $request, InitialRequest $initialRequest)
        {
        $request->validate(['admin_message' => 'required|string|max:1000']);

        if ($initialRequest->status !== 'pending') {
            return back()->with('error', 'این درخواست قبلاً پردازش شده است.');
        }

        $initialRequest->update([
            'status' => 'rejected',
            'admin_message' => $request->input('admin_message'),
            'rejected_at' => now(),
        ]);

        // --- ارسال نوتیفیکیشن به کاربر ---
        $initialRequest->user->notify(new InitialRequestRejected($initialRequest));
        // --------------------------------

        return redirect()->route('admin.initial-requests.index')->with('success', 'درخواست با موفقیت رد شد و ایمیل اطلاع‌رسانی ارسال گردید.');
        }



        public function downloadAttachment(InitialRequest $request)
    {
        // اطمینان از اینکه فایل وجود دارد
        if (!$request->initial_file_path || !Storage::disk('private')->exists($request->initial_file_path)) {
            abort(404, 'File not found.');
        }

        // دانلود فایل
        return Storage::disk('private')->download($request->initial_file_path);
    }
}
