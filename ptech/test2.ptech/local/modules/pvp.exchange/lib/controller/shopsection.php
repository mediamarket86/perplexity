<?php

namespace PVP\Exchange\Controller;

use Bitrix\Iblock\SectionTable;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\ExchangeComponent;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\Traits\PaginationTrait;

class ShopSection implements ControllerInterface
{
    use PaginationTrait, GetRequestDataTrait;

    protected int $iblockId;

    public function __construct()
    {
        $this->iblockId = ExchangeComponent::getInstance()->getCatalogParam('IBLOCK_ID');
    }

    public function getApiMethodList(string $httpMethod): array
    {
        switch($httpMethod) {
            case 'POST':
                return ['filter'];
                break;
            case 'GET':
                return [];
                break;
            default:
                return [];
        }
    }

    public function filter(): void
    {
        $params = [];
        $exchangeComponent = ExchangeComponent::getInstance();

        $data = $this->getData();

        if (isset($data['xmlId'])) {
            $dbItems = SectionTable::getList([
                'select' => ['ID', 'NAME'],
                'filter' => ['XML_ID' => $data['xmlId'], 'IBLOCK_ID' => $this->iblockId],
            ])->fetchAll();

            if (empty($dbItems)) {
                ErrorManager::getInstance()->addError('Раздел не найден: ' . $data['SECTION_XML_ID']);

                return;
            }

            $params['SECTION_ID'] = $dbItems[0]['ID'];
        }

        $exchangeComponent->includeComponentPage('shop.sectionListFiltred', $params);
    }

    public static function needAdminRights(): bool
    {
        return false;
    }

    public static function needAuthorization(): bool
    {
        return true;
    }
}