<?php

namespace ViKon\DbConfig;

use Carbon\Carbon;
use Illuminate\Contracts\Container\Container;
use ViKon\DbConfig\Model\Config;

/**
 * Class Repository
 *
 * @package ViKon\DbConfig
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 */
class Repository implements \ArrayAccess
{
    /** @type \Illuminate\Contracts\Container\Container */
    protected $container;

    /** @type \ViKon\DbConfig\Model\Config[][] */
    protected $models = [];

    /**
     * Repository constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @param \ViKon\DbConfig\Model\Config[]            $models
     */
    public function __construct(Container $container, array $models)
    {
        $this->container = $container;

        // Sort models by namespace and keys
        foreach ($models as $model) {
            if (!array_key_exists($model->namespace, $this->models)) {
                $this->models[$model->namespace] = [];
            }

            $this->models[$model->namespace][$model->key] = $model;
        }
    }

    /**
     * Check if config value exists or not
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->getModel($key) !== null;
    }

    /**
     * Get config value by key
     *
     * @param string $key     config key
     * @param mixed  $default default value if config key not found in database
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->getModel($key)->value;
        }

        return $default;
    }

    /**
     * Set config value by key
     *
     * @param string $key   config key
     * @param mixed  $value config value
     */
    public function set($key, $value)
    {
        $guard = $this->container->make('auth')->guard();

        $config = $this->getModel($key, true);

        // If no user then modified_by is not modified !
        if ($guard->check()) {
            $config->modified_by_user_id = $guard->id();
        }
        $config->modified_at = new Carbon();
        $config->value       = $value;
        $config->save();
    }

    /**
     * Get config model by key
     *
     * Note: This method only create config, but not save it!
     *
     * @param string $key    config key
     * @param bool   $create create config instance if not exists
     *
     * @return \ViKon\DbConfig\Model\Config|null return NULL if model is not found and create parameter is set to FALSE
     */
    private function getModel($key, $create = false)
    {
        list($namespace, $key) = $this->splitNamespaceAndKey($key);

        if (array_key_exists($namespace, $this->models) && array_key_exists($key, $this->models[$namespace])) {
            return $this->models[$namespace][$key];
        }

        $model = Config::query()
                       ->where(Config::FIELD_NAMESPACE, $namespace)
                       ->where(Config::FIELD_KEY, $key)
                       ->first();

        if ($model === null && $create) {
            $model            = new Config();
            $model->namespace = $namespace;
            $model->key       = $key;
        }

        if (!array_key_exists($namespace, $this->models)) {
            $this->models[$namespace] = [];
        }

        $this->models[$namespace][$key] = $model;

        return $model;
    }

    /**
     * @param string $key
     *
     * @return string[]
     */
    private function splitNamespaceAndKey($key)
    {
        if (strpos($key, '::') !== false) {
            list($namespace, $key) = explode('::', $key, 2);

            return [$namespace, $key];
        }

        return [null, $key];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->has($offset)) {
            $this->set($offset, null);
        }
    }
}