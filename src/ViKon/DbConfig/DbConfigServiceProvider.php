<?php namespace ViKon\DbConfig;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use ViKon\DbConfig\Model\Config;

/**
 * Class DbConfigServiceProvider
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\DbConfig
 */
class DbConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                             __DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),
                         ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Repository::class, function (Container $container) {
            return new Repository($container, Config::all());
        });

        $this->app->alias(Repository::class, 'config.db');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Repository::class,
            'config.db',
        ];
    }
}
