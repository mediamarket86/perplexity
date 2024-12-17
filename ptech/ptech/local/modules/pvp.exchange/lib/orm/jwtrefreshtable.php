<?php

namespace PVP\Exchange\Orm;

use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

class JWTRefreshTable extends DataManager
{
    const FIELD_ID = 'ID';
    const USER_ID = 'USER_ID';
    const HASH = 'HASH';
    const EXPIRE = 'EXPIRE';


    public static function getTableName()
    {
        return 'pvp_exchange_jwt_refresh';
    }

    public static function getMap()
    {
        return [
            self::FIELD_ID => new  Fields\IntegerField(self::FIELD_ID, [
                'primary' => true,
                'autocomplete' => true,
            ]),

            self::USER_ID => new  Fields\IntegerField(self::USER_ID, [
                'required' => true,
            ]),

            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.' . self::USER_ID, 'ref.ID')
            ))->configureJoinType('left'),

            self::HASH => new Fields\StringField(self::HASH, [
                'unique' => true,
                'required' => true,
            ]),

            self::EXPIRE => new Fields\DatetimeField(self::EXPIRE, [
                'default_value' => new DateTime(),
                'required' => true,
            ]),
        ];
    }
}