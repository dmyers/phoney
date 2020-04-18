<?php

namespace NotificationChannels\Phoney;

use Illuminate\Support\ServiceProvider;

class PhoneyServiceProvider extends ServiceProvider
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
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
