<?php

namespace PVP\Exchange\authorizers;

use PVP\Exchange\JWT\JWTManager;

class JsonWebTokenAuthorizer implements AuthorizerInteface
{
    protected JWTManager $jwtManager;

    public function __construct()
    {
        $this->jwtManager = new JWTManager();
    }

    public function authorize(string $token): bool
    {
        $userId = $this->jwtManager->getAuthId($token);
        if ($userId) {
            global $USER;

            return $USER->Authorize($userId, false, false);
        }

        return false;
    }
}