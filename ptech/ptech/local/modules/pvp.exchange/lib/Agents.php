<?php

namespace PVP\Exchange;

use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use PVP\Exchange\Orm\JWTRefreshTable;

class Agents
{
    public static function clearJwtTokens(): string
    {
        $dbItems = JWTRefreshTable::getList([
            'filter' => ['<' . JWTRefreshTable::EXPIRE => new DateTime()],
            'select' => ['ID']
        ])->fetchAll();

        if (empty($dbItems)) {
            return '\PVP\Exchange\Agents::clearJwtTokens();';
        }

        $tokenIds = join(',', array_column($dbItems, 'ID'));

        $connection = Application::getConnection();
        $connection->query('DELETE FROM ' . JWTRefreshTable::getTableName() . ' WHERE ID IN (' . $tokenIds . ')');

        return '\PVP\Exchange\Agents::clearJwtTokens();';
    }
}