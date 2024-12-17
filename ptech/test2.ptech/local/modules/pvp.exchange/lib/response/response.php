<?php

namespace PVP\Exchange\Response;

use PVP\Exchange\ErrorManager;
use PVP\Exchange\Response\Format\FormatterInterface;

class Response
{
    const STATUS_SUCCESS = 200;
    const STATUS_QUEUED = 202;
    const STATUS_FORBIDDEN = 403;
    const STATUS_ERROR = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_INTERNAL_ERROR = 500;

    protected static $instance;
    protected $statusCode = self::STATUS_SUCCESS;
    protected $responseData = [];

    protected FormatterInterface $formatter;

    protected function __construct() {}
    protected function __wakeup() {}
    protected function __clone() {}

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setResponseData($responseData) {
        $this->responseData[] = $responseData;

        return $this;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
		http_response_code($statusCode);

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponseData()
    {
        $errorManager = ErrorManager::getInstance();

        if ($errorManager->hasErrors()) {
            $this->responseData = ['ERRORS' => $errorManager->getErrors(), 'RESULT' => $this->responseData];
        }

        return $this->responseData;
    }

    public function getFormattedData(\PVP\Exchange\Response\Format\FormatterInterface $formatter)
    {
        return $formatter->format($this);
    }
}