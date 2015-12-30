<?php

namespace HomefinanceBundle\User\Model;

use HomefinanceBundle\User\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePassword {

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=8,max = 4096)
     */
    private $newPassword;

    /**
     * @var string
     * @SecurityAssert\UserPassword()
     */
    private $currentPassword;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var User
     */
    private $user;

    public function __construct(UserPasswordEncoderInterface $encoder, User $user) {
        $this->passwordEncoder = $encoder;
        $this->user = $user;
    }

    public function setNewPassword($password) {
        $this->newPassword = $this->passwordEncoder->encodePassword($this->user, $password);
         return $this;
    }

    public function getNewPassword() {
        return $this->newPassword;
    }

    public function getUpdatedUser() {
        $this->user->setPassword($this->newPassword);
        return $this->user;
    }

    public function setCurrentPassword($password) {
        $this->currentPassword = $password;
        return $this;
    }

    public function getCurrentPassword() {
        return $this->currentPassword;
    }

}