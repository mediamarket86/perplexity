<?php


namespace PVP\ExchangeV1;


class RestMethodLocalExecutor
{
    public static function executeRestMethod(Method $method)
    {
        $method->execute();

        http_response_code($method->getResultCode());
        echo json_encode($method->getResult());

        die();
    }
}