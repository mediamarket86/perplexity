<?php

namespace PVP\Exchange\Traits;

use Bitrix\Main\Application;

trait PaginationTrait
{
    public function setPage(int $navLevel): void
    {
        $request = Application::getInstance()->getContext()->getRequest();

        global $NavNum;
        /**
         * Опасное место, NavNum инкременируется каждым вызовом пагинации
         */
        if ($request->get('page')) {
            $pagenName = 'PAGEN_' . ((int)$NavNum + $navLevel);
            global ${$pagenName};

            ${$pagenName} = $request->get('page');
        }
    }

    public function getPageLimit(): int|null
    {
        $request = Application::getInstance()->getContext()->getRequest();
        
        return $request->get('pageLimit');
    }
}