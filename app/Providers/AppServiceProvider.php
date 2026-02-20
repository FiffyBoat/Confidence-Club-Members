<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') && Str::startsWith((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Gate::define('manage-users', fn ($user) => $user->is_active && $user->role === 'admin');
        Gate::define('manage-revenue', fn ($user) => $user->is_active && in_array($user->role, ['admin', 'treasurer'], true));
        Gate::define('view-reports', fn ($user) => $user->is_active && in_array($user->role, ['admin', 'treasurer'], true));
    }

}
