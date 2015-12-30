<?php

namespace HomefinanceBundle\User\Factory;

use HomefinanceBundle\User\Model\ChangePassword;
use HomefinanceBundle\User\Model\Confirmation;
use HomefinanceBundle\User\Model\LostPassword;
use HomefinanceBundle\User\Model\Profile;
use HomefinanceBundle\User\Entity\User;
use HomefinanceBundle\User\Model\Registration;
use HomefinanceBundle\User\Model\ResetPassword;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory implements FactoryInterface {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function getProfile(User $user) {
        return new Profile($user);
    }

    public function getChangePassword(User $user) {
        return new ChangePassword($this->encoder, $user);
    }

    public function getRegistration() {
        return new Registration($this->encoder);
    }

    public function getAccountConfirmation($token=null) {
        $c = new Confirmation();
        $c->setConfirmationToken($token);
        return $c;
    }

    public function getLostPassword() {
        return new LostPassword();
    }

    public function getResetPassword() {
        return new ResetPassword($this->encoder);
    }

}