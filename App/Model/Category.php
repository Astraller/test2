<?php

namespace App\Model;

/**
 * Class Category
 * @package App\Model
 * @property $name
 */
class Category extends EntityModel
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'categories';
    }
}