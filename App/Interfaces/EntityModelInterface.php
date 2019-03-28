<?php

namespace App\Interfaces;

/**
 * Interface EntityModelInterface
 * @package App\Interfaces
 */
interface EntityModelInterface
{
    /**
     * @return array
     */
    public function getAllRecords(): array;

    /**
     * @param int $id
     * @return EntityModelInterface
     */
    public function getOneRecord(int $id): self;

    /**
     * @param array $data
     * @return bool
     */
    public function newRecord(array $data): bool;

    /**
     * @param array $data
     * @return bool
     */
    public function updateRecord(array $data): bool;
}