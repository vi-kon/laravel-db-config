<?php


namespace ViKon\DbConfig;

use ViKon\DbConfig\models\Config;

class DbConfig
{
    /**
     * Get config value by key
     *
     * @param string $key config key
     *
     * @return mixed
     * @throws \ViKon\DbConfig\DbConfigException
     */
    public function get($key)
    {
        $config = $this->getConfig($key);
        if ($config === null)
        {
            throw new DbConfigException('Db config with ' . $key . ' not found');
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
        $config        = $this->getConfig($key);
        $config->value = $value;
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
     * @param string $key config key
     *
     * @throws \ViKon\DbConfig\DbConfigException
     * @return \ViKon\DbConfig\models\Config|null
     */
    private function getConfig($key)
    {
        if (strpos($key, '::') !== false)
        {
            list($group, $key) = explode('::', $key, 2);

            $config = Config::where('group', $group)->where('key', $key)->first();
        } else
        {
            $config = Config::where('key', $key)->first();
        }

        if ($config === null)
        {
            throw new DbConfigException('Db config with ' . $key . ' not found');
        }

        return $config;
    }
}