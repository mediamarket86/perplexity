<?php

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

$userFields = \Bitrix\Main\UserFieldTable::getList([
    'select' => ['CODE' => 'FIELD_NAME', 'NAME' => 'FIELD_NAME'],
    'filter' => [
        'ENTITY_ID' => 'USER',
        'USER_TYPE_ID' => 'string',
    ],
])->fetchAll();

$userFieldsValues[0] = 'Не использовать';
foreach ($userFields as $field) {
    $userFieldsValues[$field['CODE']] = $field['NAME'];
}

$arComponentParameters = [
    "GROUPS" => [
    ],
    'PARAMETERS' => [
        'SEF_MODE' => array(
            'method' => array(
                'NAME' => GetMessage('PARAM_METHOD_CALL'),
                'DEFAULT' => '#AUTH#/#CONTROLLER#/#METHOD#',
                'VARIABLES' => array(
                    'AUTH',
                    'CONTROLLER',
                    'METHOD'
                ),
            ),
            'methodWithSlash' => array(
                'NAME' => GetMessage('PARAM_METHOD_CALL'),
                'DEFAULT' => '#AUTH#/#CONTROLLER#/#METHOD#/',
                'VARIABLES' => array(
                    'AUTH',
                    'CONTROLLER',
                    'METHOD'
                ),
            ),
        ),
        'AUTH_STRING' => array(
            'NAME' => GetMessage('PARAM_AUTH_STRING'),
            'TYPE' => 'STRING',
            'DEFAULT' => md5(time()),
        ),
        'USER_TOKEN_FIELD' => array(
            'NAME' => GetMessage('PARAM_AUTH_STRING'),
            'TYPE' => 'LIST',
            'VALUES' => $userFieldsValues,
        )

    ],
];