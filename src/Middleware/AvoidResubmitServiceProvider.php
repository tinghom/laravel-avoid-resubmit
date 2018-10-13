<?php

namespace Tinghom\Middleware;

use Illuminate\Support\ServiceProvider;
use Tinghom\Middleware\AvoidResubmit;

class AvoidResubmitServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('web', AvoidResubmit::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AvoidResubmit::class, function ($app) {
            return new AvoidResubmit();
        });
    }
}
