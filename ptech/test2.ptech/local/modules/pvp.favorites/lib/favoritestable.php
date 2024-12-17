<?php

namespace PVP\Favorites;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Orm\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

class FavoritesTable extends DataManager
{
    const FIELD_ID = 'ID';
    const FIELD_DATE_CREATE = 'DATE_CREATE';
    const FIELD_USER_ID = 'USER_ID';
    const FIELD_PRODUCT_ID = 'PRODUCT_ID';

    public static function getTableName()
    {
        return 'pvp_favorites';
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
            self::FIELD_USER_ID => new Fields\IntegerField(self::FIELD_USER_ID, []),


            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.' . self::FIELD_USER_ID, 'ref.ID')
            ))->configureJoinType('left'),

            self::FIELD_PRODUCT_ID => new Fields\IntegerField(self::FIELD_PRODUCT_ID, []),

            (new Reference(
                'ELEMENT',
                ElementTable::class,
                Join::on('this.' . self::FIELD_PRODUCT_ID, 'ref.ID')
            ))->configureJoinType('left'),
        ];
    }
}