<?php

namespace App\Model;

use App\System\BaseModel;

/**
 * Class ItemCategories
 * @package App\Model
 * @property int $id_item
 * @property int $id_category
 */
class ItemCategories extends BaseModel
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'item_categories';
    }

    /**
     * @param int $itemId
     * @return Category[]
     */
    public static function getCategoriesByItem(int $itemId): array
    {
        $self = self::model();
        $query = Category::model();
        $statement = $query->directExecute('
            SELECT c.* 
            FROM ' . $query->getTableName() . ' c 
            LEFT JOIN ' . $self->getTableName() . ' ic ON ic.id_category = c.id
            WHERE ic.id_item = ?');
        $statement->execute([$itemId]);
        return $statement->fetchAll();
    }
}