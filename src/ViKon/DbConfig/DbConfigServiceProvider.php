<?php namespace ViKon\DbConfig;

use Illuminate\Support\ServiceProvider;

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
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['config.db'];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('config.db', 'ViKon\DbConfig\DbConfig');
    }
}
