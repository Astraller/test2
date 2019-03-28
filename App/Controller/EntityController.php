<?php

namespace App\Controller;

use App\Interfaces\EntityModelInterface;
use App\System\BaseController;

/**
 * Class EntityController
 * @package App\Controller
 */
abstract class EntityController extends BaseController
{
    /**
     * @var EntityModelInterface
     */
    protected $model;

    /**
     * @return array
     */
    public function getIndex()
    {
        return $this->model->getAllRecords();
    }

    /**
     * @param $id
     * @return EntityModelInterface
     */
    public function getView($id)
    {
        return $this->model->getOneRecord($id);
    }

    /**
     * @param mixed ...$params
     * @return array
     */
    public function putIndex(...$params)
    {
        $id = array_shift($params);
        return [
            'result' => $this->model->getOneRecord($id)->updateRecord($params)
        ];
    }

    /**
     * @param mixed ...$params
     * @return array
     */
    public function postIndex(...$params)
    {
        return [
            'result' => $this->model->newRecord($params)
        ];
    }

}