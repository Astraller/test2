<?php

namespace App\Model;

/**
 * Class Item
 * @package App\Model
 * @property            $name
 * @property            $price
 * @property Category[] $categories
 */
class Item extends EntityModel
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'items';
    }

    public function getCategories()
    {
        return ItemCategories::getCategoriesByItem($this->id);
    }
}