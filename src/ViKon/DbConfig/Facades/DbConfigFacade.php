<?php

namespace ViKon\DbConfig\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DbConfigFacade
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\DbConfig\Facades
 */
class DbConfigFacade extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'config.db';
    }
}