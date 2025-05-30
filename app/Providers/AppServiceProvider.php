<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Users;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

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
        View::share('users', Users::all());

        Carbon::setLocale('id');
    }
}
