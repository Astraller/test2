<?php

namespace App\System;

use App\System\Exceptions\HttpException;
use App\System\Exceptions\NotFoundException;

/**
 * Class Router
 * @package App\System
 */
class Router
{
    /**
     *
     */
    public const RESPONSE_FORMAT_JSON = 'json';
    /**
     *
     */
    public const RESPONSE_FORMAT_RAW = 'raw';

    /**
     * @var array
     */
    private $availableFormats = [
        'applications/json' => self::RESPONSE_FORMAT_JSON
    ];
    /**
     * @var array
     */
    private $contentTypes = [
        self::RESPONSE_FORMAT_JSON => 'applications/json',
        self::RESPONSE_FORMAT_RAW => 'text/html'
    ];
    /**
     * @var array
     */
    private $successResponseCodes = [
        'get' => 200,
        'post' => 201,
        'put' => 200,
        'delete' => 200
    ];

    /**
     * @var
     */
    private $controllerName;
    /**
     * @var
     */
    private $actionName;
    /**
     * @var string
     */
    private $requestMethod;
    /**
     * @var
     */
    private $requestParams;
    /**
     * @var string
     */
    private $responseFormat;
    /**
     * @var BaseController
     */
    private $controller;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $this->responseFormat = $this->defineResponseFormat();
        $this->defineRoute();
    }

    /**
     *
     */
    public function dispatch(): void
    {
        $controllerClass = $this->defineController();
        $actionName = $this->defineAction();
        try {
            if (is_callable([$controllerClass, $actionName])) {
                $this->controller = new $controllerClass();
                $result = call_user_func_array([$this->controller, $actionName], $this->requestParams);
            } else {
                throw new NotFoundException('Path not found');
            }
        } catch (HttpException $httpException) {
            $this->httpErrorResponse($httpException);
        } catch (\Exception $exception) {
            $this->generalErrorResponse($exception);
        }
        $this->setResponse($result);
    }

    /**
     * @param      $response
     * @param bool $isError
     */
    private function setResponse($response, $isError = false)
    {
        $this->setHeaders($isError);
        echo $this->constructResponse($response);
    }

    /**
     *
     */
    private function defineRoute()
    {
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        if (count($uri) > 1) {
            $this->controllerName = strtolower(array_shift($uri));
            $this->actionName = strtolower(array_shift($uri));
        } elseif (count($uri) > 0) {
            $this->controllerName = 'index';
            $this->actionName = strtolower(array_shift($uri));
        } else {
            $this->controllerName = 'index';
            $this->actionName = 'index';
        }
        $this->requestParams = $uri;
    }

    /**
     * @return string
     */
    private function defineController()
    {
        return '\\App\\Controller\\' . ucfirst($this->controllerName) . 'Controller';
    }

    /**
     * @return string
     */
    private function defineAction()
    {
        return $this->requestMethod . ucfirst($this->actionName);
    }

    /**
     * @param array|string $result
     * @return string
     */
    private function constructResponse($result): string
    {
        if ('JSON' === $this->responseFormat) {
            return \GuzzleHttp\json_encode($result);
        } else {
            return $result;
        }
    }

    /**
     * @return string
     */
    private function defineResponseFormat(): string
    {
        $accept = $_SERVER['HTTP_ACCEPT'];
        if (in_array($accept, $this->availableFormats)) {
            return $this->availableFormats[$accept];
        } else {
            return self::RESPONSE_FORMAT_RAW;
        }
    }

    /**
     * @param $errorCode
     */
    private function setHeaders($errorCode)
    {
        header('Content-type: ' . $this->contentTypes[$this->responseFormat]);
        if (!$errorCode) {
            http_response_code($this->successResponseCodes[$this->requestMethod]);
        } else {
            http_response_code($errorCode);
        }
    }

    /**
     * @param HttpException $httpException
     */
    private function httpErrorResponse(HttpException $httpException)
    {
        $this->generalErrorResponse($httpException);
    }

    /**
     * @param \Exception $exception
     */
    private function generalErrorResponse(\Exception $exception)
    {
        $error = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];
        $this->setResponse($error, $exception->getCode());
    }
}