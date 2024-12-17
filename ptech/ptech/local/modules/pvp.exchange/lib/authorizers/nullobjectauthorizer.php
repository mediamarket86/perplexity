<?php

namespace PVP\Exchange\authorizers;

class NullObjectAuthorizer implements AuthorizerInteface
{

    public function authorize(string $token): bool
    {
        return false;
    }
}