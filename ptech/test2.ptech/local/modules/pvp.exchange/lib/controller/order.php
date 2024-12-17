<?php

namespace PVP\Exchange\Controller;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Application;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\Internals\UserPropsValueTable;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\ExchangeComponent;
use PVP\Exchange\Response\Response;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\Traits\PaginationTrait;

class Order implements ControllerInterface
{
    use GetRequestDataTrait, PaginationTrait;

    public function create()
    {
        $application = Application::getInstance();
        global $USER;

        $data = $this->getData();

        if (empty($data['profileXmlId'])) {
            ErrorManager::getInstance()->addError('Не задан profileXmlId');

            return;
        }

        $profile = UserPropsTable::getList([
           'filter' => ['USER_ID' => $USER->GetID(), 'XML_ID' => $data['profileXmlId']]
        ])->fetch();

        if (empty($profile)) {
            ErrorManager::getInstance()->addError('Профиль не найден или не принадлежит пользователю');

            return;
        }

        $userPropsValues = UserPropsValueTable::getList([
            'filter' => ['USER_PROPS_ID' => $profile['ID']],
        ])->fetchAll();

        $userPropsValues = array_column($userPropsValues, null, 'ORDER_PROPS_ID');

        if (empty($data['paySystemXmlId'])) {
            ErrorManager::getInstance()->addError('Не задан paySystemXmlId');

            return;
        }

        $paySystem = \Bitrix\Sale\PaySystem\Manager::getList([
            'filter' => ['XML_ID' => $data['paySystemXmlId']]
        ])->fetch();

        if (empty($paySystem)) {
            ErrorManager::getInstance()->addError('Способ оплаты не найден');

            return;
        }

        $fuser = Fuser::getId();
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser($fuser, $application->getContext()->getSite());

        if (0 == $basket->count()) {
            ErrorManager::getInstance()->addError('Корзина пуста');

            return;
        }

        $order = \Bitrix\Sale\Order::create(
            $application->getContext()->getSite(),
            $USER->GetID(),
            CurrencyManager::getBaseCurrency()
        );

        $order->setBasket($basket);

        $order->setPersonTypeId($profile['PERSON_TYPE_ID']);

        foreach ($order->getPropertyCollection() as $property) {
            $propertyId = $property->getField('ORDER_PROPS_ID');

            if (isset($userPropsValues[$propertyId])) {
                $property->setValue($userPropsValues[$propertyId]['VALUE']);
            }
        }

        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $payment->setFields([
            'PAY_SYSTEM_ID' => $paySystem['ID'],
            'PAY_SYSTEM_NAME' => $paySystem['NAME'],
            'SUM' => number_format($basket->getPrice(), 2, ".", "")
        ]);

        $order->setFields([
            'STATUS_ID' => 'N',
            'USER_DESCRIPTION' => (empty($data['comment']) ? '' : $data['comment']),
        ]);

        $order->doFinalAction(true);
        $result = $order->save();


        if ($result->isSuccess()) {
            Response::getInstance()->setResponseData(['ORDER_ID' => $order->getId()]);
        } else {
            ErrorManager::getInstance()->addError($result->getErrorMessages());
        }
    }

    public function cancel()
    {
        $application = Application::getInstance();
        global $DB, $USER;

        $data = $this->getData();

        if (empty($data['orderId'])) {
            ErrorManager::getInstance()->addError('Не передан идентификатор заказа orderId');

            return;
        }

         $order = \Bitrix\Sale\Order::load(
            $data['orderId']
        );

        if($order->getUserId() !== $USER->GetID()){
            ErrorManager::getInstance()->addError('Вы не являетесь владельцем отменяемого заказа');
        }
        $order->setFields([
            "CANCELED" => 'Y',
            "REASON_CANCELED" => ( $data['reason'] <> '' ? $data['reason']: false ),
            "EMP_CANCELED_ID" => ( intval($USER->GetID())>0 ? intval($USER->GetID()) : false )
        ]);

        $result = $order->save();

        if ($result->isSuccess()) {
            Response::getInstance()->setResponseData(['ORDER_ID' => $order->getId(), 'STATUS' => 'CANCELED']);
        } else {
            ErrorManager::getInstance()->addError($result->getErrorMessages());
        }
    }

    public static function needAdminRights(): bool
    {
        return false;
    }

    public static function needAuthorization(): bool
    {
        return true;
    }

    public function getActive()
    {
        $this->setPage(1);

        //Так надо
        $_REQUEST["filter_history"] = "N";

        $exchangeComponent = ExchangeComponent::getInstance();

        $exchangeComponent->includeComponentPage('order.list');
    }

    public function getCanceled()
    {
        $this->setPage(1);

        //Так надо
        $_REQUEST["filter_history"] = "Y";
        $_REQUEST["show_canceled"] = "Y";

        $exchangeComponent = ExchangeComponent::getInstance();

        $exchangeComponent->includeComponentPage('order.list');
    }

    public function getArchive()
    {
        $this->setPage(1);

        //Так надо
        $_REQUEST["filter_history"] = "Y";

        $exchangeComponent = ExchangeComponent::getInstance();

        $exchangeComponent->includeComponentPage('order.list');
    }

    public function getApiMethodList(string $httpMethod): array
    {
        switch($httpMethod) {
            case 'POST':
                return ['create', 'cancel'];
                break;
            case 'GET':
                return ['getCanceled', 'getActive', 'getArchive'];
                break;
            default:
                return [];
        }
    }
}