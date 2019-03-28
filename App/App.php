<?php

namespace App;

use App\System\Authentication;
use App\System\Configuration;
use App\System\DataSource;
use App\System\Router;

/**
 * Class App
 * @package App
 * @property Router         $Router
 * @property Configuration  $Configuration
 * @property DataSource     $DataSource
 * @property Authentication $Authentication
 */
class App
{
    /**
     * @var
     */
    private static $_instance;
    /**
     * @var bool|string
     */
    public $basePath;
    /**
     * @var bool|string
     */
    public $appPath;
    /**
     * @var array
     */
    private $_components = [];

    /**
     * App constructor.
     */
    private function __construct()
    {
        $this->basePath = realpath(__DIR__ . '../');
        $this->appPath = realpath(__DIR__);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->_components[$name])) {
            $this->_components[$name] = new $name;
        }
        return $this->_components[$name];
    }

    /**
     * @return App
     */
    public static function inst()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}