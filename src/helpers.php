<?php

if (!function_exists('db_config'))
{
    /**
     * Get config value by key
     *
     * @param string $key config key
     *
     * @return mixed
     * @throws \ViKon\DbConfig\DbConfigException
     */
    function db_config($key, $default = null)
    {
        return app('ViKon\DbConfig\DbConfig')->get($key, $default);
    }
}