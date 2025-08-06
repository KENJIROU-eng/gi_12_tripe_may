<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Encryption\Encrypter;


class EncrypterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('encrypter', function ($app) {
            $config = $app['config']['app'];
            $key = $config['key'];

            if (str_starts_with($key, 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter(
                $key,
                $config['cipher']
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
