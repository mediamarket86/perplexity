<?php

namespace PVP\Exchange\Import\Catalog;

use Bitrix\Catalog\MeasureTable;

class Meashure
{
    static protected $meashures;

    public static function getBySymbol(string $symbol)
    {
        $meashures = self::getMeashures();

        foreach ($meashures as $meashure) {
            if ($meashure['SYMBOL'] == $symbol) {
                return $meashure;
            }
        }

        return false;
    }

    protected static function getMeashures()
    {

        if (empty(self::$meashures)) {
            $dbItems = \CCatalogMeasure::getList(
                [],
                [],
                false,
                false
            );

            $measures = [];
            while ($res = $dbItems->GetNext()) {
                $measures[] = $res;
            }

            self::$meashures = array_column($measures, null, 'CODE');
        }

        return self::$meashures;
    }
}