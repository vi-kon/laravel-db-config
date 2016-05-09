<?php

namespace ViKon\DbConfig;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Collection;
use ViKon\DbConfig\Contract\Repository as DbConfigRepository;
use ViKon\DbConfig\Model\Config;

/**
 * Class Repository
 *
 * @package ViKon\DbConfig
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 */
class Repository implements \ArrayAccess, DbConfigRepository
{
    /** @type \Illuminate\Contracts\Auth\Guard */
    protected $guard;

    /** @type \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @type \Illuminate\Database\Eloquent\Collection|\ViKon\DbConfig\Model\Config[] */
    protected $models;

    /**
     * Repository constructor.
     *
     * @param \Illuminate\Contracts\Auth\Guard                                        $guard
     * @param \Illuminate\Contracts\Cache\Repository                                  $cache
     * @param \Illuminate\Database\Eloquent\Collection|\ViKon\DbConfig\Model\Config[] $models
     */
    public function __construct(Guard $guard, CacheRepository $cache, Collection $models = null)
    {
        $this->guard  = $guard;
        $this->cache  = $cache;
        $this->models = new Collection();

        if ($models !== null) {
            // Sort models by namespace and keys
            foreach ($models as $model) {
                $key                = $model->namespace . '::' . $model->key;
                $this->models[$key] = $model;

                // Update cache entries
                $this->cache->forever($key, $model->value);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function create($key, $type, $value = null)
    {
        list($namespace, $key) = $this->splitNamespaceAndKey($key);

        $config            = new Config();
        $config->namespace = $namespace;
        $config->key       = $key;
        $config->type      = $type;
        $config->value     = $value;
        $config->save();

        $this->cache->forever($namespace . '::' . $key, $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return $this->cache->has($key) || $this->getModel($key) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        if ($this->has($key)) {
            return $this->getModel($key)->value;
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $config = $this->getModel($key);

        // If no user then modified_by is not modified !
        if ($this->guard->check()) {
            $config->modified_by_user_id = $this->guard->id();
        }
        $config->modified_at = new Carbon();
        $config->value       = $value;
        $config->save();

        // Update cache
        $this->cache->forever($key, $config->value);

        return $this;
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

    /**
     * Get config model by key
     *
     * @param string $key config key
     *
     * @return \ViKon\DbConfig\Model\Config|null return NULL if model was not found in database
     */
    protected function getModel($key)
    {
        if (!array_key_exists($key, $this->models)) {
            list($namespace, $key) = $this->splitNamespaceAndKey($key);

            $this->models[$key] = Config::query()
                                        ->where(Config::FIELD_NAMESPACE, $namespace)
                                        ->where(Config::FIELD_KEY, $key)
                                        ->first();

            // Cache if data is found in database
            if ($this->models[$key] !== null) {
                $this->cache->forever($key, $this->models[$key]->value);
            }
        }

        return $this->models[$key];
    }

    /**
     * @param string $key
     *
     * @return string[]
     */
    protected function splitNamespaceAndKey($key)
    {
        if (strpos($key, '::') !== false) {
            list($namespace, $key) = explode('::', $key, 2);

            return [$namespace, $key];
        }

        return ['', $key];
    }
}