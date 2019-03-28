<?php

namespace App\System\Exceptions;

/**
 * Class UnauthorizedHttpException
 * @package App\System\Exceptions
 */
class UnauthorizedHttpException extends HttpException
{
    /**
     * @var string
     */
    protected $code = '401';
    /**
     * @var string
     */
    protected $message = 'Do not authorized';
}