<?php


namespace PVP\Exchange;


class ErrorManager
{
    protected static $instance;
    protected $errors = [];
    protected bool $debugMode = false;

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

    public function hasErrors(): bool
    {
        return (bool)count($this->errors);
    }

    public function addError($errors) {
        if (! is_array($errors)) {
            $errors = [$errors];
        }

        foreach ($errors as $error) {
            $errorInfo['ERROR'] = $error;

            if ($this->isDebugMode()) {
                $errorInfo['DEBUG_INFO'] = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 7);
            }

            $this->errors[] = $errorInfo;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->errors as $error) {
            $messages[] = $error['ERROR'];
        }

        return $messages;
    }

    public function clearErrors()
    {
        $this->errors = [];
    }

    public function setDebugMode(bool $debugMode)
    {
        $this->debugMode = $debugMode;
    }

    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }
}