<?php
namespace PVP\Exchange\Traits;

use Bitrix\Iblock\ElementTable;

trait ArticleToElementIdTrait
{
    public function articleToElementId(string $article, int $iblockId): int
    {
        $elementId = 0;
		$select = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_CML2_ARTICLE"];
		$filter = ["IBLOCK_ID" => $iblockId, "ACTIVE" => "Y", "=PROPERTY_CML2_ARTICLE" => $article];
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