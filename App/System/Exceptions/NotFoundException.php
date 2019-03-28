<?php

namespace App\System\Exceptions;


/**
 * Class NotFoundException
 * @package App\System\Exceptions
 */
class NotFoundException extends HttpException
{
    /**
     * @var int
     */
    protected $code = 404;
    /**
     * @var string
     */
    protected $message = 'Not found';
}