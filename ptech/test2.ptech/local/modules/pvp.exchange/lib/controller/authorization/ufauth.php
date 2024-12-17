<?php

namespace PVP\Exchange\Controller\Authorization;


use PVP\Exchange\Controller\ControllerInterface;

/**
 * Класс заглушка для режима авторизации по ключу в параметре пользователя
 */
class UFAuth implements ControllerInterface
{
    public static function needAdminRights(): bool
    {
        return false;
    }

    public static function needAuthorization(): bool
    {
        return false;
    }

    public function getApiMethodList(string $httpMethod): array
    {
        return [];
    }
}