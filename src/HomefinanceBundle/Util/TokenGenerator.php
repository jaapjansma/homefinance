<?php

namespace HomefinanceBundle\Util;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TokenGenerator implements TokenGeneratorInterface
{
    public function __construct()
    {

    }

    public function generateToken()
    {
        return rtrim(strtr(base64_encode($this->getRandomNumber()), '+/', '-_'), '=');
    }

    private function getRandomNumber()
    {
        return hash('sha256', uniqid(mt_rand(), true), true);
    }
}