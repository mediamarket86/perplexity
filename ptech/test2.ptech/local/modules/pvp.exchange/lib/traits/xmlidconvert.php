<?php

namespace PVP\Exchange\Traits;



trait XmlIdConvert
{
    protected static $idList = [];

    public static function xmlIdsToIdList(array $xmlIds, int $iblockId): array
    {
        $dbItems = self::getTableClass()::getList([
            'select' => ['ID', 'XML_ID'],
            'filter' => [
                'XML_ID' => $xmlIds,
                'IBLOCK_ID' => $iblockId,
            ],
        ])->fetchAll();

        return array_column($dbItems, null, 'XML_ID');
    }

    public static function getAllIds($iblockId, bool $renew = false): array
    {
        if (empty(self::$idList[$iblockId]) || $renew) {
            $dbItems = self::getTableClass()::getList([
                'select' => ['ID', 'XML_ID'],
                'filter' => [],
            ])->fetchAll();

            self::$idList[$iblockId] = array_column($dbItems, null, 'XML_ID');
        }

        return self::$idList[$iblockId];
    }

    public static function xmlIdToId($iblockId, $xmlId, $renew = false): int
    {
        $idList = self::getAllIds($iblockId, $renew);

        return isset($idList[$xmlId]) ? $idList[$xmlId]['ID'] : 0;
    }

    protected static function getTableClass()
    {
        if (empty(self::$tableClass)) {
            throw new \Exception('Дабовьте "protected static $tableClass = \'Table class\'" перед вызовом трейта');
        }

        return self::$tableClass;
    }
}
