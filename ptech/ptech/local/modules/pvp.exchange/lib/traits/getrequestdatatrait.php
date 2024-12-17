<?php

namespace PVP\Exchange\Traits;

use Bitrix\Main\Application;

trait GetRequestDataTrait
{
    public function getData(): array
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isPost() && $input = file_get_contents('php://input')) {
            return json_decode($input, true);
        }

        return [];
    }
}