<?php

namespace App\System;

/**
 * Class BaseController
 * @package App\System
 */
abstract class BaseController
{
    /**
     *
     */
    public const REQUEST_DATA_TYPE_REGULAR = 'regular';
    /**
     *
     */
    public const REQUEST_DATA_TYPE_RAW = 'raw';

    /**
     * @var string
     */
    protected $requestDataType = self::REQUEST_DATA_TYPE_REGULAR;
    /**
     * @var array
     */
    protected $requestData;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->requestData = $this->prepareRequestData();
    }

    /**
     * @throws \InvalidArgumentException if the JSON cannot be decoded.
     */
    private function prepareRequestData(): array
    {
        if (self::REQUEST_DATA_TYPE_REGULAR === $this->requestDataType) {
            return $_REQUEST;
        } elseif (self::REQUEST_DATA_TYPE_RAW === $this->requestDataType) {
            return \GuzzleHttp\json_decode(file_get_contents("php://input"), true);
        } else {
            return [];
        }
    }
}