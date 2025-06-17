<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia; // برای استفاده از Inertia
use App\Models\Service; // برای استفاده از مدل Service

class PublicPagesController extends Controller
{
    /**
     * Display the home page.
     */
    public function home()
    {
        // فقط خدمات فعال را برای نمایش در صفحه اصلی دریافت کنید
        // و فقط 6 تای اول را برای نمونه نمایش دهید
        $services = Service::where('is_active', true)
            ->latest() // جدیدترین خدمات
            ->take(6) // فقط 6 مورد
            ->get(['id', 'name', 'slug', 'description']); // فقط فیلدهای مورد نیاز

        return Inertia::render('Home', [
            'services' => $services,
            'appName' => config('app.name'), // ارسال نام سایت به Vue.js
        ]);
    }

    /**
     * Display the services listing page.
     */
    public function servicesIndex()
    {
        $services = Service::where('is_active', true)
            ->orderBy('name') // مرتب سازی بر اساس نام
            ->get(['id', 'name', 'slug', 'description']);

        return Inertia::render('Services/Index', [ // این ویو را در مرحله بعدی میسازیم
            'services' => $services,
        ]);
    }

    /**
     * Display a specific service details page.
     */
    public function serviceShow(Service $service)
    {
        return Inertia::render('Services/Show', [ // این ویو را در مرحله بعدی میسازیم
            'service' => $service->only(['id', 'name', 'slug', 'description', 'base_price', 'price_calculation_params', 'initial_form_fields', 'requires_coordination']),
        ]);
    }

    /**
     * Display the about us page.
     */
    public function about()
    {
        return Inertia::render('About'); // این ویو را بعدا میسازیم
    }

    /**
     * Display the contact us page.
     */
    public function contact()
    {
        return Inertia::render('Contact'); // این ویو را بعدا میسازیم
    }

    /**
     * Display the privacy policy page.
     */
    public function privacyPolicy()
    {
        return Inertia::render('PrivacyPolicy'); // این ویو را بعدا میسازیم
    }

    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return Inertia::render('Terms'); // این ویو را بعدا میسازیم
    }
}
