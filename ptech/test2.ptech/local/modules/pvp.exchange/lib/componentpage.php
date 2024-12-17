<?php

namespace PVP\Exchange;

class ComponentPage
{
    public function __construct(protected string $page, protected array $result)
    {}

    public function render()
    {
        if (file_exists($this->page)) {
            try {
                global $APPLICATION;

                $exchangeComponent = ExchangeComponent::getInstance();
                $component = $exchangeComponent->getComponent();
                $arResult = $this->result;

				include $this->page;

            } catch (\Error $e) {
                ErrorManager::getInstance()->addError($e->getMessage() . PHP_EOL . $e->getFile() . ':' . $e->getLine());
            }
        } else {
            throw new \Exception('Component page not found: ' . $this->page);
        }
    }
}