<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields;
use PVP\Exchange\Orm\ImportQueueLogTable as LogTable;
use PVP\Exchange\Orm\ImportQueueTable as ImportTable;
use PVP\Exchange\Orm\JWTRefreshTable;

Loc::loadMessages(__FILE__);

class Pvp_Exchange extends CModule
{
    var $MODULE_ID = 'pvp.exchange';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_SORT;

    protected $connection;

    protected $AGENT_CLEAR_FUNC = '\PVP\Exchange\Agents::clearJwtTokens();';

    public function __construct()
    {
        $ormPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .'orm';

        require_once $ormPath . DIRECTORY_SEPARATOR . 'importqueuelogtable.php';
        require_once $ormPath . DIRECTORY_SEPARATOR . 'importqueuetable.php';
        require_once $ormPath . DIRECTORY_SEPARATOR . 'jwtrefreshtable.php';

        // информация о модуле и разработчике
        $this->MODULE_NAME = 'PVP.Обмен';
        $this->MODULE_DESCRIPTION = 'Обмен данными в формате JSON';
        $this->PARTNER_NAME = 'Петр Пронюк';
        $this->PARTNER_URI = '';

        // версия
        $arModuleVersion = array(
            'VERSION' => '',
            'VERSION_DATE' => ''
        );
        include('version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->connection = \Bitrix\Main\Application::getConnection();
    }


    public function InstallDB(): void
    {
        if (! $this->connection->isTableExists(LogTable::getTableName())) {
            LogTable::getEntity()->createDbTable();
        }

        if (! $this->connection->isTableExists(ImportTable::getTableName())) {
            ImportTable::getEntity()->createDbTable();
        }

        if (! $this->connection->isTableExists(JWTRefreshTable::getTableName())) {
            JWTRefreshTable::getEntity()->createDbTable();
        }
    }

    public function UnInstallDB(): void
    {
        $this->connection->query('DROP TABLE IF EXISTS ' . LogTable::getTableName());
        $this->connection->query('DROP TABLE IF EXISTS ' . ImportTable::getTableName());
        $this->connection->query('DROP TABLE IF EXISTS ' . JWTRefreshTable::getTableName());
    }

    public function InstallFiles(): void
    {
    }

    public function UnInstallFiles(): void
    {
    }

    function InstallEvents(): void
    {
        CAgent::AddAgent(
            $this->AGENT_CLEAR_FUNC,
            $this->MODULE_ID
        );
    }

    function UnInstallEvents(): void
    {
        CAgent::RemoveAgent(
            $this->AGENT_CLEAR_FUNC,
            $this->MODULE_ID
        );
    }

    public function DoInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();

        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
