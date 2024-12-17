<?php
namespace PVP\ExchangeV1\Controllers;

use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\OrderPropsTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\Internals\UserPropsValueTable;

class User extends \PVP\ExchangeV1\AuthorizedController
{
    const PERSON_PROFILE = 0;
    const COMPANY_PROFILE = 0;

    protected $userObj;

    public function __construct($param)
    {
        parent::__construct($param);

        $this->userObj = new \CUser();
    }

    public function add()
    {
//        dd(json_encode(
//            ['DATA' =>[
//                "NAME"              => "Сергей",
//                "LAST_NAME"         => "Иванов",
//                "EMAIL"             => "ivanov@microsoft.com",
//                "LOGIN"             => "ivan",
//                "LID"               => "ru",
//                "ACTIVE"            => "Y",
//                "GROUP_ID"          => array(10,11),
//                "PASSWORD"          => "123456",
//                "CONFIRM_PASSWORD"  => "123456",
//                ]
//            ]
//        ));
        if (empty($this->data['DATA']['XML_ID'])) {
            $this->errorManager->addError('Не указан XML_ID');

            return;
        }



        if (! $this->userObj->add($this->data['DATA'])) {
            $this->errorManager->addError($this->userObj->LAST_ERROR);

            return;
        }

        //Бан за спам
//        \CEvent::SendImmediate(
//            'API_USER_ADD',
//            SITE_ID,
//            [
//                'NAME' => $this->data['DATA']['NAME'],
//                'EMAIL' => $this->data['DATA']['EMAIL'],
//                'LOGIN' => $this->data['DATA']['LOGIN'],
//                'PASSWORD' => $this->data['DATA']['PASSWORD'],
//                'UNSUBSCRIBE_LINK' => '',
//            ],
//            'Y',
//            109
//
//        );
    }

    public function update()
    {
        if (! $userId = $this->getUserByXmlId($this->data['XML_ID'])) {
            return;
        }

        if (! $this->userObj->update($userId, $this->data['DATA'])) {
            $this->errorManager->addError($this->userObj->LAST_ERROR);
        }
    }

    public function profileAdd()
    {
        if (! $userId = $this->getUserByXmlId($this->data['XML_ID'])) {
            return;
        }

        if (empty($this->data['PERSON_TYPE_ID'])) {
            $this->errorManager->addError('Не указан PERSON_TYPE_ID');

            return;
        }

        if (empty($this->data['PROFILE_NAME'])) {
            $this->errorManager->addError('Не указан PROFILE_NAME');

            return;
        }

        if (empty($this->data['PROFILE_XML_ID'])) {
            $this->errorManager->addError('Не указан PROFILE_XML_ID');

            return;
        }

        $orderProps = OrderPropsTable::getList([
            'select' => ['ID', 'CODE', 'REQUIRED', 'NAME', 'DEFAULT_VALUE'],
            'filter' => [
                'PERSON_TYPE_ID' =>  $this->data['PERSON_TYPE_ID'],
                'ACTIVE' => 'Y',
            ],
        ])->fetchAll();

        if (empty($orderProps)) {
            $this->errorManager->addError('По указанному PERSON_TYPE_ID не удалось найти ни одного свойства');

            return;
        }

        $orderProps = array_column($orderProps, null, 'CODE');
        foreach ($orderProps as $key => $orderProp) {
            if (empty($this->data['DATA'][$key]) && 'Y' == $orderProp['REQUIRED'] && empty($orderProp['DEFAULT_VALUE'])) {
                $this->errorManager->addError('Поле: ' . $key .  ' обязательно.');

                return;
            }

            $orderProps[$key]['VALUE'] = $this->data['DATA'][$key];
            unset($this->data['DATA'][$key]);
        }

        if (! empty($this->data['DATA'])) {
            $this->errorManager->addError('Переданы неизвестные свойства: ' . var_export($this->data['DATA'], true));

            return;
        }

        $result = UserPropsTable::getList([
            'select' => ['ID'],
            'filter' => [
                'XML_ID' => $this->data['PROFILE_XML_ID'],
            ],
        ])->fetchAll();

        if (! empty($result)) {
            $this->errorManager->addError('PROFILE_XML_ID уже существует');

            return;
        }

        $result = UserPropsTable::add([
            'NAME' => $this->data['PROFILE_NAME'],
            'USER_ID' => $userId,
            'PERSON_TYPE_ID' => $this->data['PERSON_TYPE_ID'],
            'XML_ID' => $this->data['PROFILE_XML_ID'],
        ]);

        if ($result->isSuccess()) {
            $userPropsId = $result->getId();
        } else {
            $this->errorManager->addError($result->getErrorMessages());

            return;
        }

        foreach ($orderProps as $orderProp) {
            if (empty($orderProp['VALUE']) && 'Y' == $orderProp['REQUIRED']) {
                $orderProp['VALUE'] = $orderProp['DEFAULT_VALUE'];
            }

            $result = UserPropsValueTable::add([
               'USER_PROPS_ID' => $userPropsId,
               'ORDER_PROPS_ID' => $orderProp['ID'],
               'NAME' => $orderProp['NAME'],
               'VALUE' => $orderProp['VALUE'],
            ]);

            if (! $result->isSuccess()) {
                $this->errorManager->addError($result->getErrorMessages());
            }
        }
    }

    public function profileUpdate()
    {
        $profile = $this->getUserProfileByXmlId($this->data['PROFILE_XML_ID']);

        if ($this->errorManager->hasErrors()) {
            return;
        }


        $orderProps = OrderPropsTable::getList([
            'select' => ['ID', 'CODE', 'REQUIRED', 'NAME', 'DEFAULT_VALUE'],
            'filter' => [
                'PERSON_TYPE_ID' =>  $profile['PERSON_TYPE_ID'],
                'ACTIVE' => 'Y',
            ],
        ])->fetchAll();

        $orderProps = array_column($orderProps, null, 'CODE');
        $propsToUpdate = [];
        foreach ($this->data['DATA'] as $key => $value) {
            if (isset($orderProps[$key])) {
                if ('Y' == $orderProps[$key]['REQUIRED'] && empty($value) && empty($orderProps[$key]['DEFAULT_VALUE'])) {
                    $this->errorManager->addError($orderProps[$key]['CODE'] . ' обязательно для заполнения');
                }

                $propsToUpdate[$key] = $orderProps[$key];
                $propsToUpdate[$key]['VALUE'] = $value;
                unset($this->data['DATA'][$key]);
            }
        }

        if (! empty($this->data['DATA'])) {
            $this->errorManager->addError('Переданы неизвестные свойства: ' . var_export($this->data['DATA'], true));
        }

        if ($this->errorManager->hasErrors()) {
            return;
        }

        foreach ($propsToUpdate as $prop) {
            $userPropValueRow = UserPropsValueTable::getList([
                'filter' => [
                    'USER_PROPS_ID' => $profile['ID'],
                    'ORDER_PROPS_ID' => $prop['ID'],
                ],
            ])->fetchAll();

            if ($userPropValueRow) {
                $userPropValue =  UserPropsValueTable::getById($userPropValueRow[0]['ID'])->fetchObject();
                $userPropValue->set('VALUE', $prop['VALUE']);
                $userPropValue->save();
                continue;
            }

            $result = UserPropsValueTable::add([
                'USER_PROPS_ID' => $profile['ID'],
                'ORDER_PROPS_ID' => $prop['ID'],
                'NAME' => $prop['NAME'],
                'VALUE' => $prop['VALUE'],
            ]);

            if (! $result->isSuccess()) {
                $this->errorManager->addError($result->getErrorMessages());
            }
        }
    }

    public function profileDelete()
    {
        $profile = $this->getUserProfileByXmlId($this->data['PROFILE_XML_ID']);

        if ($this->errorManager->hasErrors()) {
            return;
        }

        UserPropsTable::delete($profile['ID']);
        $propsValues = UserPropsValueTable::getList([
            'filter' => ['USER_PROPS_ID' => $profile['ID']]
        ])->fetchCollection();

        foreach ($propsValues as $propValue) {
            $propValue->delete();
        }
    }

    public function getUserProfileByXmlId(string $xmlId)
    {
        $profiles = UserPropsTable::getList([
            'filter' => [
                'XML_ID' => $xmlId,
            ],
        ])->fetchAll();

        if (empty($profiles)) {
            $this->errorManager->addError('Не найден XML_ID ' . $this->data['PROFILE_XML_ID']);
        }

        if (1 < count($profiles)) {
            $this->errorManager->addError('Дубликат XML_ID ' . $this->data['PROFILE_XML_ID']);
        }

        return $profiles[0];
    }

    protected function getUserByXmlId(string $xmlId): int
    {
        $user = UserTable::getList([
            'select' => ['ID'],
            'filter' => ['XML_ID' => $xmlId]
        ])->fetchAll();

        if (! $user) {
            $this->errorManager->addError('Пользователь не найден, XML_ID: ' . $this->data['XML_ID']);

            return 0;
        }

        if (1 < count($user)) {
            $this->errorManager->addError('Дубликат внешнего кода, XML_ID: ' . $this->data['XML_ID']);

            return 0;
        }

        return $user[0]['ID'];
    }
}