<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('sys-admin', function ($user) {
            return $user->role === 'sys-admin';
        });

        Gate::define('client-admin', function ($user) {
            return $user->role === 'client-admin';
        });

        Gate::define('client-user', function ($user) {
            return $user->role === 'client-user';
        });

        Gate::define('client-area', function ($user) {
        return in_array($user->role, [
            'client-admin',
            'client-user'
         ]);
        });
    }
}