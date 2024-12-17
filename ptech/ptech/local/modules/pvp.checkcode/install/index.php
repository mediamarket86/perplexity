<?
use Bitrix\Main\Localization\Loc;
use PVP\Favorites\FavoritesTable;
use PVP\CheckCode\OrderChecksTable;
use PVP\CheckCode\OrderCodesTable;

Loc::loadMessages(__FILE__);

class Pvp_Checkcode extends CModule
{
    var $MODULE_ID = 'pvp.checkcode';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_SORT;
    var $MODULE_MODE_EXEC = 'local';


    protected $connection;

    public function __construct()
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ordercheckstable.php';
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ordercodestable.php';
        // информация о модуле и разработчике
        $this->MODULE_NAME = 'PVP.проверка кода для Ptech';
        $this->MODULE_DESCRIPTION = 'Модуль првоерки кода по ТЗ ptech.ru';
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

    public function InstallFiles()
    {
        $path = $_SERVER["DOCUMENT_ROOT"] . "/local/components/pvp";

        CopyDirFiles(__DIR__ . "/components",
            $path, true, true);

        rename($path . DIRECTORY_SEPARATOR . 'checkcode' . DIRECTORY_SEPARATOR . '.gitignore.tmp',
                $path . DIRECTORY_SEPARATOR . 'checkcode' . DIRECTORY_SEPARATOR . '.gitignore');

        rename($path . DIRECTORY_SEPARATOR . 'checkcodelog' . DIRECTORY_SEPARATOR . '.gitignore.tmp',
                $path . DIRECTORY_SEPARATOR . 'checkcodelog' . DIRECTORY_SEPARATOR . '.gitignore');

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/pvp/checkcode");
        DeleteDirFilesEx("/local/components/pvp/checkcodelog");

        return true;
    }

    public function InstallDB()
    {
        if (! $this->connection->isTableExists(OrderChecksTable::getTableName())) {
            OrderChecksTable::getEntity()->createDbTable();
        }

        if (! $this->connection->isTableExists(OrderCodesTable::getTableName())) {
            OrderCodesTable::getEntity()->createDbTable();
        }

        return true;
    }

    public function UnInstallDB()
    {
        $this->connection->query('DROP TABLE IF EXISTS ' . OrderChecksTable::getTableName());
        $this->connection->query('DROP TABLE IF EXISTS ' . OrderCodesTable::getTableName());

        return true;
    }

    public function DoInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();

        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
