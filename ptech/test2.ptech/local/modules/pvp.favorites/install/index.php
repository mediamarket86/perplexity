<?
use Bitrix\Main\Localization\Loc;
use PVP\Favorites\FavoritesTable;

Loc::loadMessages(__FILE__);

class Pvp_Favorites extends CModule
{
    var $MODULE_ID = 'pvp.favorites';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_SORT;
    var $MODULE_MODE_EXEC = 'local';

    protected $AGENT_CLEAR_FUNC = '\PVP\\Favorites\\Favorites::clearAgent();';

    protected $connection;

    public function __construct()
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'favoritestable.php';

        // информация о модуле и разработчике
        $this->MODULE_NAME = 'PVP.Избранное для Ptech';
        $this->MODULE_DESCRIPTION = 'Модуль избранного по ТЗ ptech.ru';
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


    public function InstallDB()
    {
        if (! $this->connection->isTableExists(FavoritesTable::getTableName())) {
            FavoritesTable::getEntity()->createDbTable();
        }

        return true;
    }

    public function UnInstallDB()
    {
        $this->connection->query('DROP TABLE IF EXISTS ' . FavoritesTable::getTableName());

        return true;
    }

    public function DoInstall()
    {
        $this->InstallDB();

        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

        CAgent::AddAgent(
            $this->AGENT_CLEAR_FUNC,
            $this->MODULE_ID
        );
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        CAgent::RemoveAgent(
            $this->AGENT_CLEAR_FUNC,
            $this->MODULE_ID
        );
    }
}
