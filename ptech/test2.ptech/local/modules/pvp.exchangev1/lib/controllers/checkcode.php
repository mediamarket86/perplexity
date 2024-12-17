<?php

namespace PVP\ExchangeV1\Controllers;

use Bitrix\Main\Loader;
use PVP\ExchangeV1\AuthorizedController;
use PVP\CheckCode\OrderCodesTable;

class CheckCode extends AuthorizedController
{
    const MODULE_CODE = 'pvp.checkcode';
    const ERROR_WRONG_INPUT = 'Неверные входящие данные!';

    protected \PVP\CheckCode\CheckCode $checkCodeObj;

    public function __construct($param)
    {
        parent::__construct($param);

        if (! Loader::includeModule(self::MODULE_CODE)) {
            $this->addError('Модель ' . self::MODULE_CODE . ' не установлен!');
        }

        $this->checkCodeObj = new \PVP\CheckCode\CheckCode();
    }

    public function add()
    {
        if (! $this->checkData()) {
            return false;
        }

        return $this->checkCodeObj->add($this->data['ORDER_ID'], $this->data['CODE']);
    }

    public function update()
    {
        if (! $this->checkData()) {
            return false;
        }

        return $this->checkCodeObj->update($this->data['ORDER_ID'], $this->data['CODE']);
    }

    public function addOrUpdate()
    {
        if (! $this->checkData()) {
            return false;
        }

        if ($this->checkCodeObj->isOrderExists($this->data['ORDER_ID'])) {
            return $this->update($this->data['ORDER_ID'], $this->data['CODE']);
        }

        return $this->checkCodeObj->add($this->data['ORDER_ID'], $this->data['CODE']);
    }

    protected function checkData(): bool
    {
        if (empty($this->data['ORDER_ID']) || empty($this->data['CODE'])) {
            $this->addError(self::ERROR_WRONG_INPUT);

            return false;
        }

        if (! preg_match('/^[0-9]+$/', $this->data['CODE'])) {
            $this->addError(self::ERROR_WRONG_INPUT);

            return false;
        }

        return true;
    }
}