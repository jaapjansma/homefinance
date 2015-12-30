<?php

namespace HomefinanceBundle\User\Model;

use HomefinanceBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use HomefinanceBundle\User\Validator\Constraints\UniqueEmailAddress;

class Profile {

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueEmailAddress()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user) {
        $this->email = !empty($user->getNewEmail()) ? $user->getNewEmail() : $user->getEmail();
        $this->name = $user->getName();
        $this->user = $user;
    }

    public function updateUser(User $user) {
        $user->setEmail($this->email);
        $user->setName($this->name);
        return $user;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param $email
     * @return Profile
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $name
     * @return Profile
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

}