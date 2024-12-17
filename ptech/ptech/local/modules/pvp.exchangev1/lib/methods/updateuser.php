<?php


namespace PVP\ExchangeV1\Methods;


use Bitrix\Rest\RestException;

class UpdateUser extends \PVP\ExchangeV1\ImportMethod
{
    public function execute()
    {
        if ($this->error) {
            return false;
        }

        if (! $this->data || empty($this->data[\PVP\Import\User::LOGIN_FIELD])) {
            $this->resultCode = 400;
            $this->result = ['ERROR' => 'LOGIN not found'];
            return false;
        }

        $import = new \PVP\Import\User($this->data);

        if ($import->import()) {
            $this->result = ['SUCCESS' => 'OK'];
        } else {
            $this->resultCode = 400;
            $this->result = ['ERROR' => $import->getErrors()];
        }
    }
}