<?php

namespace App\System;

use App\App;
use PDO;

/**
 * Class ActiveQuery
 * @package App\System
 */
class ActiveQuery
{
    /**
     * @var BaseModel
     */
    private $_repository;
    /**
     * @var array
     */
    private $_conditions = [];
    /**
     * @var array
     */
    private $_select = [];
    /**
     * @var  \PDOStatement
     */
    private $_query;
    /**
     * @var array
     */
    private $_params;
    /**
     * @var DataSource
     */
    private $_connection;
    /**
     * @var string
     */
    private $_prefix;

    /**
     * ActiveQuery constructor.
     * @param BaseModel $repository
     */
    public function __construct(BaseModel $repository)
    {
        $this->_repository = $repository;
        $this->_prefix = App::inst()->Configuration->db['prefix'];
        $this->_connection = App::inst()->DataSource;
    }

    public function directExecute(string $query): \PDOStatement
    {
        $statement = $this->_connection->prepare($query);
        $this->_query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->_repository);
        return $statement;
    }

    public function getTableName(): string
    {
        return $this->constructTableName();
    }

    /**
     * @param array $conditions
     * @return ActiveQuery
     */
    public function where(array $conditions): self
    {
        $this->_conditions = array_merge($this->_conditions, $conditions);
        return $this;
    }

    /**
     * @return BaseModel
     */
    public function one(): BaseModel
    {
        $this->constructSelectQuery('one');
        $this->_query->execute($this->_params);
        $this->_query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->_repository);

        return $this->_query->fetchObject();
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $this->constructSelectQuery('all');
        $this->_query->execute($this->_params);
        $this->_query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->_repository);

        return $this->_query->fetchAll();
    }

    /**
     * @param array $fields
     * @return bool
     */
    public function update(array $fields): bool
    {
        $this->constructUpdateQuery($fields);
        return $this->_query->execute($this->_params);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $this->constructInsertQuery($data);
        return $this->_query->execute($this->_params);
    }

    /**
     * @param string $type
     */
    private function constructSelectQuery(string $type): void
    {
        $query[] = 'SELECT';
        $query[] = $this->constructFields();
        $query[] = $this->constructFrom();
        $query[] = $this->constructConditions();
        $query[] = $this->constructGroupBy();
        $query[] = $this->constructOrder();
        $query[] = $this->constructLimit($type);

        $completeQuery = implode(' ', $query);

        $this->_query = App::inst()->DataSource->prepare($completeQuery);
    }

    /**
     * @param array $params
     */
    private function constructUpdateQuery(array $params)
    {
        $query[] = 'UPDATE';
        $query[] = $this->constructTableName();
        $query[] = 'SET';
        $query[] = $this->constructUpdate($params);
        $query[] = $this->constructConditions();

        $completeQuery = implode(' ', $query);

        $this->_query = App::inst()->DataSource->prepare($completeQuery);
    }

    /**
     * @param array $params
     */
    private function constructInsertQuery(array $params)
    {
        $query[] = 'INSERT INTO';
        $query[] = $this->constructTableName();
        $query[] = 'SET';
        $query[] = $this->constructUpdate($params);
        $query[] = $this->constructConditions();

        $completeQuery = implode(' ', $query);

        $this->_query = App::inst()->DataSource->prepare($completeQuery);
    }

    /**
     * @return string
     */
    private function constructFields(): string
    {
        if (!count($this->_select)) {
            return "*";
        } else {
            return '`' . implode('`, `', $this->_select) . '`';
        }
    }

    /**
     * @return string
     */
    private function constructFrom(): string
    {
        return 'FROM ' . $this->constructTableName();
    }

    /**
     * @return string
     */
    private function constructTableName(): string
    {
        return $this->_prefix . $this->_repository->getTableName();
    }

    /**
     * @return string
     */
    private function constructConditions(): string
    {
        if (0 === count($this->_conditions)) {
            return '';
        }
        return 'WHERE ' . $this->constructParametrizedStatement($this->_conditions, ' AND ');
    }

    /**
     * @param array $paramsToUpdate
     * @return string
     */
    private function constructUpdate(array $paramsToUpdate): string
    {
        if (0 === count($this->_conditions)) {
            return '';
        }
        return 'SET ' . $this->constructParametrizedStatement($paramsToUpdate, ', ');
    }

    /**
     * @param array  $params
     * @param string $glue
     * @return string
     */
    private function constructParametrizedStatement(array $params, string $glue)
    {
        $statement = [];
        foreach ($params as $param => $value) {
            $statement[] = '`' . $param . '` = ?';
            $this->_params = $value;
        }
        return implode($glue, $statement);
    }

    /**
     * @return string
     */
    private function constructGroupBy()
    {
        return '';
    }

    /**
     * @return string
     */
    private function constructOrder()
    {
        return '';
    }

    /**
     * @param string $type
     * @return string
     */
    private function constructLimit($type = 'all')
    {
        if ('one' === $type) {
            return 'LIMIT 1';
        } else {
            return '';
        }
    }

}