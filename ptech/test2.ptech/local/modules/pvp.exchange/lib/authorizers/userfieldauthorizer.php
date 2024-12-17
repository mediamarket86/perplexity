<?php

namespace PVP\Exchange\authorizers;

use PVP\Exchange\ExchangeComponent;

class UserFieldAuthorizer implements AuthorizerInteface
{

    public function authorize(string $token): bool
    {
        $exchangeComponent = ExchangeComponent::getInstance();

        if (! $exchangeComponent->get('USER_TOKEN_FIELD')) {
            return false;
        }

        global $USER;

        /**
         * Если поле не существует, найдет всех юзеров
         */
        $userField = \Bitrix\Main\UserFieldTable::getList([
            'select' => ['ID', 'ENTITY_ID', 'FIELD_NAME'],
            'filter' => [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => $exchangeComponent->get('USER_TOKEN_FIELD'),
                'USER_TYPE_ID' => 'string',
            ],
        ])->fetchAll();

        if (empty($userField)) {
            return false;
        }

        $res = $USER::GetList(
            'ID', 'ASC',
            [$exchangeComponent->get('USER_TOKEN_FIELD') => $token],
            [
                'FIELDS' => ['ID', 'LOGIN', 'EMAIL',],
                'SELECT' => [$exchangeComponent->get('USER_TOKEN_FIELD')],
            ],
        );

        if (1 < $res->SelectedRowsCount()) {
            throw new \Exception('Duplicate UF tokens');
        }


        while ($userAr = $res->GetNext()) {
            return $USER->Authorize($userAr['ID']);
        }

        return false;
    }
}