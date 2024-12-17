<?php
if ($arResult['USER_LOGIN'] && !is_array($arParams['AUTH_RESULT'])) {
    $userData = \Bitrix\Main\UserTable::getList([
        'filter' => [
            'LOGIN' => $arResult['USER_LOGIN']
        ],
        'select' => ['ID']
    ]);
    $userExist  = $userData->getSelectedRowsCount() > 0;
    if ($userExist) {
        $arParams['AUTH_RESULT'] = $arParams['~AUTH_RESULT'] = [
            'MESSAGE' => 'Пользователь успешно зарегистрирован',
            'TYPE' => 'OK',
            'ID' => $userData->fetchAll()[0]['ID']
        ];
    }
}