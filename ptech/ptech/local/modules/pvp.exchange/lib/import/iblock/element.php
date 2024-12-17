<?php

namespace PVP\Exchange\Import\Iblock;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Main\Application;
use PVP\Exchange\Container;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\Response\Response;

class Element
{
    protected $elementObj;
    protected static $tableClass = ElementTable::class;

    use \PVP\Exchange\Traits\XmlIdConvert;

    public function __construct()
    {
        $this->elementObj = Container::getInstance()->make(\CIBlockElement::class);
    }

    public function add($data)
    {
        if (ElementTable::getCount(['IBLOCK_ID' => $data['FIELDS']['IBLOCK_ID'], 'XML_ID' => $data['FIELDS']['XML_ID']])) {
            ErrorManager::getInstance()->addError($data['FIELDS']['XML_ID'] . ' элемент c таким внешним кодом уже существует!');

            return;
        }

        if (empty($data['FIELDS']['CODE'])) {
            $data['FIELDS']['CODE'] = $this->generateSlug($data['FIELDS']['IBLOCK_ID'], $data['FIELDS']['NAME']);
        }

        $result = $this->elementObj->Add(
            $data['FIELDS'],
            false,
            false,
            false,
        );

        $this->checkResult($result);
    }

    public function update($data)
    {
        $result = $this->elementObj->Update(
            $data['ID'],
            $data['FIELDS'],
            false,
            false,
            false,
            false,
        );

        if ($this->checkResult($result)) {
            $this->clearInerhitedProperty($data['IBLOCK_ID'], $data['ID']);
        }
    }

    public function delete($data)
    {
        $connection = Application::getInstance()->getConnectionPool()->getConnection();

        $connection->startTransaction();
        if(! $this->elementObj::Delete($data['ID'])) {
            ErrorManager::getInstance()->addError('Ошибка при удалении, ID:' . $data['ELEMENT_ID']);
            $connection->rollbackTransaction();
        } else {
            $connection->commitTransaction();
        }
    }

    protected function checkResult($result):bool
    {
        if (! $result) {
            ErrorManager::getInstance()->addError($this->elementObj->LAST_ERROR);
            return false;
        }

        return true;
    }

    protected function generateSlug($iblockId, $name): string
    {
        $tempCode = $this->elementObj->generateMnemonicCode($name, $iblockId);

        $count = 0;
        $code = $tempCode;
        while ($this->slugExists($iblockId, $code)) {
            ++$count;
            $code = $tempCode . '-' . $count;
        }

        return $code;
    }

    protected function slugExists($iblockId, $code): bool
    {
        return ElementTable::getCount([
            'IBLOCK_ID' => $iblockId,
            'CODE' => $code
        ]);
    }

    protected function clearInerhitedProperty($iblockId, $elementId)
    {
        $ipropValues = new ElementValues($iblockId, $elementId);

        $ipropValues->clearValues();
    }
}