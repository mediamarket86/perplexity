<?php


namespace PVP\ExchangeV1;


class ErrorManager
{
    protected static $instance;
    protected $errors = [];

    protected function __construct()
    {

    }

    protected function __wakeup()
    {
    }

    protected function __clone()
    {

    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function hasErrors(): bool
    {
        return (bool)count($this->errors);
    }

    public function addError($errors) {
        if (! is_array($errors)) {
            $errors = [$errors];
        }

        foreach ($errors as $error) {
            $this->errors[] = $error;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}