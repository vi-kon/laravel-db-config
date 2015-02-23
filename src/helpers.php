<?php

if (!function_exists('config_db')) {
    /**
     * Get config value by key
     *
     * @param string $key config key
     *
     * @return mixed
     * @throws \ViKon\DbConfig\DbConfigException
     */
    function config_db($key, $default = null) {
        return app('config.db')->get($key, $default);
    }
}