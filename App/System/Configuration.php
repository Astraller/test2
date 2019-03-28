<?php

namespace App\System;

use App\App;

/**
 * Class Configuration
 * @package App\System
 * @property string[] $db
 * @property string[] $security
 * @property string[] $auth
 */
class Configuration
{
    /**
     * @var mixed
     */
    private $_data;

    /**
     * Configuration constructor.
     */
    public function __construct()
    {
        $this->_data = require(App::inst()->basePath . 'config.php');
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_data[$name];
    }
}