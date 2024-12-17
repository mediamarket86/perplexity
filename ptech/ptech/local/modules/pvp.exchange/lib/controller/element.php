<?php

namespace PVP\Exchange\Controller;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\Traits\GetRequestDataTrait;

class Element implements ControllerInterface
{
    use GetRequestDataTrait;

    const CATALOG_IBLOCK_ID = 26;

    public static function needAdminRights(): bool
    {
        return true;
    }

    public static function needAuthorization(): bool
    {
        return true;
    }

    public function getApiMethodList(string $httpMethod): array
    {
        switch($httpMethod) {
            case 'POST':
                return ['setAdditionalSections'];
                break;
            case 'GET':
                return [];
                break;
            default:
                return [];
        }
    }

    public function setAdditionalSections()
    {
        $data = $this->getData();
        if (empty($data['XML_ID']) || empty($data['ADDITIONAL_SECTIONS']) || ! is_array($data['ADDITIONAL_SECTIONS'])) {
            ErrorManager::getInstance()->addError('Неверные входящие данные');
        }

        $dbItems = ElementTable::getList([
            'filter' => ['IBLOCK_ID' => self::CATALOG_IBLOCK_ID, 'XML_ID' => $data['XML_ID']],
            'select' => ['ID'],
        ])->fetchAll();

        if (empty($dbItems)) {
            ErrorManager::getInstance()->addError('Элемент не найден ' . $data['XML_ID']);
            return;
        }

        $elementId = $dbItems[0]['ID'];

        $dbItems = SectionTable::getList([
            'filter' => [
                'IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                'XML_ID' => $data['ADDITIONAL_SECTIONS'],
            ],
            'select' => ['ID'],
        ])->fetchAll();

        if (empty($dbItems)) {
            ErrorManager::getInstance()->addError('Разделы не найдены');
            return;
        }

        $sections = array_column($dbItems, 'ID');

        $element = new \CIBlockElement();

        $dbItems = \CIBlockElement::GetElementGroups($elementId, false, ['ID']);
        while($res = $dbItems->GetNext()) {
            $sections[] = $res['ID'];
        }

        $sections = array_unique($sections);

        if (! $element->Update($elementId, ['IBLOCK_SECTION' => $sections])) {
            ErrorManager::getInstance()->addError($element->LAST_ERROR);
        }
    }
}