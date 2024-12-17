<?php

namespace PVP\Exchange\Controller;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\ExchangeComponent;
use PVP\Exchange\Controller\Subscribe;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\Traits\PaginationTrait;

class ShopCatalog implements ControllerInterface
{
    use GetRequestDataTrait, PaginationTrait;

    protected int $iblockId;


    public function __construct()
    {
        $this->iblockId = ExchangeComponent::getInstance()->getCatalogParam('IBLOCK_ID');
    }

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
                return ['search', 'element', 'filter'];
                break;
            case 'GET':
                return ['getNew', 'getAll'];
                break;
            default:
                return [];
        }
    }

    public function search(): void
    {
        $exchangeComponent = ExchangeComponent::getInstance();

        if (isset($_REQUEST['q'])) {
            throw new \Exception('Глобальные переменные уже используются!');
        }

        $data = $this->getData();
        $_REQUEST['q'] = $data['query'];

        $this->setPage(2);

        $exchangeComponent->includeComponentPage('shop.search');
    }

    public function getNew(): void
    {
        $exchangeComponent = ExchangeComponent::getInstance();

        $this->setPage(1);

        $filterName = $exchangeComponent->getCatalogParam("FILTER_NAME");

        global ${$filterName};
        $filter = &${$filterName};
        //Поле типа список
        $filter['PROPERTY_NEWPRODUCT_VALUE'] = 'Да';

        $exchangeComponent->includeComponentPage('shop.sectionFiltred');
    }

    public function getAll(): void
    {
        $exchangeComponent = ExchangeComponent::getInstance();
		$params["PAGE_ELEMENT_COUNT"] = $this->getPageLimit();
        $this->setPage(1);

        $exchangeComponent->includeComponentPage('shop.sectionFiltred', $params);
    }

    public function filter(): void
    {
        global $USER;
        $allowedField = ['PROPERTY_CML2_ARTICLE', 'CATALOG_QUANTITY', 'CATALOG_MEASURE_RATIO'];
        $params = [];
        $exchangeComponent = ExchangeComponent::getInstance();

        $filterName = $filterName = $exchangeComponent->getCatalogParam("FILTER_NAME");

        global ${$filterName};
        $filter = &${$filterName};

        $data = $this->getData();

        if (isset($data['sectionXmlId'])) {
            $dbItems = SectionTable::getList([
                'select' => ['ID', 'NAME'],
                'filter' => ['XML_ID' => $data['sectionXmlId'], 'IBLOCK_ID' => $this->iblockId],
            ])->fetchAll();

            if (empty($dbItems)) {
                ErrorManager::getInstance()->addError('Раздел не найден: ' . $data['SECTION_XML_ID']);

                return;
            }

            $params['SECTION_ID'] = $dbItems[0]['ID'];
        }

        if($data['isFilteredByMySubscribe']){
            $subscriptions = Subscribe::get($USER->GetID());
            if(!$subscriptions){
                $filter['ID'] = 0;
            }else{
                foreach($subscriptions as $subData){
                    $filter['ID'][] = $subData['ITEM_ID'];
                }
            }
        }

        if (isset($data['filter'])) {
            foreach ((array)$data['filter'] as $field => $value) {
                if (in_array($field, $allowedField)) {
                    $filter[$field] = $value;
                }
            }
        }

        if (isset($data['sort'])) {
            foreach ((array)$data['sort'] as $field => $order) {
                $params['SORT_FIELD'] = $field;
                $params['SORT_ORDER'] = $order;
            }
        }

		$params["PAGE_ELEMENT_COUNT"] = $this->getPageLimit();
        $this->setPage(1);
        $exchangeComponent->includeComponentPage('shop.sectionFiltred', $params);
    }

    public function element(): void
    {
        $data = $this->getData();

        if (empty($data['xmlId'])) {
            ErrorManager::getInstance()->addError('В запросе отсуствует XML_ID');

            return;
        }

        $dbItems = ElementTable::getList([
            'filter' => ['XML_ID' => $data['xmlId'], 'IBLOCK_ID' => $this->iblockId],
            'select' => ['ID', 'IBLOCK_SECTION_ID', 'SECTION_CODE' => 'IBLOCK_SECTION.CODE']
        ])->fetchAll();

        if (empty($dbItems)) {
            ErrorManager::getInstance()->addError('Элемент не найден: ' . $data['xmlId']);

            return;
        }

        $params = ['ELEMENT_ID' => $dbItems[0]['ID'], 'SECTION_CODE' => $dbItems[0]['SECTION_CODE']];

        $exchangeComponent = ExchangeComponent::getInstance();

        $exchangeComponent->includeComponentPage('shop.element', $params);
    }
}