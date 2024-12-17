<?php


namespace PVP\ExchangeV1;


abstract class Method implements MethodInterface
{
    protected $result = [];
    protected $resultCode = 200;
    protected $param;
    protected $errorManager;

    /**
     * @deprecated
     */
    protected $errors;


    public function __construct($param)
    {
        $this->param = $param;
        $this->errorManager = ErrorManager::getInstance();
    }

    public function getResult(): array
    {
        if (self::DEBUG) {
            AddMessage2Log(var_export($this->result, true));
        }

        if ($this->errorManager->hasErrors()) {
            $this->result['ERRORS'] = $this->errorManager->getErrors();
        }

        return $this->result;
    }

    public function getResultCode(): int
    {
        if ($this->errorManager->hasErrors()) {
            $this->resultCode = 400;
        }

        return $this->resultCode;
    }

    protected function addError($errors) {
        $errorManager = ErrorManager::getInstance();
        $errorManager->addError($errors);
    }

    public function hasError() {
        return $this->errorManager->hasErrors();
    }
}