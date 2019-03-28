<?php

namespace App\System;

/**
 * Class BaseModel
 * @package App\System
 * @property int $id
 */
abstract class BaseModel
{
    /**
     * @var array
     */
    protected $_attributes = [];

    /**
     * @return string
     */
    abstract public function getTableName(): string;

    /**
     * @return ActiveQuery
     */
    public static function model(): ActiveQuery
    {
        return new ActiveQuery(new static());
    }

    /**
     * @param $identifier
     * @return BaseModel
     */
    public static function findOne($identifier): self
    {
        return self::model()->where(['id' => $identifier])->one();
    }

    /**
     * @param $data
     * @return bool
     */
    public function update($data): bool
    {
        return self::model()->where(['id' => $this->id])->update($data);
    }

    /**
     * @param $data
     * @return bool
     */
    public function create($data): bool
    {
        return self::model()->where(['id' => $this->id])->create($data);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $relationName = 'get' . ucfirst($name);
        if (is_callable([$relationName, $this])) {
            return $this->$relationName();
        }
        return $this->_attributes[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

}