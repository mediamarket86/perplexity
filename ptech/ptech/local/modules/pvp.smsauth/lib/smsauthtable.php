<?php

namespace PVP\SmsAuth;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Orm\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

class SmsAuthTable extends DataManager
{
    const USER_ID = 'USER_ID';
    const SMS_COUNT = 'SMS_COUNT';
    const LAST_SEND = 'LAST_SEND';
    const CHECK_COUNT = 'CHECK_COUNT';
    const HASH = 'HASH';

    public static function getTableName()
    {
        return 'pvp_sms_auth';
    }

    public static function getMap()
    {
        return [
            self::USER_ID => new  Fields\IntegerField(self::USER_ID, [
                'primary' => true,
                'autocomplete' => true,
            ]),

            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.' . self::USER_ID, 'ref.ID')
            ))->configureJoinType('left'),

            self::SMS_COUNT => new  Fields\IntegerField(self::SMS_COUNT, [
                'default_value' => 0,
                'required' => true,
            ]),
            self::CHECK_COUNT => new  Fields\IntegerField(self::CHECK_COUNT, [
                'default_value' => 0,
                'required' => true,
            ]),
            self::LAST_SEND => new Fields\DatetimeField(self::LAST_SEND, [
                'default_value' => new DateTime(),
                'required' => true,
            ]),
            self::HASH => new Fields\StringField(self::HASH, ['required' => true]),
        ];
    }
}