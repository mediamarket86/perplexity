<?php

namespace PVP\Exchange\authorizers;

interface AuthorizerInteface
{
    public function authorize(string $token): bool;
}