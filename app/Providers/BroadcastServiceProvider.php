<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
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
        // Web + 認証ユーザーに限定したルート設定
        Broadcast::routes([
            'middleware' => ['web', 'auth'], // 'auth:web' と同等
        ]);

        // チャンネル定義ファイルの読み込み
        require base_path('routes/channels.php');
    }
}
