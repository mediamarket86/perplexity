<?php

namespace PVP\Exchange\Controller;

use Bitrix\Main\GroupTable;
use Bitrix\Main\UserFieldTable;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\Internals\UserPropsValueTable;
use PVP\Exchange\Response\Response;
use PVP\Exchange\Traits\GetRequestDataTrait;

class User implements ControllerInterface
{
    use GetRequestDataTrait;

    public function get()
    {
        global $USER;

        //Здесь получаем телефон и базовые поля, но нет пользовательских
        $user = UserTable::getList([
           'select' => ['NAME', 'SECOND_NAME', 'LAST_NAME', 'LOGIN', 'EMAIL', 'PHONE' => 'PHONE_AUTH.PHONE_NUMBER'],
           'filter' => ['ID' => $USER->GetID()]
        ])->fetch();

        $by = 'ID';
        $order = 'ASC'; //ignore sort

        //Здесь есть пользовательские и все поля таблицы b_user(много лишних), но нет телефона регистрации
        $userFields = \CUser::GetList(
            $by,
            $order,
            ['ID' => $USER->GetID()],
            ['SELECT' => ['UF_USER_MANAGER', 'UF_MANAGER_PHONE']]
        )->Fetch();
        //Берем только нужные пользовательские поля
        foreach ($userFields as $key => $value) {
            if (preg_match('/^UF_/', $key)) {
                $user['USER_FIELDS'][$key] = $value;
            }
        }

        $groupIds = $USER->GetUserGroupArray();
        $groups = GroupTable::getList([
            'select' => ['NAME'],
            'filter' => ['ID' => $groupIds]
        ])->fetchAll();

        $user['DISCOUNT'] = '';
        foreach ($groups as $group) {
            if (preg_match('/[0-9]+%/', $group['NAME'])) {
                $user['DISCOUNT'] = $group['NAME'];
            }
        }

        Response::getInstance()->setResponseData($user);
    }

    public static function needAdminRights(): bool
    {
        return false;
    }

    public static function needAuthorization(): bool
    {
        return true;
    }

    public function getApiMethodList(string $httpMethod): array
    {
        switch($httpMethod) {
            case 'POST':
                return [];
                break;
            case 'GET':
                return ['get'];
                break;
            default:
                return [];
        }
    }
}