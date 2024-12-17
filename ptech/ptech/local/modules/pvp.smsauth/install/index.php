<?
use Bitrix\Main\Localization\Loc;
use PVP\SmsAuth\SmsAuthTable;


Loc::loadMessages(__FILE__);

class Pvp_Smsauth extends CModule
{
    var $MODULE_ID = 'pvp.smsauth';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_SORT;
    var $MODULE_MODE_EXEC = 'local';

    protected string $componentName = 'smsauth';


    protected $connection;

    public function __construct()
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'smsauthtable.php';
        // информация о модуле и разработчике
        $this->MODULE_NAME = 'PVP Авторизация по SMS';
        $this->MODULE_DESCRIPTION = 'Модуль авторизации по коду в СМС';
        $this->PARTNER_NAME = 'Петр Пронюк';
        $this->PARTNER_URI = '';

        // версия
        $arModuleVersion = array(
            'VERSION' => '1.0.1b',
            'VERSION_DATE' => '2023.02.09',
        );
        include('version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->connection = \Bitrix\Main\Application::getConnection();
    }

    public function InstallFiles()
    {
        $path = $_SERVER["DOCUMENT_ROOT"] . "/local/components/pvp";

       if (! CopyDirFiles(__DIR__ . "/components", $path, true, true)) {
            throw new \Exception('Cant copy component');
       }

        rename($path . DIRECTORY_SEPARATOR . $this->componentName . DIRECTORY_SEPARATOR . '.gitignore.tmp',
                $path . DIRECTORY_SEPARATOR . $this->componentName . DIRECTORY_SEPARATOR . '.gitignore');

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/pvp/" . $this->componentName);


        return true;
    }

    public function InstallDB()
    {
        if (! $this->connection->isTableExists(SmsAuthTable::getTableName())) {
            SmsAuthTable::getEntity()->createDbTable();
        }

        return true;
    }

    public function UnInstallDB()
    {
        $this->connection->query('DROP TABLE IF EXISTS ' . SmsAuthTable::getTableName());


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
