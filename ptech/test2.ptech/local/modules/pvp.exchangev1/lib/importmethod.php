<?php


namespace PVP\ExchangeV1;


use Bitrix\Main\Application;

abstract class ImportMethod extends Method
{
    protected $data;

    public function __construct($param)
    {
        parent::__construct($param);

        if (! \Bitrix\Main\Loader::includeModule('pvp.import')) {
            throw new \Exception('Module pvp.import not installed!');
        }

        $request = Application::getInstance()->getContext()->getRequest();


        //dd(file_get_contents('php://input'));
        $this->data = json_decode(file_get_contents('php://input'), true);

        if (! $request->isPost() || ! $this->data) {

            $this->addError('POST DATA not found');

        }

    }
}