<?php

namespace HomefinanceBundle\User\Model;

use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use HomefinanceBundle\User\Validator\Constraints\EmailExists;

class LostPassword {

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @EmailExists()
     */
    private $email;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

}