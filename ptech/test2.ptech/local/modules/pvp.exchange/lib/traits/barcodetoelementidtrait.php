<?php
namespace PVP\Exchange\Traits;

use Bitrix\Iblock\ElementTable;

trait BarCodeToElementIdTrait
{
    public function barCodeToElementId(string $barCode, int $iblockId): int
    {
        $elementId = 0;
		$select = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_SHTRIKHKOD"];
		$filter = ["IBLOCK_ID" => $iblockId, "ACTIVE" => "Y", "=PROPERTY_SHTRIKHKOD" => $barCode];
		$res = \CIblockElement::GetList(["DATE_CREATE" => "DESC"], $filter, false, false, $select);
		$ob = $res->GetNextElement();
		if($ob){
			$arFields = $ob->GetFields();
			if($arFields['ID']){
				$elementId = $arFields['ID'];
			}
		}


        return $elementId;
    }
}