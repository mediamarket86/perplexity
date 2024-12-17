<?php

namespace PVP\Exchange;

class ExchangeComponent
{
    protected static self $instance;

    protected \CBitrixComponent $component;

    protected array $componentPages = [];

    protected function __construct() {}
    protected function __wakeup() {}
    protected function __clone() {}

    public static function getInstance(): self
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }

        return self::$instance = new self();
    }

    public function set($key, $value): void
    {
        $this->component->arParams[$key] = $value;
    }

    public function get($key): mixed
    {
        if (empty($this->component->arParams[$key])) {
            return false;
        }

        return $this->component->arParams[$key];
    }

    /**
     * @return \CBitrixComponent
     */
    public function getComponent(): \CBitrixComponent
    {
        return $this->component;
    }

    public function getCatalogParam(string $key): mixed
    {
        $catalog = $this->get('CATALOG');

        if (empty($catalog)) {
            Throw new \Exception('Параметры каталога не установлены');
        }

        if (empty($catalog[$key])) {
            return false;
        }

        return $catalog[$key];
    }
    /**
     * @param \CBitrixComponent $component
     * @return void
     * Устанавливает компонент
     */
    public function init(\CBitrixComponent $component): void
    {
        $this->component = $component;
        $this->componentPages = [];
    }

    public function includeComponentPage(string $page, $params = []): void
    {
        $this->componentPages[] = ['page' => $page, 'params' => $params];
    }

    public function getComponentPages(): array
    {
        return $this->componentPages;
    }
}