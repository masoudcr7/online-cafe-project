<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\InitialRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InitialRequestController extends Controller
{
    // نمایش فرم ثبت سفارش اولیه
    public function create(Request $request)
    {
        // دریافت تمام خدمات فعال
        // از ->get()->toArray() استفاده می کنیم تا مطمئن شویم یک آرایه PHP Plain داریم
        // همچنین، initial_form_fields را در سطح PHP به آرایه خالی تبدیل می کنیم اگر null بود.
        $services = Service::where('is_active', true)
            ->get()
            ->map(function ($service) {
                $data = $service->toArray(); // تبدیل مدل به آرایه
                // اطمینان از اینکه initial_form_fields همیشه یک آرایه است (حتی اگر در DB null باشد)
                $data['initial_form_fields'] = $service->initial_form_fields ?? [];
                return $data;
            })
            ->toArray(); // تبدیل کالکشن به آرایه نهایی

        $selectedServiceData = null;
        $selectedServiceSlug = $request->query('service');
        if ($selectedServiceSlug) {
            // پیدا کردن سرویس انتخاب شده از بین لیست کامل
            $selectedService = collect($services)->firstWhere('slug', $selectedServiceSlug); // از collect() روی آرایه استفاده می کنیم
            if ($selectedService) {
                // اگر سرویس پیدا شد، همان آرایه را به عنوان selectedServiceData استفاده می کنیم
                $selectedServiceData = $selectedService;
            }
        }

        return Inertia::render('InitialRequests/Create', [
            'services' => $services,
            'selectedService' => $selectedServiceData,
        ]);
    }

    // ... (متد store() بدون تغییر) ...
    public function store(Request $request)
    {
        // 1. Validation سختگیرانه
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'initial_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:1024'], // 1MB max
            'data' => ['nullable', 'array'],
            // می توانید اینجا بر اساس service_id، validation های بیشتری برای data اضافه کنید
            // For dynamic fields, you might need custom validation logic here or a Request class
        ]);

        $initialFilePath = null;
        if ($request->hasFile('initial_file')) {
            $file = $request->file('initial_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $initialFilePath = $file->storeAs('initial_requests', $filename, 'private');

            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                Storage::disk('private')->delete($initialFilePath);
                return back()->withErrors(['initial_file' => 'نوع فایل پشتیبانی نمی‌شود.']);
            }
        }

        $initialRequest = InitialRequest::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'description' => $validated['description'],
            'initial_file_path' => $initialFilePath,
            'data' => $validated['data'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'درخواست شما با موفقیت ثبت شد و در انتظار بررسی است.');
    }
}
