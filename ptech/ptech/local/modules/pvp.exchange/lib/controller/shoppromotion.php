<?php

namespace PVP\Exchange\Controller;

use PVP\Exchange\ExchangeComponent;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\Traits\PaginationTrait;

class ShopPromotion implements ControllerInterface
{
    use GetRequestDataTrait, PaginationTrait;

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
                return [];
                break;
            case 'GET':
                return ['getAds'];
                break;
            default:
                return [];
        }
    }

    public function getAds()
    {
        $this->setPage(1);

        $exchangeComponent = ExchangeComponent::getInstance();

        $exchangeComponent->includeComponentPage('shop.promotionAds');
    }
}