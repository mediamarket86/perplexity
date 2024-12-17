<?php

namespace PVP\Exchange\Orm;

use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Type\DateTime;

class ImportQueueTable extends DataManager
{
    const FIELD_ID = 'ID';
    const FIELD_DATE_CREATE = 'DATE_CREATE';
    const FIELD_ENTITY_TYPE = 'ENTITY_TYPE';
    const FIELD_METHOD = 'ENTITY_METHOD';
    const FIELD_DATA = 'DATA';

    public static function getTableName()
    {
        return 'pvp_exchange_import_queue';
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
            self::FIELD_ENTITY_TYPE => new Fields\StringField(self::FIELD_ENTITY_TYPE, []),
            self::FIELD_METHOD => new Fields\StringField(self::FIELD_METHOD, []),
            self::FIELD_DATA => new Fields\TextField(self::FIELD_DATA, [
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            return json_encode($value);
                        }
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            return json_decode($value, true);
                        }
                    ];
                }
            ]),
        ];
    }
}