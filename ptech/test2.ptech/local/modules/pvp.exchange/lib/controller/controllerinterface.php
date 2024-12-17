<?php
namespace PVP\Exchange\Controller;

interface ControllerInterface
{
    public static function needAdminRights(): bool;
    public static function needAuthorization(): bool;

    /**
     * @param string $httpMethod
     * @return array
     * Возвращает массив методов API доступных в контроллере для текщего метода запроса(GET|POST)
     */
    public function getApiMethodList(string $httpMethod): array;
}
