<?php

namespace PVP\CheckCode;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Orm\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

class OrderCodesTable extends DataManager
{
    const FIELD_ID = 'ID';
    const FIELD_DATE_CREATE = 'DATE_CREATE';
    const FIELD_ORDER_ID = 'ORDER_ID';
    const FIELD_CODE = 'CODE';

    public static function getTableName()
    {
        return 'pvp_checkorder_order_codes';
    }

    public static function getMap()
    {
        return [
            self::FIELD_ID => new  Fields\IntegerField(self::FIELD_ID, [
                'primary' => true,
                'autocomplete' => true,
            ]),
            self::FIELD_DATE_CREATE => new Fields\DatetimeField(self::FIELD_DATE_CREATE, [
                'default_value' => new DateTime(),
            ]),
            self::FIELD_ORDER_ID => new Fields\StringField(self::FIELD_ORDER_ID, [
                'unique' => true,
                'required' => true,
            ]),
            self::FIELD_CODE => new Fields\IntegerField(self::FIELD_CODE, ['required' => true,]),
        ];
    }
}