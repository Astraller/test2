<?php

namespace App\Model;

use App\Interfaces\EntityModelInterface;
use App\System\BaseModel;

/**
 * Class EntityModel
 * @package App\Model
 */
abstract class EntityModel extends BaseModel implements EntityModelInterface
{
    /**
     * @return array
     */
    public function getAllRecords(): array
    {
        return self::model()->all();
    }

    /**
     * @param int $id
     * @return Category|EntityModelInterface|BaseModel
     */
    public function getOneRecord(int $id): EntityModelInterface
    {
        return self::findOne($id);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function newRecord(array $data): bool
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateRecord(array $data): bool
    {
        return $this->update($data);
    }
}