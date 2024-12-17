<?php

namespace PVP\Favorites;


use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;

class Favorites
{
    const SECTION_KEY = 'pvp.favorites.sections';

    public function add(int $userId, int $productId)
    {
        $exist = FavoritesTable::getCount([
            'USER_ID' => $userId,
            'PRODUCT_ID' => $productId
        ]);

        if ($exist) {
            return;
        }

        $result = FavoritesTable::add([
                    'USER_ID' => $userId,
                    'PRODUCT_ID' => $productId
                ]);

       if (! $result->isSuccess()) {
           throw new \Exception(join(PHP_EOL, $result->getErrorMessages()));
       }
    }

    public function delete(int $userId, int $productId)
    {
        $dbItems = FavoritesTable::getList([
            'select' => ['ID'],
            'filter' => ['USER_ID' => $userId, 'PRODUCT_ID' => $productId]
        ])->fetchCollection();

        foreach ($dbItems as $item) {
            $result = $item->delete();

            if (! $result->isSuccess()) {
                throw new \Exception(join(PHP_EOL, $result->getErrorMessages()));
            }
        }
    }

    public function deleteAll(int $userId)
    {
        $dbItems = FavoritesTable::getList([
            'select' => ['ID'],
            'filter' => ['USER_ID' => $userId]
        ])->fetchCollection();

        foreach ($dbItems as $item) {
            $result = $item->delete();

            if (! $result->isSuccess()) {
                throw new \Exception(join(PHP_EOL, $result->getErrorMessages()));
            }
        }
    }


    public function getCount(int $userId)
    {
        $count = FavoritesTable::getCount([
            'USER_ID' => $userId,
            '!ELEMENT.ID' => false,
            'ELEMENT.ACTIVE' => 'Y',
        ]);

        return $count;
    }

    public function getForUser(int $userId, int $iblockId): array
    {
        $sections = $this->getSectionList();

        $filter = [['USER_ID' => $userId, 'ELEMENT.ACTIVE' => 'Y', 'ELEMENT.IBLOCK_ID' => $iblockId]];

        if ($sections) {
            $rootSections = SectionTable::getList([
                'select' => ['ID', 'NAME', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
                'filter' => ['ID' => $sections, 'ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockId]
            ])->fetchAll();


            if ($rootSections) {
                $sectionFilter = [['ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockId,]];

                $tempFilter = ['LOGIC' => 'OR'];
                foreach ($rootSections as $rootSection) {
                    $tempFilter[] = [
                        '>LEFT_MARGIN' => $rootSection['LEFT_MARGIN'],
                        '<RIGHT_MARGIN' => $rootSection['RIGHT_MARGIN']
                        ];
                }

                $sectionFilter[] = $tempFilter;

                $childSections = SectionTable::getList([
                    'select' => ['ID', 'NAME',],
                    'filter' => $sectionFilter,
                ])->fetchAll();

                $sectionIds = array_column($rootSections, 'ID');
                $sectionIds = array_merge($sectionIds, array_column($childSections, 'ID'));

                $filter[] = ['LOGIC' => 'OR', 'ELEMENT.IBLOCK_SECTION_ID' => $sectionIds];
            }
        }

        $dbItems = FavoritesTable::getList([
            'select' => ['PRODUCT_ID'],
            'filter' => $filter,
        ])->fetchAll();

        $productIds = [];
        foreach ($dbItems as $item) {
            $productIds[$item['PRODUCT_ID']] = $item['PRODUCT_ID'];
        }

        return $productIds;
    }

    public function getExistsIdFromList(int $userId, $productIds): array
    {
        $dbItems = FavoritesTable::getList([
            'select' => ['PRODUCT_ID'],
            'filter' => ['USER_ID' => $userId, 'PRODUCT_ID' => $productIds]
        ])->fetchAll();

        $result = array_column($dbItems, 'PRODUCT_ID');

        return $result;
    }

    public static function clearAgent(): string
    {
        $dbItems = FavoritesTable::getList([
            'select' => ['ID', 'ELEMENT_ID' => 'ELEMENT.ID', 'ELEMENT_NAME' => 'ELEMENT.NAME',  'BX_USER_ID' => 'USER.ID'],
            'filter' => [['LOGIC' => 'OR',
                    'ELEMENT.ID' => false,
                    'USER.ID' => false,
            ]],
        ])->fetchCollection();

        foreach ($dbItems as $item) {
            $result = $item->delete();

            if (! $result->isSuccess()) {
                AddMessage2Log(join(PHP_EOL,  $result->getErrorMessages()));
            }
        }

        return __METHOD__ . '();';
    }

    public function setSectionList(array $sectionIds): void
    {
        $session = Application::getInstance()->getSession();

        $sessionSectionIds = [];
        foreach ($sectionIds as $sectionId) {
            $sessionSectionIds[] = (int)$sectionId;
        }

        $session->set(self::SECTION_KEY, $sessionSectionIds);
    }

    public function getSectionList(): array
    {
        return (array)Application::getInstance()->getSession()->get(self::SECTION_KEY);
    }
}