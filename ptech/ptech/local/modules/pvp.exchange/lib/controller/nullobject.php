<?php


namespace PVP\Exchange\Controller;

class NullObject implements ControllerInterface
{
    public function needAdminRights(): bool
    {
        return false;
    }

    public function needAuthorization(): bool
    {
        return false;
    }

    public function getApiMethodList(): array
    {
        return [];
    }
}