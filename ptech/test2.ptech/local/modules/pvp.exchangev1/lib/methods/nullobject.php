<?php


namespace PVP\ExchangeV1\Methods;


class NullObject extends \PVP\ExchangeV1\Method
{


    public function getResult(): array
    {
        return ['ERROR' => \Bitrix\Rest\RestException::ERROR_METHOD_NOT_FOUND];
    }

    public function getResultCode(): int
    {
        return 404;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}