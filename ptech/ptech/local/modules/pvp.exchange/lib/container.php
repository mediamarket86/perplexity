<?php


namespace PVP\Exchange;


class Container
{
    protected static $instance;
    protected $instances;

    protected function __construct() {}
    protected function __clone() {}
    protected function __wakeup() {}

    public static function getInstance()
    {
        if (! self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function make(string $abstract, array $parameters = [])
    {
        if (! isset($this->instances[$abstract])) {
            $this->instances[$abstract] = new $abstract();
        }

        return $this->instances[$abstract];
    }
}