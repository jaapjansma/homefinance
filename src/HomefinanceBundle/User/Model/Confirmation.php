<?php

namespace HomefinanceBundle\User\Model;

use Symfony\Component\Validator\Constraints as Assert;
use HomefinanceBundle\User\Validator\Constraints\ValidConfirmationToken;

class Confirmation {

    /**
     * @var string
     * @Assert\NotBlank()
     * @ValidConfirmationToken()
     */
    private $confirmationToken;

    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($token=null) {
        $this->confirmationToken = $token;
    }

}