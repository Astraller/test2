<?php

namespace App\Controller;

use App\Model\Category;

/**
 * Class CategoriesController
 * @package App\Controller
 */
class CategoriesController extends EntityController
{
    /**
     * @var string
     */
    protected $model = Category::class;
}