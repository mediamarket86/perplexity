<?php


namespace PVP\ExchangeV1;

use Bitrix\Main\Application;

abstract class Controller extends Method
{
    protected $data;
    protected $error = false;

    public function __construct($param)
    {
        parent::__construct($param);

        $input = file_get_contents('php://input');

        $this->data = json_decode($input, true);

        if (self::DEBUG) {

            $log = $input . "\r\n\r\n";
            $log .= var_export($this->data, true);

            AddMessage2Log($log);
        }

        $request = Application::getInstance()->getContext()->getRequest();

        if (! $request->isPost() || ! $this->data) {
            $this->addError('POST DATA not found');
        }
    }

    public function Execute()
    {
        if ($this->hasError()) return;

        if (method_exists($this, $this->param)) {
            try {
                $this->{$this->param}();
            } catch (\Exception $e) {
                $this->addError($e->getCode() . PHP_EOL . $e->getFile() . ':' . $e->getLine() . PHP_EOL . $e->getMessage());
            }
        } else {
            $this->addError(static::class . '@' . $this->param . ' method not found!');
        }
    }
}