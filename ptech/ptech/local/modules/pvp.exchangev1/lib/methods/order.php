<?php


namespace PVP\ExchangeV1\Methods;

use Bitrix\Main\Context;
use PVP\ExchangeF1\User\User;

class Order extends \PVP\ExchangeV1\Controller
{
    protected $user;

    public function __construct($param)
    {
        parent::__construct($param);

        global $USER;
        $this->user = new User($USER);

        $this->data['contacts']['phone'] = preg_replace('/[^0-9]/', '', $this->data['contacts']['phone']);
        $this->data['contacts']['email'] = filter_var($this->data['contacts']['email'], FILTER_VALIDATE_EMAIL);

        if (! \Bitrix\Main\Loader::includeModule('sale')) {
            Throw  new \RuntimeException('Module sale not installed!');
        }
    }

    public function add()
    {

        if ('Production' != $this->data['environment']) { //Dont do anything on test environment
            return ['orderId' => 3330333];
        }

        if (! $this->user->getUser()->isAuthorized()) {

            if ($this->data['contacts']['phone']) {
                $this->user->authorizeByContactData($this->data['contacts']['phone']);
            }

            if (! $this->user->getUser()->isAuthorized() && $this->data['contacts']['email']) {
                $this->user->authorizeByContactData($this->data['contacts']['email']);
            }

            if (! $this->user->getUser()->isAuthorized()) {
                $userData = new \PVP\ExchangeV1\User\UserData($this->data['contacts']['phone']);

                $userData->setUserNames(
                    (string)$this->data['contacts']['name'],
                    (string)$this->data['contacts']['surname'],
                    (string)$this->data['contacts']['lastName']
                );

                if ($this->data['contacts']['email']) {
                    $userData->setEmail($this->data['contacts']['email']);
                }

                $this->user->createUserAndAuthorize($userData);
            }

            if (! $this->user->getUser()->isAuthorized()) {
                $this->addError('Не удалось создать пользователя.');

                return false;
            }
        }

        $basket = new \PVP\ExchangeV1\Basket\Basket();

        $basket->syncMobileAppBasket($this->data['items']);

        if (! empty($this->data['promoCode'])) {
            $basket->addCoupons($this->data['promoCode']);
        }



        /**
         * LEGACY CODE BELOW
         */

        $order = \Bitrix\Sale\Order::create(Context::getCurrent()->getSite(), $this->user->getUser()->GetID());
        $order->setBasket($basket->getBasket());


        $arCrossPerson = [  // пока прямое соответствие введенным типам персон. 1 - физики, 2 - юрики.
            'person' => 1,
            'jurPerson' => 1, // вообще-то Юрлицо=2, но на сайте не создаются записи в таблице обмена, если тип плательщика=юрлицо
        ];

        $order->setPersonTypeId($arCrossPerson[$this->data['contacts']['status']]); // пропишем тип плательщика тот, который приехал из МП

        $arCrossDelivery = [  // пока прямое соответствие введенным вариантам доставки. 1 - без доставки, 2 - доставка, 3 - самовывоз.
            'delivery' => 2,
            'pickup' => 3,
        ];



        $arDeliveries = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipment->setFields([ // установим доставку приехавшую из МП
            'DELIVERY_ID' => $arDeliveries[$arCrossDelivery[$this->data['deliveryType']]]['ID'],
            'DELIVERY_NAME' => $arDeliveries[$arCrossDelivery[$this->data['deliveryType']]]['NAME'],
        ]);


        $arCrossPay = [  // пока для всех оплат из МП одна и таже на сайте - ONLINE
            'cashToDriver' => 'ONLINE',
            'cardToDriver' => 'ONLINE',
            'inOffice' => 'ONLINE',
            'cashless' => 'ONLINE',
            'webPayment' => 'ONLINE'
        ];

        $arPayments = [];

        $rsPayments = \Bitrix\Sale\PaySystem\Manager::getList();

        while ($row = $rsPayments->fetch()) $arPayments[$row['CODE']] = $row;

        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $payment->setFields([
            'PAY_SYSTEM_ID' => $arPayments[$arCrossPay[$this->data['paymentType']]]['ID'],
            'PAY_SYSTEM_NAME' => $arPayments[$arCrossPay[$this->data['paymentType']]]['NAME'],
            'SUM' => number_format($this->data['total'], 2, ".", "")
        ]);

        $propertyCollection = $order->getPropertyCollection();
        $phoneProp = $propertyCollection->getPhone();
        $phoneProp->setValue($this->data['contacts']['phone']);
        $nameProp = $propertyCollection->getPayerName();
        $nameProp->setValue($this->data['contacts']['name'].($this->data['contacts']['surname']?' '.$this->data['contacts']['surname']:'').($this->data['contacts']['lastName']?' '.$this->data['contacts']['lastName']:''));

        if ($this->data['contacts']['email'])
        {
            $emailProp = $propertyCollection->getUserEmail();
            $emailProp->setValue($this->data['contacts']['email']);
        }

        $crossDeliveryTime = [
            'am' => 'с 9:00 до 15:00',
            'pm' => 'с 15:00 до 21:00'
        ];

        foreach($propertyCollection as $item) {
            if ($item->getPropertyObject()->getField('PERSON_TYPE_ID') == $arCrossPerson[$this->data['contacts']['status']])
                $arProperties[$item->getField('CODE')] = $item;
        }

		//$arProperties['MOBIL_APP']->setValue($this->"Y");

        if (isset($arProperties['F_FIO_SECOND_NAME']) && $this->data['contacts']['lastName']) $arProperties['F_FIO_SECOND_NAME']->setValue($this->data['contacts']['lastName']);

        if (isset($arProperties['DATE']) && $this->data['deliveryOptions']['deliveryDate']) $arProperties['DATE']->setValue(date('d-m-Y',strtotime($this->data['deliveryOptions']['deliveryDate'])));

        if (isset($arProperties['TIME']) && $this->data['deliveryOptions']['deliveryTime'] && !isset($this->data['pickupAddress'])) $arProperties['TIME']->setValue($crossDeliveryTime[$this->data['deliveryOptions']['deliveryTime']]);

#                    if (isset($arProperties['DELIVERY_FAST']) && $this->data['deliveryOptions']['floorDelivery']) $arProperties['DELIVERY_FAST']->setValue('Y'); // нет в МП быстрой доставки
#                    if (isset($arProperties['DELIVERY_SUM']) && $this->data['delivery']) $arProperties['DELIVERY_SUM']->setValue($this->data['delivery']); // (1) доставка может поменяться в 1С (2) мы и так добавляем в заказ псевдотовар "Доставка"

        $addServices = [];
        
        if (! empty($this->data['deliveryOptions']['unload']))
            $addServices[] = 'Манипулятор';

        if (! empty($this->data['deliveryOptions']['manualUnload']))
            $addServices[] = 'Разгрузка';

        if (! empty($this->data['deliveryOptions']['targetIsOver50m']))
            $addServices[] = 'Нести более 50м';

        if (! empty($this->data['deliveryOptions']['floorDelivery']))
            $addServices[] = 'Подъем на этаж';

        if (! empty($this->data['deliveryOptions']['floor']))
            $addServices[] = $this->data['deliveryOptions']['floor'].'-й этаж';

        if (! empty($this->data['deliveryOptions']['elevator']))
            $addServices[] = 'Есть пассажирский лифт';

        if (! empty($this->data['deliveryOptions']['serviceElevator']))
            $addServices[] = 'Есть грузовой лифт';

        if (! empty($this->data['additionalServices']['antiseptic']))
            $addServices[] = 'Антисептирование';

        if (! empty($this->data['additionalServices']['cutting']))
            $addServices[] = 'Резка';

        if (! empty($this->data['additionalServices']['sawing']))
            $addServices[] = 'Распиловка';

        if (! empty($this->data['additionalServices']['coloring']))
            $addServices[] = 'Колеровка';

        if (isset($arProperties['ADD_SERVICES']) && count($addServices)) $arProperties['ADD_SERVICES']->setValue(implode(', ', $addServices));

        $address = [];
        if (isset($this->data['address']['city']['name'])) $address[] = $this->data['address']['city']['name'];
        if (isset($this->data['address']['street'])) $address[] = $this->data['address']['street'];
        if (isset($this->data['address']['house'])) $address[] = 'д.'.$this->data['address']['house'];
        if (isset($this->data['address']['apartment'])) $address[] = 'кв.'.$this->data['address']['apartment'];
        if (isset($this->data['pickupAddress']['title'])) $address[] = 'Самовывоз, '.$this->data['pickupAddress']['title'];
        if (isset($arProperties['ADDRESS']) && count($address)) $arProperties['ADDRESS']->setValue(implode(', ', $address));

#                    if (isset($arProperties['EXTERNAL_ORDER_ID']) && $this->data['number']) $arProperties['EXTERNAL_ORDER_ID']->setValue('МП-'.$this->data['number']); // Нельзя сюда прописывать ничего кроме 1С-ного номера заказа, т.к. это поля является признаком, 1С-ный это заказ или нет.

        $info['number'] = $this->data['number'];
        $info['deliveryType'] = $this->data['deliveryType'];
        if ($this->data['pickupAddress']['title']) $info['pickupAddress'] = $this->data['pickupAddress']['title'];
        if ($this->data['receiverIsNotClient']) $info['receiverContacts'] = $this->data['receiverContacts'];
        $info['deliveryOptions'] = $this->data['deliveryOptions'];
        $info['additionalServices'] = $this->data['additionalServices'];
        $info['paymentType'] = $this->data['paymentType'];
        $info['delivery'] = $this->data['delivery'];
        $info['status'] = $this->data['status'];
        $info['placeId'] = $this->data['placeId'];


        if (isset($arProperties['JSON_INFO'])) {
            $arProperties['JSON_INFO']->setValue(json_encode($info)); // сохраним в свойство заказа "сырую" информацию о заказе
        }

        if (isset($arProperties['USE_BONUS'])) {
            $arProperties['USE_BONUS']->setValue(empty($this->data['bonusesApplied']) ? 0 : (int)$this->data['bonusesApplied']);
        }

        $userDescription = "Заказ из МП №" . $this->data['number']."\r\n".
            (count($addServices)?implode(', ', $addServices)."\r\n":'').
            implode(', ', $address)."\r\n".
            ($this->data['delivery']?'Доставка: '.$this->data['delivery']."руб.\r\n":'').
            ($this->data['receiverIsNotClient']?'Получать будет другой человек: '.$this->data['receiverContacts']['name'].' '.$this->data['receiverContacts']['surname'].', т.'.$this->data['receiverContacts']['phone']."\r\n":'').
            ($this->data['comment']?$this->data['comment']."\r\n":'')
            . "---------------------------------------------\r\n"
            . "ФИО: " . (string)$this->data['contacts']['name'] . ' ' . (string)$this->data['contacts']['surname'] . ' ' . (string)$this->data['contacts']['lastName'] . PHP_EOL
            . "Телефон: " . $this->data['contacts']['phone'] . PHP_EOL
            . "Email: " . $this->data['contacts']['email'] . PHP_EOL
            . "---------------------------------------------" . PHP_EOL;

            if (! empty($this->data['promoCode'])) {
                $userDescription .= "Промокоды:\r\n";

                foreach ($this->data['promoCode'] as $promoCode) {
                    $userDescription .= $promoCode . "\r\n";
                }
            }


        $order->setFields([
            'STATUS_ID' => 'N',
            'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            'USER_DESCRIPTION' => $userDescription,
			
        ]);

        $order->doFinalAction(true);
        $result = $order->save();


        if (! $result->isSuccess()) {
            $this->addError($result->getErrorMessages());
        }

        /**
         * Для новых пользователей выдает ошибку, что не может перезапустить сессию, обходим
         * */
         $this->user->Logout();

         return $this->result = ['orderId' => $order->getId()];
    }

    protected function createUser(): int
    {
        $password = \Bitrix\Main\Security\Random::getString(8);
        $userGroups = \COption::GetOptionString("main", "new_user_registration_def_group", []);

        if (is_string($userGroups)) {
            $userGroups = explode(',', $userGroups);
        }

        $login = empty($this->data['contacts']['phone']) ? $this->data['contacts']['email'] : $this->data['contacts']['phone'];

        $arUser = [
            "LOGIN" => $login,
            "PASSWORD" => $password,
            "CONFIRM_PASSWORD" => $password,
            "NAME" => $this->data['contacts']['name'],
            "SECOND_NAME" => $this->data['contacts']['surname'],
            "LAST_NAME" => $this->data['contacts']['lastName'],
            "PERSONAL_PHONE" => $this->data['contacts']['phone'],
            "ACTIVE" => "Y",
            "LID" => Context::getCurrent()->getSite(),
            "GROUP_ID" => $userGroups,
        ];

        if ($this->data['contacts']['email']) {
            $arUser['EMAIL'] = $this->data['contacts']['email'];
        }

        if ('Y' == \COption::GetOptionString("main", "new_user_phone_required", 'N')) {
            $arUser['PHONE_NUMBER'] = $this->data['contacts']['phone'];
        }

        $userId = $this->user->Add($arUser);

        if (! $userId) {
            AddMessage2Log($this->user->LAST_ERROR);
            $this->addError($this->user->LAST_ERROR);
        }

        return (int)$userId;
    }
}