<?php

namespace App\System\Exceptions;

/**
 * Class ForbiddenHttpException
 * @package App\System\Exceptions
 */
class ForbiddenHttpException extends HttpException
{
    /**
     * @var string
     */
    protected $code = '403';
    /**
     * @var string
     */
    protected $message = 'You do not have rights to access that location';
}