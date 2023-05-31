<?php

namespace App\class;

use Throwable;

class ApiAuthenticator
{
    public function authenticate($credentials)
    {
        $token = new PasToken(); // Assurez-vous que la classe PasToken existe et est correctement importée

        try {
            $token->getTokenFromAuthorizationHeader()->decode();
        } catch (Throwable $exception) {
            // Gérez l'exception d'une manière appropriée, par exemple en enregistrant un message d'erreur ou en renvoyant une réponse d'erreur
        }
    }
}
