<?php namespace ViKon\DbConfig;

use Illuminate\Support\ServiceProvider;

class DbConfigServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ViKon\DbConfig\DbConfig', 'ViKon\DbConfig\DbConfig');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ViKon\DbConfig\DbConfig'];
    }
}
