<?php

namespace ViKon\DbConfig;

use Carbon\Carbon;
use ViKon\DbConfig\Model\Config;

/**
 * Class DbConfig
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\DbConfig
 */
class DbConfig
{
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
        $config = $this->getConfig($key);
        if ($config === null) {
            return $default;
        }

        return $config->value;
    }

    /**
     * Set config value by key
     *
     * @param string $key   config key
     * @param mixed  $value config value
     *
     * @throws \ViKon\DbConfig\DbConfigException
     */
    public function set($key, $value)
    {
        $config = $this->getConfig($key, true);

        // If no user then modified_by is not modified !
        if (\Auth::check()) {
            $config->modified_by = \Auth::user()->id;
        }
        $config->modified_at = new Carbon();
        $config->value       = $value;
        $config->save();
    }

    /**
     * Get config database values by group name
     *
     * @param string $group group name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConfigByGroup($group)
    {
        return Config::where('group', $group)->all();
    }

    /**
     * Get config model by key
     *
     * @param string $key    config key
     * @param bool   $create create config instance if not exists
     *
     * @return null|\ViKon\DbConfig\Model\Config
     */
    private function getConfig($key, $create = false)
    {
        list($group, $key) = $this->splitKey($key);
        $config = Config::where('group', $group)->where('key', $key)->first();

        if ($config === null && $create) {
            $config        = new Config();
            $config->key   = $key;
            $config->group = $group;
        }

        return $config;
    }

    /**
     * @param string $key
     *
     * @return string[]
     */
    private function splitKey($key)
    {
        if (strpos($key, '::') !== false) {
            list($group, $key) = explode('::', $key, 2);

            return [$group, $key];
        }

        return [null, $key];
    }
}