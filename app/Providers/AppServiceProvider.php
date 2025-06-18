<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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
    // ✅ Force HTTPS in production
    if (env('APP_ENV') !== 'local') {
        \Illuminate\Support\Facades\URL::forceScheme('http');
    }

    // ✅ Use Bootstrap 5 for pagination
    \Illuminate\Pagination\Paginator::useBootstrapFive();

    // ✅ Share unread count + admin notifications with all views
    \Illuminate\Support\Facades\View::composer('*', function ($view) {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();

            // 🔐 Force logout if inactive
            if ($user->status === 'inactive') {
                \Illuminate\Support\Facades\Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                redirect()->route('login')->withErrors([
                    'email' => 'Your account is inactive. Please contact support to reactivate.',
                ])->send();
                exit;
            }

            // Message Unread Count
            $unreadCount = \App\Models\Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();
            $view->with('unreadCount', $unreadCount);

            // Admin Notifications
            if ($user->role === 'admin') {
                $adminNotifications = $user->unreadNotifications()->take(5)->get();
                $view->with('adminNotifications', $adminNotifications);
            }
        }
    });
}
}
