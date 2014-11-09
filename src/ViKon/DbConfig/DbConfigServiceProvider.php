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

    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ViKon\DbConfig\DbConfig', 'ViKon\DbConfig\DbConfig');

        include_once __DIR__ . DIRECTORY_SEPARATOR . 'helper.php';
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
