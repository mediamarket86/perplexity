<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
    "GROUPS" => [
    ],
    'PARAMETERS' => [
        'SEF_MODE' => array(
            'method' => array(
                'NAME' => GetMessage('PVP_EXCHANGE_PARAM_METHOD_CALL'),
                'DEFAULT' => '#CONTROLLER#/#METHOD#',
                'VARIABLES' => array(
                    'CONTROLLER',
                    'METHOD'
                ),
            ),
        ),

        'DEBUG_MODE' => [
            'NAME' => GetMessage('PVP_EXCHANGE_PARAM_DEBUG_MODE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],

        'AUTH_METHOD' => [
            'NAME' => GetMessage('PVP_EXCHANGE_PARAM_AUTH_METHOD'),
            'TYPE' => 'LIST',
            'REFRESH' => 'Y',
            'DEFAULT' => 'JWT',
            'VALUES' => [
                'JWT' => GetMessage('PVP_EXCHANGE_PARAM_AUTH_JWT'),
                'UF' => GetMessage('PVP_EXCHANGE_PARAM_AUTH_USER_FIELD'),
            ],
        ]
    ],
];


if ('UF' == $arCurrentValues['AUTH_METHOD']) {
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

    $arComponentParameters['PARAMETERS']['USER_TOKEN_FIELD'] = array(
        'NAME' => GetMessage('PVP_EXCHANGE_PARAM_AUTH_UF'),
        'TYPE' => 'LIST',
        'VALUES' => $userFieldsValues,
    );
}
