<?php

if (!function_exists('config_db')) {
    /**
     * Get config value by key
     *
     * @param string|null $key config key
     * @param mixed       $default
     *
     * @return mixed|\ViKon\DbConfig\DbConfig
     *
     * @throws \ViKon\DbConfig\DbConfigException
     */
    function config_db($key = null, $default = null) {
        if ($key === null) {
            return app('config.db');
        }

        return app('config.db')->get($key, $default);
    }
}