<?php

namespace PVP\SmsAuth;

use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\UserTable;

/**
 * 2023-02-15 В оригинальном классе UserTable недоступно поле PASSWORD_EXPIRED, исправляем.
 */
class UserExtendedTable extends UserTable
{
    public static function getMap()
    {
        $result = parent::getMap();
        $result[] = (new BooleanField('PASSWORD_EXPIRED'))
            ->configureValues('N','Y');

        return $result;
    }
}