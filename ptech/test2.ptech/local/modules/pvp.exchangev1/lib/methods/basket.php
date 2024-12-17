<?php


namespace PVP\ExchangeV1\Methods;

class Basket extends \PVP\ExchangeV1\Controller
{
    public function __construct($param)
    {
        parent::__construct($param);

    }

    protected function syncBasket()
    {
        $basket = new \PVP\ExchangeV1\Basket\Basket();

        $basket->syncMobileAppBasket($this->data['items']);

        if (! empty($this->data['promoCode'])) {
            $basket->addCoupons($this->data['promoCode']);
        }

        $result = $basket->calculateDiscount();

        $this->result['promoCode'] = array_keys($result['COUPON_LIST']);

        foreach ($result['PRICES']['BASKET'] as $key => $prices) {
            $item = $basket->getBasket()->getItemById($key);

            $this->result['items'][] =[
                'id' => $item->getField('PRODUCT_XML_ID'),
                'price' => $prices['PRICE'],
            ];
        }
    }
}