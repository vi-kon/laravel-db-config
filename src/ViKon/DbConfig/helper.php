<?php

if (!function_exists('dbconfig'))
{
    /**
     * Get config value by key
     *
     * @param string $key config key
     *
     * @return mixed
     * @throws \ViKon\DbConfig\DbConfigException
     */
    function dbconfig($key, $default = null)
    {
        return app('ViKon\DbConfig\DbConfig')->get($key, $default);
    }
}