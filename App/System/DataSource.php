<?php

namespace App\System;

use App\App;

/**
 * Class DataSource
 * @package App\System
 */
class DataSource
{
    /**
     * @var \PDO
     */
    private $_connection;

    /**
     * DataSource constructor.
     */
    public function __construct()
    {
        $this->_connection = new \PDO(
            App::inst()->Configuration->db['connection'],
            App::inst()->Configuration->db['username'],
            App::inst()->Configuration->db['password']
        );
    }

    /**
     * @param       $statement
     * @param array $driverOptions
     * @return \PDOStatement
     */
    public function prepare($statement, array $driverOptions = []): \PDOStatement
    {
        return $this->_connection->prepare($statement, $driverOptions);
    }
}