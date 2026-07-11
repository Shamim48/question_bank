<?php

namespace App\Providers;

use App\Contracts\SmsGatewayInterface;
use App\Services\Sms\LogSmsGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsGatewayInterface::class, function () {
            return match (config('services.sms.driver')) {
                default => new LogSmsGateway(),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
