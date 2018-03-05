<?php

namespace Brain\Providers;

use Illuminate\Support\ServiceProvider;
use Brain\Console\Commands\BrainSubscriberCommand;

class BrainServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->commands('brain.brain-subscriber');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }

    /**
     * Register the Artisan command.
     */
    protected function registerCommand()
    {
        $this->app->singleton('brain.brain-subscriber', function () {
            return new BrainSubscriberCommand();
        });
    }
}