<?php


namespace PVP\ExchangeV1\Methods;


use Bitrix\Main\ORM\Objectify\Collection;
use Bitrix\Sale\Order;


class GetOrders extends \PVP\ExchangeV1\Method
{
    public function execute()
    {
        // TODO: Implement execute() method.

        if (! \Bitrix\Main\Loader::includeModule('sale')) {
            Throw new \Exception('Module sale not installed');
        }

        $orders = Order::loadByFilter([
            'filter' => ['>=ID' => $this->param]
        ]);

        ob_end_clean();

        $result = [];
        foreach ($orders as $order) {
            $orderData = [];
            $paymentCollection = $order->getPaymentCollection();
            $shipmentColection = $order->getShipmentCollection()->getNotSystemItems();
            $properties = $order->getPropertyCollection()->getArray();
            $basket = $order->getBasket();

            $user = \CUser::GetByID($order->getUserId())->fetch();

            $orderData = [
                'ID' => $order->getId(),
                'TIME' => $order->getDateInsert()->toString(),
                'PRICE' => $order->getPrice(),
                'PERSON_TYPE' => $order->getPersonTypeId(),
                'USER_LOGIN' => $user['LOGIN'],
            ];


            foreach ($properties['properties'] as $property) {
                $orderData['PROPERTIES'][$property['CODE']] = $property['VALUE'];
            }

            foreach ($paymentCollection as $payment) {
                $orderData['PAYMENT'][] = [
                    'ID' => $payment->getPaymentSystemId(),
                    'NAME' => $payment->getPaymentSystemName(),
                ];
            }

            foreach ($shipmentColection as $shipment) {
                $orderData['SHIPMENT'][] = [
                    'ID' => $shipment->getDeliveryId(),
                    'NAME' => $shipment->getDeliveryName(),
                ];

            }
            \Bitrix\Sale\BasketItem::class;
            \Bitrix\Sale\Basket::class;
            foreach ($basket as $basketItem) {
//                dd($basketItem);

                $offerXmlId = '';
                $xmlIdAr = explode('#', $basketItem->getField('PRODUCT_XML_ID'));

                if (2 == count($xmlIdAr)) {
                    $offerXmlId = $xmlIdAr[1];
                } elseif (2 < count($xmlIdAr)) {
                    AddMessage2Log('Проблемы с XML_ID, содержит симовл # не только для разделения оффера ' . $basketItem->getField('PRODUCT_XML_ID'));
                }

                $orderData['PRODUCTS'][] = [
                    'PRODUCT_ID' => $basketItem->getProductId(),
                    'PRODUCT_XML_ID' => $basketItem->getField('PRODUCT_XML_ID'),
                    'OFFER_XML_ID' => $offerXmlId,
                    'NAME' => $basketItem->getField('NAME'),
                    'PRICE' => $basketItem->getPrice(),
                    'PRICE_TYPE_ID' => $basketItem->getField('PRICE_TYPE_ID'),
                    'BASE_PRICE' => $basketItem->getField('BASE_PRICE'),
                    'QUANTITY' => $basketItem->getQuantity(),
                    'NOTES' => $basketItem->getField('NOTES'),
                ];
            }

            $result[] = $orderData;
        }

        $this->result = $result;
    }
}