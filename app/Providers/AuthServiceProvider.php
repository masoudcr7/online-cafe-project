<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // اینجاست که می توانید policy ها را رجیستر کنید یا Gate ها را تعریف کنید.
        // $this->registerPolicies();

        // Gate::define('view-admin-panel', function ($adminUser) {
        //     return $adminUser->role === 'admin';
        // });
    }
}
