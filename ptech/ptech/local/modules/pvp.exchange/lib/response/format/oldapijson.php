<?php

namespace PVP\Exchange\Response\Format;

use PVP\Exchange\ErrorManager;

class OldApiJson implements FormatterInterface
{
    public function format(\PVP\Exchange\Response\Response $response)
    {
        if (is_array($response->getResponseData()) && 1 == count($response->getResponseData())) {
            return json_encode($response->getResponseData()[0]);
        }

        ErrorManager::getInstance()->addError(__CLASS__ . ' $resopnseData содержит неподходяжие данные');

        return json_encode(ErrorManager::getInstance()->getErrors());
    }
}