<?php


namespace PVP\ExchangeV1;


class MethodFactory
{
    public static function create(string $class, $method): MethodInterface
    {
        $controllerDirs = [
            'controllers',
            'methods',
        ];

        foreach ($controllerDirs as $controllerDir) {
            $classFullName = __NAMESPACE__ . '\\' . ucfirst($controllerDir) . '\\' . $class;

            if (class_exists($classFullName)) {
                return new $classFullName($method);
            }
        }

        return new Methods\NullObject($method);
    }
}