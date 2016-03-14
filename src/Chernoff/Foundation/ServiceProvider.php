<?php

namespace Chernoff\Foundation;

use Chernoff\Container\ServiceProvider as BaseProvider;
use Symfony\Component\Templating\Loader\ChainLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

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
        require_once "helper_functions.php";

        $this->app->singleton("kernel", function() { return new Kernel($this->app); });
        $this->app->alias("kernel", 'Chernoff\Foundation\Kernel');

        $this->app->singleton("exception_handler", function() { return new ExceptionHandler; });
        $this->app->alias("exception_handler", 'Chernoff\Foundation\ExceptionHandler');

        $this->app->bindShared("templating", function() {
            return new PhpEngine(new TemplateNameParser, new ChainLoader);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return array("kernel", "exception_handler", "templating");
    }
}
