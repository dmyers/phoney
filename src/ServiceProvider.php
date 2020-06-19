<?php

namespace Dmyers\Phoney;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @see https://en.wikipedia.org/wiki/SMS_gateway
 * @see https://github.com/mfitzp/List_of_SMS_gateways
 * @see https://github.com/brendanlim/sms-fu
 * @see https://github.com/preston/sms-easy
 * @see https://github.com/typpo/textbelt
 * @see https://github.com/typpo/textbelt/blob/master/lib/carriers.js
 * @see https://github.com/typpo/textbelt/blob/master/lib/providers.js
 * @see https://github.com/laravel-notification-channels/twilio/tree/master/src
 * @see https://github.com/amnah/ksms
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Phoney::class, function () {
            return new Phoney;
        });

        $this->app->alias(Phoney::class, 'phoney');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Phoney::class,
            'phoney',
        ];
    }
}
