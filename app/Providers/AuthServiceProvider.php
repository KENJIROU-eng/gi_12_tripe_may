<?php

namespace App\Providers;

<<<<<<< HEAD
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
=======
use App\Models\Group;
use App\Policies\GroupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Group::class => GroupPolicy::class,
    ];

>>>>>>> e1bd31e8d67f147ae0d82c4e8a11a61e20952725
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        Gate::define('admin', function($user) {
            return $user->role_id === User::ADMIN_ROLE_ID;
        });
=======
        //
>>>>>>> e1bd31e8d67f147ae0d82c4e8a11a61e20952725
    }
}
