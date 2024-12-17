<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Pvp_Exchangev1 extends CModule
{
    var $MODULE_ID = 'pvp.exchangev1';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_SORT;
    var $MODULE_MODE_EXEC = 'local';

    public function __construct()
    {
        // информация о модуле и разработчике
        $this->MODULE_NAME = 'PVP.Обмен v1(deprecated)';
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
    }


    public function InstallDB()
    {
        return true;
    }

    public function UnInstallDB()
    {
        return true;
    }

    public function InstallFiles()
    {

        return true;
    }

    public function UnInstallFiles()
    {
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
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
