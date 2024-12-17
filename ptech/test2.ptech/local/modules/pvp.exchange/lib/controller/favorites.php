<?php

namespace PVP\Exchange\Controller;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\ExchangeComponent;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\Traits\PaginationTrait;

class Favorites implements ControllerInterface
{
    use GetRequestDataTrait, PaginationTrait;

    protected int $iblockId;
    protected int $userId;
    protected \PVP\Favorites\Favorites $favoriteObj;

    public function __construct()
    {
        $this->iblockId = ExchangeComponent::getInstance()->getCatalogParam('IBLOCK_ID');

        if (! Loader::includeModule('pvp.favorites')) {
            ErrorManager::getInstance()->addError('Модуль не найден: pvp.favorites');
            ExchangeComponent::getInstance()->getComponent()->setBadRequest();
            ExchangeComponent::getInstance()->getComponent()->sendResponse();
        }

        $this->favoriteObj = new \PVP\Favorites\Favorites();

        global $USER;

        $this->userId = $USER->GetID();
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
                return ['add', 'delete', 'filter'];
            case 'GET':
                return ['getAll'];
            default:
                return [];
        }
    }

    public function add(): void
    {
        if (! $elementId = $this->getElementId()) {
            return;
        }

        $this->favoriteObj->add($this->userId, $elementId);
    }

    public function delete(): void
    {
        if (! $elementId = $this->getElementId()) {
            return;
        }

        $this->favoriteObj->delete($this->userId, $elementId);
    }

    public function getAll()
    {
        $exchangeComponent = ExchangeComponent::getInstance();

        $productIDs = array_values($this->favoriteObj->getForUser($this->userId, $this->iblockId));

        $filterName = $exchangeComponent->getCatalogParam("FILTER_NAME");
        //dd($productIDs);
        global ${$filterName};
        $filter = &${$filterName};
        //Поле типа список
        $filter['ID'] = empty($productIDs) ? '0' : $productIDs;

        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->get('nopaging')) {
            //Меняем параметры компонента каталога
            $catalogParams = $exchangeComponent->get('CATALOG');
            $catalogParams['PAGE_ELEMENT_COUNT'] = 200;
            $catalogParams['DISPLAY_TOP_PAGER'] = 'N';
            $catalogParams['DISPLAY_BOTTOM_PAGER'] = 'N';
            $exchangeComponent->set('CATALOG', $catalogParams);
        }

        $exchangeComponent->includeComponentPage('shop.sectionFiltred');
    }

    public function filter()
    {
        $params = [];
        $allowedField = ['IBLOCK_SECTION_ID'];
        $exchangeComponent = ExchangeComponent::getInstance();

        $data = $this->getData();

        $productIDs = array_values($this->favoriteObj->getForUser($this->userId, $this->iblockId));

        $filterName = $exchangeComponent->getCatalogParam("FILTER_NAME");
        //dd($productIDs);
        global ${$filterName};
        $filter = &${$filterName};
        //Поле типа список
        $filter['ID'] = empty($productIDs) ? '0' : $productIDs;

        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->get('nopaging')) {
            //Меняем параметры компонента каталога
            $catalogParams = $exchangeComponent->get('CATALOG');
            $catalogParams['PAGE_ELEMENT_COUNT'] = 200;
            $catalogParams['DISPLAY_TOP_PAGER'] = 'N';
            $catalogParams['DISPLAY_BOTTOM_PAGER'] = 'N';
            $exchangeComponent->set('CATALOG', $catalogParams);
        }

        if (isset($data['filter'])) {
            foreach ((array)$data['filter'] as $field => $value) {
                if (in_array($field, $allowedField)) {
                    //Пустой value будет содержать ИД всех разделов без XML_ID
                    if ('IBLOCK_SECTION_ID' == $field && ! empty($value)) {
                       $value = $this->sectionXmlIdToId($value);
                    }

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

        $exchangeComponent->includeComponentPage('shop.sectionFiltred', $params);
    }

    protected function getElementId(): int
    {
        $data = $this->getData();

        if (empty($data['xmlId'])) {
            ErrorManager::getInstance()->addError('В запросе отсуствует XML_ID');

            return 0;
        }

        $dbItems = ElementTable::getList([
            'filter' => ['XML_ID' => $data['xmlId'], 'IBLOCK_ID' => $this->iblockId],
            'select' => ['ID', 'IBLOCK_SECTION_ID', 'SECTION_CODE' => 'IBLOCK_SECTION.CODE']
        ])->fetchAll();

        if (empty($dbItems)) {
            ErrorManager::getInstance()->addError('Элемент не найден: ' . $data['xmlId']);

            return 0;
        }

        return (int)$dbItems[0]['ID'];
    }

    protected function sectionXmlIdToId(array $xmlIds): array
    {
        $dbItems = SectionTable::getList([
            'select' => ['ID'],
            'filter' => ["IBLOCK_ID" => $this->iblockId,"XML_ID" => $xmlIds],
        ])->fetchAll();

        $result = array_column($dbItems, 'ID');

        return $result;
    }
}