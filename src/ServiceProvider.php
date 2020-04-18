<?php

namespace NotificationChannels\Phoney;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
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
                $pusherConfig = config('broadcasting.connections.pusher');

                return new Pusher(
                    $pusherConfig['key'],
                    $pusherConfig['secret'],
                    $pusherConfig['app_id']
                );
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
