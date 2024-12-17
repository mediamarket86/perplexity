<?php


namespace PVP\ExchangeV1\Methods;


use Bitrix\Iblock\ElementTable;

class Element extends \PVP\ExchangeV1\AuthorizedController
{
    public function updateTags()
    {

        $extIds = array_keys($this->data['DATA']);

//        dd(
//            json_encode([
//                'DATA' => [
//                    'Н-000014394' => ['Тег 1', 'tag 2', 'tag 3'],
//                    '00000011583' => ['Тег 1', 'tag 2', 'tag 3', 'tag4-2'],
//                    'Н-000006939' => ['Тег 1', 'tag 2', 'tag 3', 'tag4-2', 'tag5-3'],
//                ]
//            ])
//        );

        $elmementIds = ElementTable::getList([
           'select' => ['ID', 'XML_ID'],
            'filter' => [
               'XML_ID' => $extIds,
           ],
        ])->fetchAll();

        $XmlIdsToIdsAr = [];
        foreach ($elmementIds as $value) {
            $XmlIdsToIdsAr[$value['XML_ID']] = $value['ID'];
        }

        $cIBlockElement = new \CIBlockElement();

        foreach ($this->data['DATA'] as $key => $value) {
            if (isset($XmlIdsToIdsAr[$key])) {
                $result = $cIBlockElement->Update($XmlIdsToIdsAr[$key], ['TAGS' => join(',', $value)]);
            }

            if (! $result) {
                $this->errorManager->addError($cIBlockElement->LAST_ERROR);
            }
        }
    }
}