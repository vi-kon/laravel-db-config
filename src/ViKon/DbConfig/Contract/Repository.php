<?php

namespace ViKon\DbConfig\Contract;

/**
 * Interface Repository
 *
 * @package ViKon\DbConfig\Contract
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
interface Repository
{
    /**
     * Create new config entry
     *
     * @param string $key
     * @param string $type
     * @param mixed  $value
     *
     * @return $this
     */
    public function create($key, $type, $value = null);

    /**
     * Check if config value exists or not
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Get config value by key
     *
     * @param string $key     config key
     * @param mixed  $default default value if config key not found in database
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set config value by key
     *
     * @param string $key   config key
     * @param mixed  $value config value
     *
     * @return $this
     */
    public function set($key, $value);
}