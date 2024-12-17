<?php

namespace PVP\Exchange\Controller;

use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\Internals\UserPropsValueTable;
use PVP\Exchange\Response\Response;
use PVP\Exchange\Traits\GetRequestDataTrait;

class SaleProfile implements ControllerInterface
{
    use GetRequestDataTrait;

    public function getAll()
    {
        global $USER;

        $profiles = UserPropsTable::getList([
            'filter' => [
                'USER_ID' => $USER->GetID(),
            ],
        ])->fetchAll();

        $profiles = array_column($profiles, null, 'ID');

        $profileIds = array_keys($profiles);

        $dbItems = UserPropsValueTable::getList([
            'filter' => ['USER_PROPS_ID' => $profileIds]
        ])->fetchAll();

        foreach ($dbItems as $item) {
            $profiles[$item['USER_PROPS_ID']]['PROP_VALUES'][] = $item;
        }

        Response::getInstance()->setResponseData($profiles);
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
                return ['getAll'];
                break;
            default:
                return [];
        }
    }
}