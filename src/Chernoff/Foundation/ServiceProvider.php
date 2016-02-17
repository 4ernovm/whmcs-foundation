<?php

namespace Chernoff\Foundation;

use Chernoff\Container\ServiceProvider as BaseProvider;

/**
 * Class ServiceProvider
 * @package Chernoff\Events
 */
class ServiceProvider extends BaseProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("kernel", function() { return new Kernel($this->app); });
        $this->app->alias("kernel", 'Chernoff\Foundation\Kernel');

        $this->app->singleton("exception_handler", function() { return new ExceptionHandler; });
        $this->app->alias("exception_handler", 'Chernoff\Foundation\ExceptionHandler');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return array("kernel", "exception_handler");
    }
}
