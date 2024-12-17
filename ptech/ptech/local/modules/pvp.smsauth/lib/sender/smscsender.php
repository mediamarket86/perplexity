<?php

namespace PVP\SmsAuth\Sender;

use Bitrix\Main\Loader;

class SmscSender implements SenderInterface
{
    public function send($phone, $message): bool
    {
        if (! Loader::IncludeModule("smsc.sms")) {
            throw new \Exception('Модуль не установлен:' . 'smsc.sms');
        }

        $result = (new \SMSC_Send())->Send_SMS($phone, $message);

        if (is_array($result)) {
            return ($result[1] > 0) ? true : false;
        }

        return $result;

    }
}