<?php


namespace PVP\Exchange\Import\Iblock;


use Bitrix\Iblock\SectionTable;
use PVP\Exchange\Container;
use PVP\Exchange\ErrorManager;

class Section
{
    protected static $tableClass = SectionTable::class;

    /**
     * @var \CIBlockSection $sectionObj
     */
    protected $sectionObj;

    use \PVP\Exchange\Traits\XmlIdConvert;

    public function __construct()
    {
        $this->sectionObj = Container::getInstance()->make(\CIBlockSection::class);
    }

    public function add($data)
    {
        if (SectionTable::getCount(['IBLOCK_ID' => $data['FIELDS']['IBLOCK_ID'], 'XML_ID' => $data['FIELDS']['XML_ID']])) {
            ErrorManager::getInstance()->addError($data['FIELDS']['XML_ID'] . ' элемент c таким внешним кодом уже существует!');

            return;
        }

        if (empty($data['FIELDS']['CODE'])) {
            $data['FIELDS']['CODE'] = $this->generateSlug($data['FIELDS']['IBLOCK_ID'], $data['FIELDS']['NAME']);
        }

        $result = $this->sectionObj->Add(
            $data['FIELDS'],
                true,
                true,
                false,
            );

        $this->checkResult($result);
    }

    public function update($data)
    {
        $result = $this->sectionObj->Update(
            $data['ID'],
            $data['FIELDS'],
            true,
            true,
            false,
        );

        $this->checkResult($result);
    }

    protected function generateSlug($iblockId, $name): string
    {
        $tempCode = $this->sectionObj->generateMnemonicCode($name, $iblockId);

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
        return SectionTable::getCount([
            'IBLOCK_ID' => $iblockId,
            'CODE' => $code
        ]);
    }

    protected function checkResult($result)
    {
        if (! $result) {
            ErrorManager::getInstance()->addError($this->sectionObj->LAST_ERROR);
        }
    }
}