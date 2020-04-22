<?php

namespace Dmyers\Phoney;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @see https://github.com/typpo/textbelt
 * @see https://github.com/brendanlim/sms-fu
 * @see https://github.com/preston/sms-easy
 * @see https://github.com/typpo/textbelt/blob/master/lib/carriers.js
 * @see https://github.com/typpo/textbelt/blob/master/lib/providers.js
 * @see https://github.com/mfitzp/List_of_SMS_gateways
 * @see https://github.com/laravel-notification-channels/twilio/tree/master/src
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
        /*
        $this->app->when(Channel::class)
            ->needs(Pusher::class)
            ->give(function () {
                return new Pusher;
            });
         */
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
            'phoney',
            Phoney::class,
        ];
    }
}
