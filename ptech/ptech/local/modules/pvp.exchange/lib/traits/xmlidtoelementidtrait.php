<?php
namespace PVP\Exchange\Traits;

use Bitrix\Iblock\ElementTable;

trait XmlIdToElementIdTrait
{
    public function xmlIdToElementId(string $xmlId, int $iblockId): int
    {
        $dbItems = ElementTable::getList([
            'filter' => ['XML_ID' => $xmlId, 'IBLOCK_ID' => $iblockId],
            'select' => ['ID']
        ])->fetchAll();

        return (empty($dbItems) ? 0 : (int)$dbItems[0]['ID']);
    }
}