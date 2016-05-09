<?php namespace ViKon\DbConfig;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use ViKon\DbConfig\Contract\Repository as RepositoryContract;

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
        $this->publishes([__DIR__ . '/../../config' => config_path('vi-kon/db-config.php')], 'config');
        $this->publishes([__DIR__ . '/../../database/migrations/' => base_path('database/migrations')], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'vi-kon.db-config');

        $this->registerRepository();
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

    /**
     * Register config repository
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton(RepositoryContract::class, function (Container $container) {
            $config = $container->make(ConfigRepository::class);
            $guard  = $container->make(Guard::class);
            $cache  = $container->make(CacheFactory::class)->store($config->get('vi-kon.db-config.cache'));

            return new Repository($guard, $cache);
        });

        $this->app->alias(RepositoryContract::class, 'config.db');
    }
}
