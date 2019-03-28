<?php

namespace App\Controller;

use App\Model\Item;

/**
 * Class ItemsController
 * @package App\Controller
 */
class ItemsController extends EntityController
{
    /**
     * @var string
     */
    protected $model = Item::class;
}