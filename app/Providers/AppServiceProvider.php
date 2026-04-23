<?php

namespace App\Providers;

use App\Models\Booking;
use App\Observers\BookingObserver;
use Illuminate\Support\ServiceProvider;

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
        // RQ07 + RQ08: Đăng ký Observer để tự động xử lý notification khi booking thay đổi
        Booking::observe(BookingObserver::class);

        // Cung cấp $baseLayout động cho tất cả các views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $baseLayout = 'layouts.admin';
            if (auth()->check() && auth()->user()->role?->slug === 'staff') {
                $baseLayout = 'staff.layout';
            }
            $view->with('baseLayout', $baseLayout);
        });
    }
}
