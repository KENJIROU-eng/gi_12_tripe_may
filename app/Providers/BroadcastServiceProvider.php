<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
<<<<<<< HEAD
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

        Broadcast::routes([
        'middleware' => ['web', 'auth'], // ← 'auth:web' と同じ意味
    ]);
=======
    public function boot(): void
    {
        Broadcast::routes([
            'middleware' => ['web', 'auth'],//auth:webと同じ
        ]);
>>>>>>> e1bd31e8d67f147ae0d82c4e8a11a61e20952725

        require base_path('routes/channels.php');
    }
}
<<<<<<< HEAD
=======

>>>>>>> e1bd31e8d67f147ae0d82c4e8a11a61e20952725
