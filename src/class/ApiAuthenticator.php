<?php

namespace App\class;

use Pebble\Security\PAS\PasToken;
use Throwable;

class ApiAuthenticator
{
    public function AuthToken()
    {
        $token = new PasToken();
        try {
            //$token->getTokenFromAuthorizationHeader()->decode();
            $token->getTokenFromAuthorizationHeader()->decode();
            return $token;
        } catch (Throwable $e) {
            throw new \InvalidArgumentException('Error : ' . $e->getMessage());
        }
    }
}
