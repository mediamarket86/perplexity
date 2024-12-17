<?php

namespace PVP\Exchange\Controller;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use PVP\Exchange\Response\Response;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\Traits\GetRequestDataTrait;

class Subscribe implements ControllerInterface
{
    use GetRequestDataTrait;

    const CATALOG_IBLOCK_ID = 26;

    public static function needAdminRights(): bool
    {
        return false;
    }

    public static function needAuthorization(): bool
    {
        return true;
    }

    public function getApiMethodList(string $httpMethod): array
    {
        switch($httpMethod) {
            case 'POST':
                return ['add', 'delete'];
                break;
            case 'GET':
                return [];
                break;
            default:
                return [];
        }
    }

    public function add() : int
    {
        global $USER;
        $userId = $USER->GetID();
        $data = $this->getData();
        if (!$data['productId']){
            ErrorManager::getInstance()->addError('Не передан идентификатор товара, на который оформляется подписка');
        }
        $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;        
        $subscribeData = array(
            'USER_CONTACT' => $USER->getEmail() ? $USER->getEmail() : false,
            'ITEM_ID' => $data['productId'], 
            'SITE_ID' => 'pb',
            'CONTACT_TYPE' => \Bitrix\Catalog\SubscribeTable::CONTACT_TYPE_EMAIL,
            'USER_ID' => $userId ? $userId : false,
        );
        $subscribeId = $subscribeManager->addSubscribe($subscribeData);
        if(!$subscribeId){
            ErrorManager::getInstance()->addError('Ошибка подписки:' . var_export($subscribeManager->getErrors(), 1));
        }
        Response::getInstance()->setResponseData(['subscribeId' => $subscribeId]);

        return $subscribeId;
    }

    public function delete() : int
    {
        global $USER;
        $data = $this->getData();
        if (!$data['productId']){
            ErrorManager::getInstance()->addError('Не передан идентификатор товара, по которому удаляется подписка');
        }

        $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;

        $userSubscribes = static::get($USER->getID(), $data['productId']);
        foreach($userSubscribes as $userSubscribe){
            $userSubscribesIds[] = $userSubscribe['ID'];
        }
        if($userSubscribesIds){
            $isDeleted = $subscribeManager->deleteManySubscriptions($userSubscribesIds, $data['productId']);
        }else{
            ErrorManager::getInstance()->addError('У пользователя нет подписок на продукт');
        }

        if(!$isDeleted)
        {
            ErrorManager::getInstance()->addError('Ошибка удаления подписки:' . var_export($subscribeManager->getErrors(), 1));
        }
        Response::getInstance()->setResponseData(['isDeleted' => $isDeleted]);

        return $isDeleted;
    }

    public static function get(int $userId, int $itemId = NULL) : array | null
    {
        $filter = ($itemId) ? ["USER_ID" => $userId, 'ITEM_ID' => $itemId] : ["USER_ID" => $userId];

        $subscribes = \Bitrix\Catalog\SubscribeTable::getList([
            'select'  => ["ID", "USER_ID", "ITEM_ID"],
            'filter'  => $filter
            ]
        );

        return $subscribes->fetchAll();
    }
}