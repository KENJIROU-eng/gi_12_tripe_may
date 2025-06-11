<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{

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
    // public function boot(): void
    // {

    //     Broadcast::routes([
    //     'middleware' => ['web', 'auth'], // ← 'auth:web' と同じ意味
    // ]);

    public function boot(): void
    {
        Broadcast::routes([
            'middleware' => ['web', 'auth'],//auth:webと同じ
        ]);


        require base_path('routes/channels.php');
    }
}

