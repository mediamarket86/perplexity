<?php

namespace PVP\Exchange\Import\Iblock;

use PVP\Exchange\Container;
use PVP\Exchange\ErrorManager;

class Property
{
    protected $iblockProperties = [];
    protected $propertyObj;
    protected $elementObj;

    public function __construct()
    {
        $this->propertyObj = Container::getInstance()->make(\CIBlockProperty::class);
        $this->elementObj = Container::getInstance()->make(\CIBlockElement::class);
    }

    public function setAll($data)
    {
        if (! $elementId = Element::xmlIdToId($data['IBLOCK_ID'], $data['XML_ID'])) {
            ErrorManager::getInstance()->addError('XML_ID:' . $data['XML_ID'] . ' ИД элемента не найден!');

            return;
        }

        $properties = $this->preparePropertyValues($data);

        $this->elementObj::SetPropertyValues(
            $elementId,
            $data['IBLOCK_ID'],
            $properties,
        );
    }

    public function set($data)
    {
       if (! $elementId = Element::xmlIdToId($data['IBLOCK_ID'], $data['XML_ID'])) {
           ErrorManager::getInstance()->addError('XML_ID:' . $data['XML_ID'] . ' ИД элемента не найден!');

           return;
       }

        $properties = $this->preparePropertyValues($data);
        $this->elementObj::SetPropertyValuesEx(
            $elementId,
            $data['IBLOCK_ID'],
            $properties,
       );
    }

    public function getPropertyByXmlId(int $iblock, string $xmlId)
    {
        $propertyList = $this->getPropertyList($iblock);

        if (isset($propertyList[$xmlId])) {
            return $propertyList[$xmlId];
        }

        return false;
    }

    public static function CodeToXmlId($iblockId, $propertyCode)
    {
        $propertyList = Container::getInstance()->make(self::class)->getPropertyList($iblockId);

        foreach ($propertyList as $property) {
            if ($property['CODE'] == $propertyCode) {
                return $property['XML_ID'];
            }
        }

        ErrorManager::getInstance()->addError('IBLOCK_ID: ' . $iblockId . ' PROPERTY_CODE:' . $propertyCode . ' не найден XML_ID');
        return false;
    }

    public static function XmlIdToId($iblockId, $xmlId)
    {
        $propertyList = Container::getInstance()->make(self::class)->getPropertyList($iblockId);

        if (isset($propertyList[$xmlId])) {
            return $propertyList[$xmlId]['ID'];
        }

        ErrorManager::getInstance()->addError('IBLOCK_ID: ' . $iblockId . ' PROPERTY_XML_ID:' . $xmlId . ' не найден ID');
        return false;
    }

    protected function preparePropertyValues($data)
    {
        if (empty($data['FIELDS'])) {
            ErrorManager::getInstance()->addError('Нет значений свойств!');

            return;
        }



        $propertyList = $this->getPropertyList($data['IBLOCK_ID']);

        $properties = [];
        foreach ($data['FIELDS'] as $xmlId => $dataValues) {
            if (! $propertyId = self::XmlIdToId($data['IBLOCK_ID'], $xmlId)) {
               continue;
            }

            //Сброс свойства
            if (false === $dataValues) {
                $properties[$propertyId] = false;
                continue;
            }

            if (is_array($dataValues)) {
               if ('N' == $propertyList[$xmlId]['MULTIPLE']) {
                   ErrorManager::getInstance()->addError('XML_ID:' . $xmlId . ' - не может содержать множественные значения');
                   continue;
               }
            }

            $values = $this->findEnumValue($data['IBLOCK_ID'], $xmlId, $dataValues);

            $properties[$propertyId] = $values;
        }

        return $properties;
    }

    protected function findEnumValue($iblockId, $xmlId, $dataValues)
    {
        $property = $this->getPropertyList($iblockId)[$xmlId];
        $values = false;

        if (! isset($property['VALUES'])) {
            return $dataValues;
        }

        if (! is_array($dataValues)) {
            $dataValues = [$dataValues];
        }

        foreach ($dataValues as $dataValue) {
            foreach ($property['VALUES'] as $id => $enumValue) {
                if ($dataValue == $enumValue) {
                    $values[] = $id;
                }
            }
        }

        return $values;
    }

    protected function getPropertyList($iblockId, $renew = false)
    {
        if (isset($this->iblockProperties[$iblockId]) && ! $renew) {
            return $this->iblockProperties[$iblockId];
        }

        $res = $this->propertyObj::GetList(
            [],
            ['IBLOCK_ID' => $iblockId]
        );

        $properties = [];
        while ($property = $res->GetNext()) {
            if (empty($property['XML_ID'])) {
                continue;
            }

            switch ($property['PROPERTY_TYPE']) {
                case 'L':
                    $values = [];
                    $resPropertyEnum = $this->propertyObj::GetPropertyEnum($property['ID']);
                    while($value = $resPropertyEnum->GetNext()) {
                        $values[$value['ID']] = $value['VALUE'];
                    }
                    $property['VALUES'] = $values;
                    break;
                default:
                    break;
            }

            if (isset($properties[$property['XML_ID']])) {
                ErrorManager::getInstance()->addError('Дубликат XML_ID. IBLOCK_ID:' . $iblockId . ' XML_ID:' . $property['XML_ID']);
            }

            $properties[$property['XML_ID']] = $property;
        }

        return $this->iblockProperties[$iblockId] = $properties;
    }


}